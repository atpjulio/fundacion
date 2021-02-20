import React from 'react'
import { Table } from 'react-bootstrap'
import Paginate from './Paginate'

export default props => {
  const { headers, links, children, onPageChange = () => {} } = props

  return (
    <>
      <Table striped bordered hover size="sm">
        <thead>
          <tr>{headers}</tr>
        </thead>
        <tbody>{children}</tbody>
      </Table>
      <Paginate links={links} onPageChange={onPageChange} />
    </>
  )
}
