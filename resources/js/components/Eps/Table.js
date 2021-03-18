import React, { useEffect, useState } from 'react';
import ReactDOM from 'react-dom';
import useAsyncOptionsGet from '../Hooks/useAsyncOptionsGet';
import useGet from '../Hooks/useGet';
import EmptyResults from '../Shared/EmptyResults';
import TablePaginate from '../Shared/TablePaginate';
import Search from '../Shared/Search';
import Actions from './TableAction';
import DeleteModal from '../Shared/DeleteModal';
import swal from 'sweetalert';
import axios from 'axios';

const Table = () => {
  const ajaxUrl = '/ajax/eps';
  const selectUrl = '/ajax/merchants';
  const baseUrl = '/new/eps';
  const [records, setRecords] = useState([]);
  const [links, setLinks] = useState(undefined);
  const [search, setSearch] = useState('');
  const [searchSelect, setSearchSelect] = useState('');
  const [sortDirection, setSortDirection] = useState('asc');
  const [page, setPage] = useState(1);
  const [option, setOption] = useState(0);
  const [show, setShow] = useState(false);
  const [recordForDelete, setRecordForDelete] = useState({});
  const loadRecords = useGet({
    url: ajaxUrl,
    params: {
      search: search,
      limit: 30,
      sortDirection: sortDirection,
      option: option
    },
  });
  const loadOptions = useAsyncOptionsGet({
    url: selectUrl,
    params: {
      search: searchSelect,
      limit: 10,
    },
    field: 'name',
  });

  useEffect(() => {
    loadRecords().then((data) => {
      setLinks(data.links);
      setRecords(data.result);
    });
  }, [search, sortDirection, page, recordForDelete, option]);

  const handlePageChange = (selected) => setPage(selected);
  const handleClose = () => setShow(false);

  const handleShowDeleteModal = (merchant) => {
    setShow(true);
    setRecordForDelete(merchant);
  };

  const handleDelete = async () => {
    try {
      await axios.delete(ajaxUrl + '/' + recordForDelete.id);
    } catch (error) {
      console.warn(error);
      setRecordForDelete({});
      setShow(false);
      swal('¡Ups!', 'Ocurrió un problema durante el borrado', 'error');
      return;
    }
    setRecordForDelete({});
    setShow(false);
    swal('¡Bien hecho!', 'EPS borrada exitosamente', 'success');
  };

  const handleEdit = (merchant) => {
    return (window.location.href = `${baseUrl}/${merchant.id}/edit`);
  };

  const handleSelectSearch = (newValue) => {
    setSearchSelect(newValue);
    return newValue;
  };

  const tableHeaders = (
    <>
      <th>Código</th>
      <th>Nombre</th>
      <th>Empresa asociada</th>
      <th>Acciones</th>
    </>
  );

  return (
    <>
      <DeleteModal
        show={show}
        handleClose={handleClose}
        handleDelete={handleDelete}
      >
        Borrar la EPS: <strong>{recordForDelete.name}</strong>
      </DeleteModal>
      <Search
        searchText="Búsqueda por nombre..."
        search={search}
        setSearch={setSearch}
        sortDirection={sortDirection}
        setSortDirection={setSortDirection}
        buttonUrl={baseUrl + '/create'}
        loadOptions={loadOptions}
        optionsPlaceholder={'Todas las empresas'}
        handleSelectSearch={handleSelectSearch}
        setOption={setOption}
        withOptions
      />
      <TablePaginate
        headers={tableHeaders}
        links={links}
        onPageChange={handlePageChange}
      >
        {records.length < 1 ? (
          <tr>
            <td colSpan="4">
              <EmptyResults />
            </td>
          </tr>
        ) : (
          records.map((eps) => (
            <tr key={eps.id}>
              <td>{eps.code}</td>
              <td>{eps.name}</td>
              <td>{eps.merchant.name}</td>
              <td>
                <Actions
                  record={eps}
                  handleShowDeleteModal={handleShowDeleteModal}
                  handleEdit={handleEdit}
                />
              </td>
            </tr>
          ))
        )}
      </TablePaginate>
    </>
  );
};

export default Table;

if (document.getElementById('eps-table')) {
  ReactDOM.render(<Table />, document.getElementById('eps-table'));
}
