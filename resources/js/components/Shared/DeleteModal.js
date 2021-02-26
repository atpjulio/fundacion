import React from 'react'
import { Button, Modal } from 'react-bootstrap'
import { FaTimes, FaTrashAlt } from 'react-icons/fa'

export default props => {
  const {
    title = 'Borrar elemento',
    show,
    handleClose = () => {},
    handleDelete = () => {},
    children,
  } = props

  return (
    <Modal show={show} onHide={handleClose}>
      <Modal.Header closeButton>
        <Modal.Title>{title}</Modal.Title>
      </Modal.Header>
      <Modal.Body>{children}</Modal.Body>
      <Modal.Footer>
        <Button variant="secondary" onClick={handleClose}>
          <span className="mr-2">
            <FaTimes />
          </span>
          Cancelar
        </Button>
        <Button variant="danger" onClick={handleDelete}>
          <span className="mr-2">
            <FaTrashAlt />
          </span>
          Borrar
        </Button>
      </Modal.Footer>
    </Modal>
  )
}
