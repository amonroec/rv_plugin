<template>
  <div class="col-12 float-left" id="reservations_page">
    <form method="POST" @submit.prevent="submitForm()">
      <div id="reservationForm" class="float-left" style="width:100%;">
        <div class="col-sm-12 col-md-6 float-left formField">
          <label>First Name</label>
          <input type="text" disabled required class="form-control textEntry" name="first_name" v-model="form.first_name" />
        </div>
        <div class="col-sm-12 col-md-6 float-left formField">
          <label>Last Name</label>
          <input type="text" disabled required class="form-control textEntry" name="last_name" v-model="form.last_name" />
        </div>
        <div class="col-12 float-left formField">
          <label>Address</label>
          <input type="text" disabled required class="form-control textEntry" name="address" v-model="form.address" />
        </div>
        <div class="col-sm-12 col-md-4 float-left formField">
          <label>City</label>
          <input type="text" disabled required class="form-control textEntry" name="city" v-model="form.city" />
        </div>
        <div class="col-sm-12 col-md-4 float-left formField">
          <label>State</label>
          <!-- <input type="text" class="form-control textEntry" name="state" v-model="form.state" /> -->
          <select disabled class="form-control textEntry" v-model="form.state">
            <option value="">Select State</option>
            <option v-for="val in states" :value="val">{{val}}</option>
          </select>
        </div>
        <div class="col-sm-12 col-md-4 float-left formField">
          <label>Zip</label>
          <input type="text" disabled class="form-control textEntry" name="zip_code" v-model="form.zip_code" />
        </div>
        <div class="col-sm-12 col-md-6 float-left formField">
          <label>Phone#</label>
          <input type="text" disabled required class="form-control textEntry" name="phone" v-model="form.phone" />
        </div>
        <div class="col-sm-12 col-md-6 float-left formField">
          <label>Email</label>
          <input type="text" disabled required class="form-control textEntry" name="email" v-model="form.email" />
        </div>
        <hr style="width:90%;height:2px;background-color: #e0e0e0;margin-left: 5%;float:left;" />
        <div class="col-sm-12 float-left formField longLabel">
          <label>Date In:</label>
          <input type="text" disabled class="form-control textEntry" v-model="form.reservation_date_start" />
        </div>
        <div class="col-sm-12 float-left formField longLabel">
          <label>Date Out:</label>
          <input type="text" disabled class="form-control textEntry" v-model="form.reservation_date_end" />
        </div>
        <div class="col-12 float-left formField longLabel">
          <label class="longLabel">Length of Stay</label>
          <!-- <input type="date" required class="form-control textEntry" name="arrival_time" v-model="form.arrival_time" /> -->
          <select required disabled class="form-control textEntry" v-model="form.length_of_stay">
            <option v-for="val in stay_options" :value="val">{{val.text}} (${{val.price}})</option>
          </select>
        </div>
        <div class="col-12 float-left">
          <b>Site Types</b><br />
          - Standard (Includes full hook-ups and paved pull-through site.)<br />
          - Deluxe (Includes all standard amenities plus patio, picnic table, and hilltop site. Add $4 per day.)<br />
          - Premium (Includes all standard amenities plus a large patio, large picnic table, and gazebo. Add $19 per day.)<br /><br />
        </div>
        <div class="col-12 float-left formField longLabel">
          <label class="longLabel">Choose Site Type</label>
          <select required disabled class="form-control textEntry" v-model="form.site_type">
            <option v-for="val in site_type_options" :value="val">{{val.text}} (+${{val.price}})</option>
          </select>
        </div>
        <div class="col-12 float-left formField longLabel" v-if="sites_available">
          <label>Choose Site Spot</label>
          <select v-if="sites_available.length" required class="form-control textEntry" v-model="form.site_selected">
            <option value="">Select Site Spot</option>
            <option v-for="val in sites_available" :value="val">#{{val.site_spot_number}}</option>
          </select>
          <div v-else style="float:left;">
            There are not site spots available for the options you selected.
          </div>
        </div>
        <hr style="width:90%;height:2px;background-color: #e0e0e0;margin-left: 5%;float:left;" />
        <div class="col-12 float-left formField longLabel">
          <label class="longLabel">Passport America Member?</label>
          <button class="btn btn-sm" style="color:#000;" disabled :class="form.passport_america_member === 'No' ? 'btn-info' : 'btn-outline-info'" @click="form.passport_america_member = 'No'">No</button>
          <button class="btn btn-sm" style="color:#000;" disabled :class="form.passport_america_member === 'Yes' ? 'btn-info' : 'btn-outline-info'" @click="form.passport_america_member = 'Yes'">Yes</button>
        </div>
        <div class="col-12 float-left formField longLabel" v-if="form.passport_america_member === 'Yes'">
          <label class="longLabel">Membership ID</label>
          <input type="text" required class="form-control textEntry" disabled name="membership_id" v-model="form.membership_id" />
        </div>
        <div class="col-12 float-left formField longLabel">
          <label class="longLabel">Vehicle License #</label>
          <input type="text" required class="form-control textEntry" disabled name="vehicle_license" v-model="form.vehicle_license" />
        </div>
        <div class="col-12 float-left formField longLabel">
          <label class="longLabel">How did you hear about us?</label>
          <select required disabled class="form-control textEntry" v-model="form.hear_about_us">
            <option value="">Select Option</option>
            <option v-for="val in hear_options" :value="val">{{val}}</option>
          </select>
        </div>
        <hr style="width:90%;height:2px;background-color: #e0e0e0;margin-left: 5%;float:left;" />
        <div class="col-12 float-left text-center">
          Total Price: ${{calculateTotalPrice()}}
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import Swal from 'sweetalert2'
import {apiUrl} from './config'
var methods = {}

methods.submitForm = function (paypalId) {
  if (this.form.site_selected !== '' && this.form.site_selected) {
    var arrivalHour = this.arrival_hour
    if (this.ampm === 'PM') {
      if (this.arrival_hour === '12') {
        arrivalHour = '00'
      } else {
        arrivalHour = parseInt(arrivalHour) + 12
      }
    }
    arrivalHour = '0' + arrivalHour
    arrivalHour = arrivalHour.substr(-2)
    var totalPrice = this.calculateTotalPrice()
    const postData = {
      arrival_hour: arrivalHour,
      arrival_minutes: this.arrival_minutes,
      ampm: this.ampm,
      date_in: this.form.date_in,
      stay_options: this.form.length_of_stay,
      site_id: 1,
      site_type: this.form.site_type,
      form: this.form,
      total_price: totalPrice,
      paypalId: paypalId
    }
    axios.post('http://rv_server.test/api/submitReservation', postData).then(response => {
      if (response.status === 201) {
        console.log(response)
        Swal.fire('Success!', 'You have successfully reserved your spot. Please be sure to check your spam for your confirmation email.', 'success')
        this.clearForm()
      }
    })
  }
}

methods.configureForm = function () {
  this.form = {
    first_name: this.reservation.first_name,
    last_name: this.reservation.last_name,
    address: this.reservation.address,
    city: this.reservation.city,
    state: this.reservation.state,
    zip_code: this.reservation.zip_code,
    phone: this.reservation.phone,
    email: this.reservation.email,
    date_in: this.reservation.date_in,
    arrival_time: this.reservation.arrival_time,
    length_of_stay: {text: this.reservation.length_of_stay_text, price: this.reservation.length_of_stay_price, days: this.reservation.length_of_stay_days},
    passport_america_member: this.reservation.passport_america_member,
    membership_id: this.reservation.membership_id,
    site_type: {text: this.reservation.site_type_text, price: this.reservation.site_type_price},
    vehicle_license: this.reservation.vehicle_license,
    hear_about_us: this.reservation.hear_about_us,
    site_selected:'',
    reservation_date_start: this.reservation.reservation_date_start,
    reservation_date_end: this.reservation.reservation_date_end
  }
}

methods.clearForm = function () {
  this.form = {
        first_name: '',
        last_name: '',
        address: '',
        city: '',
        state: '',
        zip_code: '',
        phone: '',
        email: '',
        date_in: '',
        arrival_time: '',
        length_of_stay: {text: '1 Day', price: 39, days: 1},
        passport_america_member: 'No',
        membership_id: '',
        site_type: {text: 'Standard', price: 0},
        vehicle_license: '',
        hear_about_us: '',
        site_selected: ''
      }
  this.arrival_hour = ''
  this.arrival_minutes = '00'
  this.ampm = 'AM'
}

methods.configureShow = function () {
  var arr = ['first_name', 'last_name', 'address', 'city', 'state', 'zip_code', 'phone', 'email', 'date_in', 'site_selected', 'vehicle_license']
  var ready = true
  var that = this
  arr.forEach(function (val) {
    if (that.form[val] === '' || !that.form[val]) {
      ready = false
    }
  })
  console.log(ready)
  return ready
}

methods.paymentCancelled = function (e) {
  console.log('payment cancelled')
  console.log(e)
}

methods.paymentCompleted = function (e) {
  console.log(e)
  var id = e.transactions[0].related_resources[0].sale.id
  this.submitForm(id)
}

methods.calculateTotalPrice = function () {
  var calc = 0
  calc = this.form.length_of_stay.price + (this.form.site_type.price * this.form.length_of_stay.days)
  return calc.toFixed(2)
}

methods.checkReservations = function () {
  this.reservationLoading = true
  var arrivalHour = this.arrival_hour
  if (this.ampm === 'PM') {
    if (this.arrival_hour === '12') {
      arrivalHour = '00'
    } else {
      arrivalHour = parseInt(arrivalHour) + 12
    }
  }
  arrivalHour = '0' + arrivalHour
  arrivalHour = arrivalHour.substr(-2)
  const postData = {
    arrival_hour: arrivalHour,
    arrival_minutes: this.arrival_minutes,
    ampm: this.ampm,
    date_in: this.form.date_in,
    stay_options: this.form.length_of_stay,
    site_id: 1,
    site_type: this.form.site_type
  }
  console.log(postData)
  axios.post('http://rv_server.test/api/checkReservations', postData).then(response => {
    if (response.status) {
      console.log(response)
      this.sites_available = response.data.r
      this.reservationLoading = false
    }
  })
}

export default {
  name: 'reservations_view_page',
  components: {

  },
  watch: {
    'form.date_in': function () {
      if (this.arrival_hour !== '') {
        this.checkReservations()
        this.form.site_selected = ''
      }
    },
    arrival_hour: function () {
      if (this.form.date_in !== '') {
        this.checkReservations()
        this.form.site_selected = ''
      }
    },
    arrival_minutes: function () {
      if (this.form.date_in !== '' && this.arrival_hour !== '') {
        this.checkReservations()
        this.form.site_selected = ''
      }
    },
    ampm: function () {
      if (this.form.date_in !== '' && this.arrival_hour !== '') {
        this.checkReservations()
        this.form.site_selected = ''
      }
    },
    'form.length_of_stay': function () {
      if (this.arrival_hour !== '' && this.form.date_in !== '') {
        this.checkReservations()
        this.form.site_selected = ''
      }
    },
    'form.site_type': function () {
      if (this.arrival_hour !== '' && this.form.date_in !== '') {
        this.checkReservations()
        this.form.site_selected = ''
      }
    }
  },
  props: ['reservation'],
  data () {
    return {
      form: {
        first_name: '',
        last_name: '',
        address: '',
        city: '',
        state: '',
        zip_code: '',
        phone: '',
        email: '',
        date_in: '',
        arrival_time: '',
        length_of_stay: {text: '1 Day', price: 39, days: 1},
        passport_america_member: 'No',
        membership_id: '',
        site_type: {text: 'Standard', price: 0},
        vehicle_license: '',
        hear_about_us: '',
        site_selected: ''
      },
      reservationLoading: false,
      sites_available: null,
      hear_options: [
        'Billboard',
        'Google',
        'Facebook',
        'Passing By',
        'Word of Mouth',
        'Referral'
      ],
      arrival_hour: '',
      arrival_minutes: '00',
      ampm: 'AM',
      site_type_options: [
        {text: 'Standard', price: 0},
        {text: 'Deluxe', price: 4},
        {text: 'Premium', price: 19}
      ],
      states: [
        "Alabama",
        "Alaska",
        "Alberta",
        "Arizona",
        "Arkansas",
        "British Columbia",
        "California",
        "Colorado",
        "Connecticut",
        "Delaware",
        "Florida",
        "Georgia",
        "Hawaii",
        "Idaho",
        "Illinois",
        "Indiana",
        "Iowa",
        "Kansas",
        "Kentucky",
        "Louisiana",
        "Maine",
        "Manitoba",
        "Maryland",
        "Massachusetts",
        "Michigan",
        "Minnesota",
        "Mississippi",
        "Missouri",
        "Montana",
        "Nebraska",
        "Nevada",
        "New Brunswick",
        "New Hampshire",
        "New Jersey",
        "New Mexico",
        "New York",
        "Newfoundland and Labrador",
        "North Carolina",
        "North Dakota",
        "Northwest Territories",
        "Nova Scotia",
        "Nunavut",
        "Ohio",
        "Oklahoma",
        "Ontario",
        "Oregon",
        "Pennsylvania",
        "Prince Edward Island",
        "Quebec",
        "Rhode Island",
        "Saskatchewan",
        "South Carolina",
        "South Dakota",
        "Tennessee",
        "Texas",
        "Utah",
        "Vermont",
        "Virginia",
        "Washington",
        "West Virginia",
        "Wisconsin",
        "Wyoming",
        "Yukon"
      ],
      stay_options: [
        {text: "1 Day", price: 39, days: 1},
        {text: "2 Days", price: 70, days: 2},
        {text: "3 Days", price: 105, days: 3},
        {text: "4 Days", price: 140, days: 4},
        {text: "5 Days", price: 175, days: 5},
        {text: "6 Days", price: 210, days: 6},
        {text: "7 Days", price: 224, days: 7},
        {text: "8 Days", price: 256, days: 8},
        {text: "9 Days", price: 288, days: 9},
        {text: "10 Days", price: 320, days: 10},
        {text: "11 Days", price: 352, days: 11},
        {text: "12 Days", price: 384, days: 12},
        {text: "13 Days", price: 416, days: 13},
        {text: "14 Days", price: 448, days: 14},
        {text: "15 Days", price: 480, days: 15},
        {text: "16 Days", price: 512, days: 16},
        {text: "17 Days", price: 544, days: 17},
        {text: "18 Days", price: 576, days: 18},
        {text: "19 Days", price: 608, days: 19},
        {text: "20 Days", price: 640, days: 20},
        {text: "21 Days", price: 672, days: 21},
        {text: "22 Days", price: 704, days: 22},
        {text: "23 Days", price: 736, days: 23},
        {text: "24 Days", price: 768, days: 24},
        {text: "25 Days", price: 800, days: 25},
        {text: "26 Days", price: 832, days: 26},
        {text: "27 Days", price: 864, days: 27},
        {text: "28 Days", price: 896, days: 28},
        {text: "10 Hours", price: 20, days: 1},
        {text: "11 Hours", price: 22, days: 1},
        {text: "12 Hours", price: 24, days: 1},
        {text: "13 Hours", price: 26, days: 1},
        {text: "14 Hours", price: 28, days: 1},
        {text: "15 Hours", price: 30, days: 1},
        {text: "16 Hours", price: 32, days: 1},
        {text: "17 Hours", price: 34, days: 1},
        {text: "18 Hours", price: 36, days: 1},
        {text: "19 Hours", price: 38, days: 1},
      ]
    }
  },
  methods: methods,
  mounted: function () {
    // console.log('mounted')
    // axios.post('http://rv_server.test/api/getReservations').then(response => {
    //   console.log(response)
    // })
    this.configureForm()
  }
}
</script>

<style lang="scss" scoped>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}

.formField {

  &.longLabel {
    label {
      width: 250px !important;
    }
    .textEntry {
      width: calc(100% - 250px);
    }
  }

  label {
    float: left;
    width:120px;
    line-height:40px;
    text-align:left;
    padding-right: 10px;
  }
  .textEntry {
    width: calc(100% - 120px);
  }
  .timeField {
    width: 100px;
    float:left;
  }
}

h1, h2 {
  font-weight: normal;
}

ul {
  list-style-type: none;
  padding: 0;
}

li {
  display: inline-block;
  margin: 0 10px;
}

a {
  color: #42b983;
}
</style>
