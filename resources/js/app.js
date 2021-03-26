/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

require('./bootstrap');
require('react-icons');
require('react-bootstrap');
require('react-select');

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

require('./components/Example');

// Authorization
require('./components/Authorizations/Table');
require('./components/Authorizations/CreateEditForm');

// Companions
require('./components/Participants/Companions/Table');

// Eps
require('./components/Eps/Table');
// Eps services
require('./components/Eps/Services/Table');

// Invoice series
require('./components/Invoices/Series/Table');

// Merchants
require('./components/Merchants/Table');

// Patients
require('./components/Participants/Patients/Table');
