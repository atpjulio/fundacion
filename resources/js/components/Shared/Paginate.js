import React from 'react';
import ReactPaginate from 'react-paginate';

export default (props) => {
  const { links, onPageChange = () => {} } = props;
  let paginationLinks = null;

  if (links !== undefined) {
    paginationLinks = (
      <div className="d-flex flex-row-reverse">
        <ReactPaginate
          pageCount={Math.ceil(links.total / links.perPage)}
          pageRangeDisplayed={3}
          marginPagesDisplayed={2}
          previousLabel="Anterior"
          nextLabel="Siguiente"
          onPageChange={({ selected }) => onPageChange(selected + 1)}
          forcePage={links.page - 1}
          // Bootstrap pagination classes
          containerClassName="pagination pagination-sm"
          pageClassName="page-item"
          pageLinkClassName="page-link"
          previousClassName="page-item"
          previousLinkClassName="page-link"
          nextClassName="page-item"
          nextLinkClassName="page-link"
          breakClassName="page-item"
          breakLinkClassName="page-link"
          activeClassName="active"
          disabledClassName="disabled"
        />
      </div>
    );
  }

  return paginationLinks;
};
