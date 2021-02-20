import React from 'react';
import ReactDOM from 'react-dom';
import { Row } from 'react-bootstrap';
import { GiBroom } from 'react-icons/gi'

function Table() {
  return (
    <Row className="justify-content-center">
      <div className="col-md-8">
        <div className="card">
          <div className="card-header">Table Component</div>

          <div className="card-body"><GiBroom /></div>
        </div>
      </div>
    </Row>
  );
}

export default Table;

if (document.getElementById('merchant-table')) {
  ReactDOM.render(<Table />, document.getElementById('merchant-table'));
}
