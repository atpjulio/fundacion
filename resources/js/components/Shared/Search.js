import React from 'react';
import { Button, Col, FormControl, InputGroup, Row } from 'react-bootstrap';
import { GiBroom } from 'react-icons/gi';
import {
  FaAngleLeft,
  FaPlus,
  FaSortAmountDownAlt,
  FaSortAmountUp,
} from 'react-icons/fa';
import AsyncSelect from 'react-select/async';

export default (props) => {
  const {
    searchText,
    search,
    setSearch,
    sortDirection,
    setSortDirection,
    loadOptions,
    optionsPlaceholder = 'Seleccione',
    handleSelectSearch = () => {},
    setOption = () => {},
    buttonUrl = '',
    buttonReturnUrl = '',
    withOptions,
  } = props;

  const handleSearchChange = (evt) => setSearch(evt.target.value);

  const handleCleanSearch = () => setSearch('');

  const handleSortDirection = () => {
    if (sortDirection === 'asc') setSortDirection('desc');
    else setSortDirection('asc');
  };

  const handleChange = (event) => {
    setOption(event.value);
  };

  return (
    <Row>
      <Col md={withOptions ? 9 : 12}>
        <InputGroup className="mb-3 pt-1">
          {buttonReturnUrl.length > 0 ? (
            <InputGroup.Append>
              <a className="btn btn-secondary" href={buttonReturnUrl}>
                <FaAngleLeft />
              </a>
            </InputGroup.Append>
          ) : null}
          <FormControl
            size={'sm'}
            placeholder={searchText}
            aria-label={searchText}
            onChange={handleSearchChange}
            className="search-input py-0"
            value={search}
          />
          <InputGroup.Append>
            <Button
              variant="secondary"
              onClick={handleCleanSearch}
              style={{ zIndex: 0 }}
              className="search-options"
            >
              <GiBroom />
            </Button>
          </InputGroup.Append>
          {sortDirection && (
            <InputGroup.Append>
              <Button variant="dark" onClick={handleSortDirection}>
                {sortDirection === 'asc' ? (
                  <FaSortAmountDownAlt />
                ) : (
                  <FaSortAmountUp />
                )}
              </Button>
            </InputGroup.Append>
          )}
          {buttonUrl.length > 0 ? (
            <InputGroup.Append>
              <a className="btn btn-primary" href={buttonUrl}>
                <FaPlus />
              </a>
            </InputGroup.Append>
          ) : null}
        </InputGroup>
      </Col>
      {withOptions ? (
        <Col>
          <AsyncSelect
            loadOptions={loadOptions}
            defaultOptions
            cacheOptions
            placeholder={optionsPlaceholder}
            onInputChange={handleSelectSearch}
            onChange={handleChange}
            className=""
          />
        </Col>
      ) : null}
    </Row>
  );
};
