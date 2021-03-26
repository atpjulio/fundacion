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
import { FaClipboardList, FaFileInvoiceDollar, FaUser } from 'react-icons/fa';
import { Badge, OverlayTrigger, Tooltip } from 'react-bootstrap';
import { formatCOP } from '../Shared/helpers';

const Table = () => {
  const ajaxUrl = '/ajax/authorizations';
  const selectUrl = '/ajax/eps';
  const baseUrl = '/authorizations';
  const [records, setRecords] = useState([]);
  const [links, setLinks] = useState(undefined);
  const [search, setSearch] = useState('');
  const [searchSelect, setSearchSelect] = useState('');
  const [sortDirection, setSortDirection] = useState('desc');
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
      option: option,
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
    swal('¡Bien hecho!', 'Autorización borrada exitosamente', 'success');
  };

  const handleEdit = (authorization) => {
    return (window.location.href = `${baseUrl}/${authorization.id}/edit`);
  };

  const handleSelectSearch = (newValue) => {
    setSearchSelect(newValue);
    return newValue;
  };

  const tableHeaders = (
    <>
      <th>Código</th>
      <th>Paciente</th>
      <th>Resumen</th>
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
        Borrar la Autorización: <strong>{recordForDelete.code}</strong>
      </DeleteModal>
      <Search
        searchText="Búsqueda por código..."
        search={search}
        setSearch={setSearch}
        sortDirection={sortDirection}
        setSortDirection={setSortDirection}
        buttonUrl={baseUrl + '/create'}
        loadOptions={loadOptions}
        optionsPlaceholder={'Todas las EPS'}
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
          records.map((authorization) => (
            <tr key={authorization.id}>
              <td>{authorization.code}</td>
              <td>
                {authorization.patient?.first_name +
                  ' ' +
                  authorization.patient?.last_name}
              </td>
              <td>
                <ServicesSummary services={authorization.services} />
                <CompanionsSummary companions={authorization.companions} />
                <InvoicesSummary />
              </td>
              <td>
                <Actions
                  record={authorization}
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

const ServicesSummary = (props) => {
  const { services = [] } = props;

  return services.map((service) => (
    <OverlayTrigger
      key={service.id}
      placement="top"
      overlay={
        <Tooltip id={'service-' + service.id}>
          {service.quantity} x {formatCOP(Number(service.authorizable?.amount))}{' '}
          = {formatCOP(Number(service.quantity * Number(service.authorizable?.amount)))}
        </Tooltip>
      }
    >
      <Badge className="mr-3" variant="info" key={service.id}>
        <FaClipboardList /> {service.authorizable?.code} x {service.quantity}
      </Badge>
    </OverlayTrigger>
  ));
};

const CompanionsSummary = (props) => {
  const { companions = [] } = props;

  return companions.map((companion) => (
    <Badge className="mr-3" variant="success" key={companion.id}>
      <FaUser />{' '}
      {companion.authorizable?.first_name +
        ' ' +
        companion.authorizable?.last_name}
    </Badge>
  ));
};

const InvoicesSummary = () => {
  return (
    <Badge className="mr-3" variant="warning">
      <FaFileInvoiceDollar /> 12031
    </Badge>
  );
};

export default Table;

if (document.getElementById('authorization-table')) {
  ReactDOM.render(<Table />, document.getElementById('authorization-table'));
}
