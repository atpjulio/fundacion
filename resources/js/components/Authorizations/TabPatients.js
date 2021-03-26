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
  const ajaxUrl = '/ajax/patients';
  const baseUrl = '/patients';
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

  const handleSelected = (patient) => {
    const patientInfo =
      patient.first_name +
      ' ' +
      patient.last_name +
      ' - ' +
      documentTypes[patient.dni_type] +
      ': ' +
      patient.dni;
    setValues({ ...values, patient_id: patient.id, patientInfo: patientInfo });
    // setSelected(patient.id)
    // setPatientInfo(patient.first_name + ' ' + patient.last_name + ' - ' + patient.dni_type + ': ' + patient.dni)
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
          records.map((patient) => (
            <tr key={patient.id}>
              <td>{patient.first_name + ' ' + patient.last_name}</td>
              <td>
                {documentTypes[patient.dni_type]}: {patient.dni}
              </td>
              <td>
                {patient.id === values.patient_id ? (
                  <span className="text-success">
                    <FaCheckCircle />
                  </span>
                ) : (
                  <OverlayTrigger
                    placement="top"
                    overlay={
                      <Tooltip id={'button-select-' + patient.id}>
                        Seleccionar
                      </Tooltip>
                    }
                  >
                    <span
                      className="text-secondary delete-button"
                      onClick={() => handleSelected(patient)}
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

  // return (
  //   <div  className="pt-4">
  //     Tab de pacientes
  //   </div>
  // );
};
