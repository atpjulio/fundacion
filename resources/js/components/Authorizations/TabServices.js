import React, { useEffect, useState } from 'react';
import {
  Col,
  FormControl,
  OverlayTrigger,
  Row,
  Tooltip,
} from 'react-bootstrap';
import { FaCheckCircle, FaPencilAlt, FaRegCircle } from 'react-icons/fa';
import useGet from '../Hooks/useGet';
import EmptyResults from '../Shared/EmptyResults';
import { formatCOP } from '../Shared/helpers';
import Search from '../Shared/Search';
import TablePaginate from '../Shared/TablePaginate';
import ServiceQuantityModal from './ServiceQuantityModal';

export default (props) => {
  const { option, setValues = () => {}, values = {} } = props;
  const ajaxUrl = `/ajax/eps/${option}/services`;
  const baseUrl = `/eps/${option}/services`;
  const [quantity, setQuantity] = useState(1);
  const [records, setRecords] = useState([]);
  const [links, setLinks] = useState(undefined);
  const [search, setSearch] = useState('');
  const [sortDirection, setSortDirection] = useState('asc');
  const [show, setShow] = useState(false);
  const [recordForUpdate, setRecordForUpdate] = useState({});
  const [page, setPage] = useState(1);
  const loadRecords = useGet({
    url: ajaxUrl,
    params: {
      search: search,
      limit: 10,
      sortDirection: sortDirection,
      option: 'ACTIVE',
    },
  });

  const handleClose = () => setShow(false);

  const handleShowUpdateModal = (service) => {
    setShow(true);
    setRecordForUpdate(service);
  };

  const handleQuantityChange = (evt) => setQuantity(evt.target.value);

  const handleUpdateQuantity = () => {
    const service_ids = values.services.map(
      (recordForUpdate) => recordForUpdate.id
    );

    if (!service_ids.includes(recordForUpdate.id)) return;

    const updatedServices = values.services.map((oldService) => {
      if (oldService.id === recordForUpdate.id) {
        oldService.quantity = quantity;
      }
      return oldService;
    });

    setValues({
      ...values,
      services: [...updatedServices],
    });
    setShow(false);
  };

  const handleSelected = (service) => {
    const service_ids = values.services.map((oldService) => oldService.id);

    if (service_ids.includes(service.id)) return;

    const serviceInfo = service.code + ' - ' + service.name;
    const newService = {
      id: service.id,
      info: serviceInfo,
      quantity: 1,
    };

    setValues({
      ...values,
      services: [...values.services, newService],
    });
  };

  const handleDeselected = (service) => {
    const service_ids = values.services.map((oldService) => oldService.id);

    if (!service_ids.includes(service.id)) return;

    const updatedServices = values.services.filter(
      (oldService) => oldService.id != service.id
    );

    setValues({
      ...values,
      services: [...updatedServices],
    });
  };

  useEffect(() => {
    loadRecords().then((data) => {
      setLinks(data.links);
      setRecords(data.result);
    });
  }, [search, sortDirection, page, option]);

  const handlePageChange = (selected) => setPage(selected);

  const tableHeaders = (
    <>
      <th>Nombre</th>
      <th>Monto</th>
      <th></th>
    </>
  );

  return (
    <div className="pt-4">
      <ServiceQuantityModal show={show} handleClose={handleClose} handleUpdateQuantity={handleUpdateQuantity}>
        Actualizar cantidad para servicio{' '}
        <strong>{recordForUpdate.code}</strong> de{' '}
        <strong> {formatCOP(recordForUpdate.amount)}</strong>
        <Row>
          <Col md={{ offset: 4, span: 4}}>
            <FormControl
              size={'sm'}
              type="number"
              min="1"
              placeholder="Actualizar cantidad"
              aria-label="Actualizar cantidad"
              onChange={handleQuantityChange}
              className="search-input py-0"
              value={quantity}
            />
          </Col>
        </Row>
      </ServiceQuantityModal>
      <Search
        searchText="Búsqueda por código de servicio..."
        search={search}
        setSearch={setSearch}
        sortDirection={sortDirection}
        setSortDirection={setSortDirection}
        buttonUrl={baseUrl + '/create'}
        isExternal
      />
      <TablePaginate
        headers={tableHeaders}
        links={links}
        onPageChange={handlePageChange}
        showPagination={false}
      >
        {!records || records.length < 1 ? (
          <tr>
            <td colSpan="3">
              <EmptyResults />
            </td>
          </tr>
        ) : (
          records.map((service) => (
            <tr key={service.id}>
              <td>{service.code + ' - ' + service.name}</td>
              <td>{formatCOP(service.amount)}</td>
              <td>
                {values.services.some(
                  (oldService) => oldService.id == service.id
                ) ? (
                  <>
                    <span
                      className="text-success pr-2"
                      onClick={() => handleDeselected(service)}
                    >
                      <FaCheckCircle />
                    </span>
                    <span
                      className="text-info delete-button pr-2"
                      onClick={() => handleShowUpdateModal(service)}
                    >
                      <FaPencilAlt />
                    </span>
                    <span className="text-muted">
                      Cant:{' '}
                      {
                        values.services.filter(
                          (oldService) => oldService.id == service.id
                        )[0].quantity
                      }
                    </span>
                  </>
                ) : (
                  <OverlayTrigger
                    placement="top"
                    overlay={
                      <Tooltip id={'button-select-' + service.id}>
                        Seleccionar
                      </Tooltip>
                    }
                  >
                    <span
                      className="text-secondary delete-button"
                      onClick={() => handleSelected(service)}
                    >
                      <FaRegCircle />
                    </span>
                  </OverlayTrigger>
                )}
              </td>
            </tr>
          ))
        )}
      </TablePaginate>
    </div>
  );
};
