import React, { useEffect, useState } from 'react';
import { Card, Col, FormControl, Row, Tab, Tabs, Form } from 'react-bootstrap';
import ReactDOM from 'react-dom';
import useAsyncOptionsGet from '../Hooks/useAsyncOptionsGet';
import AsyncSelect from 'react-select/async';
import Debug from '../Shared/Debug';
import TabCompanions from './TabCompanions';
import TabPatients from './TabPatients';
import TabServices from './TabServices';

const CreateEditForm = () => {
  const selectUrl = '/ajax/eps';
  const [searchSelect, setSearchSelect] = useState('');
  const [values, setValues] = useState({
    code: '',
    date: '',
    eps_id: '',
    patient_id: '',
    patientInfo: '',
  });
  const loadOptions = useAsyncOptionsGet({
    url: selectUrl,
    params: {
      search: searchSelect,
      limit: 10,
    },
    field: 'name',
  });

  const handleValueChange = (event) => {
    setValues({ ...values, [event.target.name]: event.target.value });
  };

  const handleSelectChange = (event) => {
    setValues({
      ...values,
      eps_id: event.value,
      patient_id: '',
      patientInfo: '',
    });
  };

  const handleSelectSearch = (newValue) => {
    setSearchSelect(newValue);
    return newValue;
  };

  return (
    <Row>
      <Col xs={12}>
        <Card>
          <Card.Body>
            <Debug values={values} />
            <Row>
              <Col md={4}>
                <Form.Label>Seleccione EPS *</Form.Label>
                <AsyncSelect
                  loadOptions={loadOptions}
                  defaultOptions
                  cacheOptions
                  placeholder="Seleccione"
                  onInputChange={handleSelectSearch}
                  onChange={handleSelectChange}
                />
              </Col>
              <Col md={4}>
                <Form.Label>Código de autorización *</Form.Label>
                <FormControl
                  name="code"
                  placeholder="Código de autorización"
                  className="underlined"
                  onChange={handleValueChange}
                  value={values.code}
                />
              </Col>
              <Col md={4}>
                <Form.Label>Fecha de autorización *</Form.Label>
                <FormControl
                  type="date"
                  name="date"
                  placeholder="Fecha de autorización"
                  onChange={handleValueChange}
                  className="underlined"
                  value={values.date}
                />
              </Col>
              <Col md={12} className="py-3">
                <Tabs
                  defaultActiveKey="patientsTab"
                  id="authorization-handling"
                >
                  <Tab eventKey="patientsTab" title="Pacientes">
                    <TabPatients
                      values={values}
                      setValues={setValues}
                      option={values.eps_id}
                    />
                  </Tab>
                  <Tab eventKey="servicesTab" title="Servicios">
                    <TabServices onChange={handleValueChange} />
                  </Tab>
                  <Tab eventKey="companionsTab" title="Acompañantes">
                    <TabCompanions onChange={handleValueChange} />
                  </Tab>
                </Tabs>
              </Col>
            </Row>
          </Card.Body>
        </Card>
      </Col>
      <Col xs={12}>
        <Card>
          <Card.Body>
            <Card.Title className="text-center">
              <h4>Resumen de autorización: {values.code} </h4>
              <hr />
            </Card.Title>
            <h6>
              <b>Paciente</b>
            </h6>
            <ul>
              <li>{values.patientInfo?.length > 0 ? values.patientInfo : '--'}</li>
            </ul>
            <h6>
              <b>Servicio(s)</b>
            </h6>
          </Card.Body>
        </Card>
      </Col>
    </Row>
  );
};

export default CreateEditForm;

if (document.getElementById('authorization-form')) {
  ReactDOM.render(
    <CreateEditForm />,
    document.getElementById('authorization-form')
  );
}
