import React from 'react'
import { OverlayTrigger, Tooltip } from 'react-bootstrap'
import { FaPencilAlt, FaTrashAlt, FaWpforms } from 'react-icons/fa'

export default props => {
  const { record, handleEdit, handleShowDeleteModal } = props

  return (
    <>
      <OverlayTrigger
        placement="top"
        overlay={<Tooltip id={'button-edit-' + record.id}>Editar</Tooltip>}
      >
        <span
          className="text-info delete-button"
          onClick={() => handleEdit(record)}
        >
          <FaPencilAlt />
        </span>
      </OverlayTrigger>
      <OverlayTrigger
        placement="top"
        overlay={<Tooltip id={'button-delete-' + record.id}>Borrar</Tooltip>}
      >
        <span
          className="text-danger delete-button ml-2"
          onClick={() => handleShowDeleteModal(record)}
        >
          <FaTrashAlt />
        </span>
      </OverlayTrigger>{' '}
    </>
  )
}