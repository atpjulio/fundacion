import React from 'react';
import { Alert } from 'react-bootstrap';

export default (props) => {
  return (
    <Alert variant='secondary' className="pt-4">
      <pre>{JSON.stringify(props.values, null, 2)}</pre>
    </Alert>
  );
};
