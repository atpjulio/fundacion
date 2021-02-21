(function() {
  const stateId = document.querySelector('#state_id');
  
  if (stateId) {
    stateId.addEventListener('change', (event) => {
      getCitiesForSelect(event.target.value, stateId.getAttribute('data-city-id'));
    })

    if (stateId.getAttribute('data-state-id') > 0) {
      getCitiesForSelect(stateId.getAttribute('data-state-id'), stateId.getAttribute('data-city-id'));
    }
  }
})();