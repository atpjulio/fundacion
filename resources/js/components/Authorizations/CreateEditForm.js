import React, { useEffect, useState } from 'react';
import {
  Card,
  Col,
  FormControl,
  Row,
  Tab,
  Tabs,
  Form,
  Button,
  Alert,
} from 'react-bootstrap';
import ReactDOM from 'react-dom';
import useAsyncOptionsGet from '../Hooks/useAsyncOptionsGet';
import AsyncSelect from 'react-select/async';
import Debug from '../Shared/Debug';
import TabCompanions from './TabCompanions';
import TabPatients from './TabPatients';
import TabServices from './TabServices';
import moment from 'moment';
import CreateEditErrors from './CreateEditErrors';
import axios from 'axios';
import useGet from '../Hooks/useGet';
import { buildPatientInfo } from '../Shared/helpers';

const CreateEditForm = () => {
  const authorizationId = window?.location?.pathname.split('/')[2];
  const isEdit = authorizationId !== 'create';
  const formUrl = isEdit ? `/ajax/authorizations/${authorizationId}` : '/ajax/authorizations';
  const selectUrl = '/ajax/eps';
  const [oldAuthorization, setOldAuthorization] = useState();
  const loadOld = isEdit ? useGet({
    url: formUrl,
    params: {
    },
  }) : null;
  const [checkErrors, setCheckErrors] = useState(false);
  const [searchSelect, setSearchSelect] = useState('');
  const [values, setValues] = useState({
    authorizationExists: false,
    code: '',
    date: '',
    eps_id: '',
    patient_id: '',
    patientInfo: '',
    services: [],
    companion_ids: [],
    companionInfos: [],
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
    setCheckErrors(false);
  };

  const handleSelectChange = (event) => {
    setValues({
      ...values,
      eps_id: event.value,
      patient_id: '',
      patientInfo: '',
      services: [],
      companion_ids: [],
      companionInfos: [],
    });
    setCheckErrors(false);
  };

  const handleSelectSearch = (newValue) => {
    setSearchSelect(newValue);
    return newValue;
  };

  const handleSubmit = async () => {
    const existAnyError = Boolean(
      values.authorizationExists ||
        values.code?.length < 1 ||
        values.date?.length < 1 ||
        values.eps?.length < 1 ||
        values.services?.length < 1 ||
        values.patientInfo?.length < 1
    );
    setCheckErrors(existAnyError);
    if (existAnyError) return;

    try {
      if (isEdit) {
        await axios.put(formUrl, values);
      } else {
        await axios.post(formUrl, values);
      }
    } catch (error) {
      console.warn(error);
    }
    window.location.href = isEdit ? '/authorizations?updated=1' : '/authorizations?stored=1';
  };

  useEffect(() => {
    if (!isEdit) return;

    loadOld().then((data) => {
      const authorization = data.result?.[0];
    
      setOldAuthorization(authorization);

      const oldValues = {
        code: authorization.code,
        date: authorization.date,
        eps_id: authorization.eps_id,
        patient_id: authorization.patient_id,
        patientInfo: buildPatientInfo(authorization?.patient),
      };

      setValues(prev => ({...prev, ...oldValues}));
    });  
  }, []);

  return (
    <Row>
      <Col xs={12}>
        <Card>
          <Card.Body>
            <Debug values={values} />
            <Debug values={oldAuthorization} />
            <Row>
              <Col md={4}>
                <Form.Label>Seleccione EPS *</Form.Label>
                <AsyncSelect
                  loadOptions={loadOptions}
                  defaultOptions
                  // defaultOptions={isEdit ? {value: String(oldAuthorization?.id), label: oldAuthorization?.eps?.name } : null}
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
                  defaultActiveKey="servicesTab"
                  id="authorization-handling"
                >
                  <Tab eventKey="servicesTab" title="Servicios">
                    <TabServices
                      values={values}
                      setValues={setValues}
                      option={values.eps_id}
                    />
                  </Tab>
                  <Tab eventKey="patientsTab" title="Pacientes">
                    <TabPatients
                      values={values}
                      setValues={setValues}
                      option={values.eps_id}
                    />
                  </Tab>
                  <Tab eventKey="companionsTab" title="Acompañantes">
                    <TabCompanions
                      values={values}
                      setValues={setValues}
                      option={values.eps_id}
                    />
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
              <h4>
                Resumen de autorización: {values.code} - Fecha:{' '}
                {values.date.length > 0
                  ? moment(values.date).format('DD/MM/YYYY')
                  : '-'}
              </h4>
              <hr />
            </Card.Title>
            <h6>
              <b>Paciente</b>
            </h6>
            <ul>
              <li>
                {values.patientInfo?.length > 0 ? values.patientInfo : '--'}
              </li>
            </ul>
            <h6>
              <b>Servicio(s)</b>
            </h6>
            <ul>
              {values.services?.length > 0 ? (
                values.services.map((service) => (
                  <li key={service.id}>
                    {service.quantity} x {service.info}
                  </li>
                ))
              ) : (
                <li>--</li>
              )}
            </ul>
            <h6>
              <b>Acompañante(s)</b>
            </h6>
            <ul>
              {values.companionInfos?.length > 0 ? (
                values.companionInfos.map((companionInfo, index) => (
                  <li key={index}>{companionInfo}</li>
                ))
              ) : (
                <li>--</li>
              )}
            </ul>
            <CreateEditErrors formValues={values} showErrors={checkErrors} />
          </Card.Body>
        </Card>
      </Col>
      <Col xs={12} className="text-center">
        <Button
          className="btn-oval"
          variant="primary"
          type="button"
          onClick={handleSubmit}
        >
          Guardar
        </Button>
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
