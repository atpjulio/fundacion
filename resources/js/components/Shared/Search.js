import React from 'react';
import { Button, Col, FormControl, InputGroup, Row } from 'react-bootstrap';
import { GiBroom } from 'react-icons/gi';
import {
  FaAngleLeft,
  FaPlus,
  FaSortAmountDownAlt,
  FaSortAmountUp,
} from 'react-icons/fa';
import SelectSearch from 'react-select-search';

export default (props) => {
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
    buttonReturnUrl = '',
  } = props;

  const handleSearchChange = (evt) => setSearch(evt.target.value);

  const handleCleanSearch = () => setSearch('');

  const handleSortDirection = () => {
    if (sortDirection === 'asc') setSortDirection('desc');
    else setSortDirection('asc');
  };

  return (
    <Row>
      <Col md={options?.length > 1 ? 9 : 12}>
        <InputGroup className="mb-3">
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
  );
};
