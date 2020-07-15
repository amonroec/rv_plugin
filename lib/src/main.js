import Vue from 'vue'
import ReservationsPage from './reservations_page.vue'
import ReservationsTable from './reservations_table.vue'
import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css'
// import PayPal from 'vue-paypal-checkout-2'
// import swal from 'sweetalert2';
window.axios = require('axios');

// Vue.use(swal);

new Vue({
  el: '#vue-reservation-app',
  // data: {
  //   message: 'Hello Vueeeeee!'
  // }
  render: h => h(ReservationsPage)
})

new Vue({
	el: '#vue-reservation-table',
	render: h => h(ReservationsTable)
})
