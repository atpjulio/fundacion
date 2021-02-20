import React, { useEffect, useState } from 'react';
import ReactDOM from 'react-dom';
import useGet from '../Hooks/useGet';
import EmptyResults from '../Shared/EmptyResults';
import TablePaginate from '../Shared/TablePaginate';
import { companyDocumentTypes } from '../Config/constants';
import Search from '../Shared/Search';

const Table = () => {
  const ajaxUrl = '/ajax/merchants';
  const baseUrl = '/merchants';
  const [records, setRecords] = useState([]);
  const [links, setLinks] = useState(undefined);
  const [search, setSearch] = useState('');
  const [sortDirection, setSortDirection] = useState('asc');
  const [page, setPage] = useState(1);
  const loadRecords = useGet({
    url: ajaxUrl,
    params: {
      filters: { search: search },
      limit: 30,
      sortDirection: 'asc',
    },
  });

  useEffect(() => {
    loadRecords().then((data) => {
      setLinks(data.links);
      setRecords(data.result);
    });
  }, [search, sortDirection, page]);

  const handlePageChange = (selected) => setPage(selected);

  console.log('records', records);

  const tableHeaders = (
    <>
      <th>#</th>
      <th>Nombre</th>
      <th>Documento</th>
      <th>Acciones</th>
    </>
  );

  return (
    <>
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
          records.map((merchant) => (
            <tr key={merchant.id}>
              <td>{merchant.id}</td>
              <td>{merchant.name}</td>
              <td>
                {companyDocumentTypes[merchant.dni_type]}: {merchant.dni}
              </td>
              <td>
                {/* <Actions
                record={merchant}
                handleShowDeleteModal={handleShowDeleteModal}
                handleEdit={handleEdit}
              /> */}
              </td>
            </tr>
          ))
        )}
      </TablePaginate>
    </>
  );
};

export default Table;

if (document.getElementById('merchant-table')) {
  ReactDOM.render(<Table />, document.getElementById('merchant-table'));
}
