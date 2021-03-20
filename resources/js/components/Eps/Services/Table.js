import React, { useEffect, useState } from 'react';
import ReactDOM from 'react-dom';
import useGet from '../../Hooks/useGet';
import EmptyResults from '../../Shared/EmptyResults';
import TablePaginate from '../../Shared/TablePaginate';
import Search from '../../Shared/Search';
import Actions from './TableAction';
import DeleteModal from '../../Shared/DeleteModal';
import swal from 'sweetalert';
import axios from 'axios';
import Status from '../../Shared/Status';
import { formatCOP } from '../../Shared/helpers';

const Table = () => {
  const epsId = window?.location?.pathname.split('/')[2];

  const ajaxUrl = `/ajax/eps/${epsId}/services`;
  const baseUrl = `/eps/${epsId}/services`;
  const [records, setRecords] = useState([]);
  const [links, setLinks] = useState(undefined);
  const [search, setSearch] = useState('');
  const [sortDirection, setSortDirection] = useState('asc');
  const [page, setPage] = useState(1);
  const [show, setShow] = useState(false)
  const [recordForDelete, setRecordForDelete] = useState({})
  const loadRecords = useGet({
    url: ajaxUrl,
    params: {
      search: search,
      limit: 30,
      sortDirection: sortDirection,
    },
  });

  useEffect(() => {
    loadRecords().then((data) => {
      setLinks(data.links);
      setRecords(data.result);
    });
  }, [search, sortDirection, page, recordForDelete]);

  const handlePageChange = (selected) => setPage(selected);
  const handleClose = () => setShow(false)

  const handleShowDeleteModal = eps => {
    setShow(true)
    setRecordForDelete(eps)
  }

  const handleDelete = async () => {
    try {
      await axios.delete(ajaxUrl + '/' + recordForDelete.id)
    } catch (error) {
      console.warn(error)
      setRecordForDelete({})
      setShow(false)
      swal('¡Ups!', 'Ocurrió un problema durante el borrado', 'error')
      return;
    }
    setRecordForDelete({})
    setShow(false)
    swal('¡Bien hecho!', 'Servicio borrado exitosamente', 'success')
  }

  const handleEdit = service => {
    return window.location.href = `/eps/${epsId}/services/${service.id}/edit`;
  }

  const tableHeaders = (
    <>
      <th>Nombre</th>
      <th>Monto</th>
      <th>Estado</th>
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
        Borrar el servicio: <strong>{recordForDelete.name}</strong>
      </DeleteModal>
      <Search
        searchText="Búsqueda por nombre..."
        search={search}
        setSearch={setSearch}
        sortDirection={sortDirection}
        setSortDirection={setSortDirection}
        buttonUrl={baseUrl + '/create'}
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
          records.map((service) => (
            <tr key={service.id}>
              <td>{service.code + ' - ' + service.name}</td>
              <td>{formatCOP(service.amount)}</td>
              <td><Status status={service.status} /></td>
              <td>
                <Actions
                  record={service}
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

if (document.getElementById('eps-service-table')) {
  ReactDOM.render(<Table />, document.getElementById('eps-service-table'));
}
