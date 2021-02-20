import React from 'react'
import { Button, Col, FormControl, InputGroup, Row } from 'react-bootstrap'
import { GiBroom } from 'react-icons/gi'
import {
  FaAngleLeft,
  FaPlus,
  FaSortAmountDownAlt,
  FaSortAmountUp
} from 'react-icons/fa'
import SelectSearch from 'react-select-search'

export default props => {
  const {
    searchText,
    search,
    setSearch,
    sortDirection,
    setSortDirection,
    option,
    setOption,
    options = [],
    searchPlaceholder = 'Seleccione',
    buttonUrl = '',
    buttonReturnUrl = ''
  } = props

  const handleSearchChange = evt => setSearch(evt.target.value)

  const handleCleanSearch = () => setSearch('')

  const handleSortDirection = () => {
    if (sortDirection === 'asc') setSortDirection('desc')
    else setSortDirection('asc')
  }

  return (
    <Row>
      <Col md={options?.length > 1 ? 9 : 12}>
        <InputGroup className="mb-3">
          {buttonReturnUrl.length > 0 ? (
            <InputGroup.Append>
              <InertiaLink
                className="btn btn-secondary btn-sm"
                href={buttonReturnUrl}
              >
                <FaAngleLeft />
              </InertiaLink>
            </InputGroup.Append>
          ) : null}
          <FormControl
            size={'sm'}
            placeholder={searchText}
            aria-label={searchText}
            onChange={handleSearchChange}
            value={search}
          />
          <InputGroup.Append>
            <Button size="sm" variant="secondary" onClick={handleCleanSearch} style={{ zIndex: 0 }}>
              <GiBroom />
            </Button>
          </InputGroup.Append>
          {sortDirection && <InputGroup.Append>
            <Button size="sm" variant="dark" onClick={handleSortDirection}>
              {sortDirection === 'asc' ? (
                <FaSortAmountDownAlt />
              ) : (
                <FaSortAmountUp />
              )}
            </Button>
          </InputGroup.Append>}
          {buttonUrl.length > 0 ? (
            <InputGroup.Append>
              <InertiaLink className="btn btn-primary btn-sm" href={buttonUrl}>
                <FaPlus />
              </InertiaLink>
            </InputGroup.Append>
          ) : null}
        </InputGroup>
      </Col>
      {options?.length > 1 ? (
        <Col>
          <SelectSearch
            search
            options={options}
            value={option}
            placeholder={searchPlaceholder}
            onChange={setOption}
          />
        </Col>
      ) : null}
    </Row>
  )
}
