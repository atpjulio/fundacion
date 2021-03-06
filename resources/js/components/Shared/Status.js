import React from 'react'
import { Badge } from 'react-bootstrap';

export default props => {
  const { status = 'ACTIVE', textActive = 'ACTIVO', textInactive = 'INACTIVO' } = props;

  let badge = <Badge variant="success">{textActive}</Badge>;
  if (status !== 'ACTIVE')
    badge = <Badge variant="danger">{textInactive}</Badge>

  return badge;
}