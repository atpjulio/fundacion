import React, { useEffect, useState } from 'react';
import { OverlayTrigger, Tooltip } from 'react-bootstrap';
import { FaCheckCircle, FaRegCircle } from 'react-icons/fa';
import { documentTypes } from '../Config/constants';
import useGet from '../Hooks/useGet';
import EmptyResults from '../Shared/EmptyResults';
import Search from '../Shared/Search';
import TablePaginate from '../Shared/TablePaginate';

export default (props) => {
  const { option, setValues = () => {}, values = {} } = props;
  const ajaxUrl = '/ajax/companions';
  const baseUrl = '/companions';
  const [records, setRecords] = useState([]);
  const [links, setLinks] = useState(undefined);
  const [search, setSearch] = useState('');
  const [sortDirection, setSortDirection] = useState('asc');
  const [page, setPage] = useState(1);
  const loadRecords = useGet({
    url: ajaxUrl,
    params: {
      search: search,
      limit: 10,
      sortDirection: sortDirection,
      option: option,
    },
  });

  const handleSelected = (companion) => {
    const companion_ids = values.companion_ids;

    if (companion_ids.includes(companion.id)) return;

    companion_ids.push(companion.id);

    const companionInfo =
      companion.first_name +
      ' ' +
      companion.last_name +
      ' - ' +
      documentTypes[companion.dni_type] +
      ': ' +
      companion.dni;

    const companionInfos = [...values.companionInfos, companionInfo];

    setValues({
      ...values,
      companion_ids: companion_ids,
      companionInfos: companionInfos,
    });
  };

  const handleDeselected = (companion) => {
    let companion_ids = values.companion_ids;

    if (!companion_ids.includes(companion.id)) return;

    companion_ids = companion_ids.filter(
      (companion_id) => companion_id != companion.id
    );

    const companionInfo =
      companion.first_name +
      ' ' +
      companion.last_name +
      ' - ' +
      documentTypes[companion.dni_type] +
      ': ' +
      companion.dni;

    const companionInfos = values.companionInfos.filter(
      (oldCompanionInfo) => oldCompanionInfo != companionInfo
    );

    setValues({
      ...values,
      companion_ids: companion_ids,
      companionInfos: companionInfos,
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
      <th>Documento</th>
      <th></th>
    </>
  );

  return (
    <div className="pt-4">
      <Search
        searchText="Búsqueda por número de documento..."
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
        {records.length < 1 ? (
          <tr>
            <td colSpan="3">
              <EmptyResults />
            </td>
          </tr>
        ) : (
          records.map((companion) => (
            <tr key={companion.id}>
              <td>{companion.first_name + ' ' + companion.last_name}</td>
              <td>
                {documentTypes[companion.dni_type]}: {companion.dni}
              </td>
              <td>
                {values.companion_ids?.includes(companion.id) ? (
                  <span
                    className="text-success"
                    onClick={() => handleDeselected(companion)}
                  >
                    <FaCheckCircle />
                  </span>
                ) : (
                  <OverlayTrigger
                    placement="top"
                    overlay={
                      <Tooltip id={'button-select-' + companion.id}>
                        Seleccionar
                      </Tooltip>
                    }
                  >
                    <span
                      className="text-secondary delete-button"
                      onClick={() => handleSelected(companion)}
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
