import React from 'react'
import { Table } from 'react-bootstrap'
import Paginate from './Paginate'

export default props => {
  const { headers, links, children, onPageChange = () => {}, showPagination = true } = props

  return (
    <>
      <Table striped bordered hover size="sm">
        <thead>
          <tr>{headers}</tr>
        </thead>
        <tbody>{children}</tbody>
      </Table>
      {showPagination ? <Paginate links={links} onPageChange={onPageChange} /> : null}
    </>
  )
}
