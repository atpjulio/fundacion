import React from 'react'
import { Button, Modal } from 'react-bootstrap'
import { FaSave, FaTimes } from 'react-icons/fa'

export default props => {
  const {
    title = 'Actualizar cantidad',
    show,
    handleClose = () => {},
    handleUpdateQuantity = () => {},
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
        <Button variant="info" onClick={handleUpdateQuantity}>
          <span className="mr-2">
            <FaSave />
          </span>
          Guardar cambios
        </Button>
      </Modal.Footer>
    </Modal>
  )
}
