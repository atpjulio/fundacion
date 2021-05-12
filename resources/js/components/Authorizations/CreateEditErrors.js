import React from 'react';
import { Alert } from 'react-bootstrap';

export default (props) => {
  const { formValues, showErrors = false } = props;
  const errors = [];

  if (formValues.authorizationExists) {
    errors.push('Código de autorización ya existe');
  }
  if (formValues.code?.length < 1) {
    errors.push('Código de autorización inválido');
  }
  if (formValues.date?.length < 1) {
    errors.push('Fecha de autorización inválida');
  }
  if (formValues.eps?.length < 1) {
    errors.push('Debes seleccionar una EPS');
  }
  if (formValues.services?.length < 1) {
    errors.push('Debes seleccionar al menos un servicio');
  }
  if (formValues.patientInfo?.length < 1) {
    errors.push('Debes seleccionar un paciente');
  }

  const show = errors.length > 0 && showErrors;
  
  return show ? (
    <Alert
      variant="danger"
      style={{ backgroundColor: '#fff', color: '#dd4b39' }}
    >
      {errors.map((error, index) => (
        <li key={index}>{error}</li>
      ))}
    </Alert>
  ) : null;
};
