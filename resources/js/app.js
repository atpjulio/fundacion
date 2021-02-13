
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import moment from 'moment';

import Vue from 'vue';
// window.Vue = require('vue');
window.moment = moment;

// Filters
Vue.filter('moment', function (value) {
    if (!value)  {
        return '';
    }
    
    value = value.toString();

    return moment(value).format("DD/MM/YYYY");
});

Vue.filter('days', function(dateFrom, dateTo) {
    if (!dateFrom || !dateTo) {
        return '';
    }

    dateFrom = dateFrom.toString();
    dateTo = dateTo.toString();

    return moment(dateTo).diff(moment(dateFrom), 'days');
});


// Components
Vue.component('invoice-component', require('./components/InvoiceComponent.vue').default);
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('puc-component', require('./components/PucComponent.vue').default);

const app = new Vue({
    el: '#app'
});
