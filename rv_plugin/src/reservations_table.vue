<template>
    <div class="col-12 float-left">
        <div class="col-5 text-center float-left">
            Start Date<br />
            <input type="date" class="form-control" v-model="start_date" />
        </div>
        <div class="col-5 text-center float-left">
            End Date<br />
            <input type="date" class="form-control" v-model="end_date" />
        </div>
        <div class="col-2 float-left text-center">
            <br />
            <button class="btn btn-info btn-sm" @click="getReservations()">Submit</button>
        </div>
        <div class="col-12 float-left text-center">
            Click on row to view reservation.
        </div>
        <table class="table table-striped" v-if="data">
            <thead>
                <tr>
                    <th>Invoice ID</th>
                    <th>Email</th>
                    <th>Spot ID</th>
                    <th>Length of Stay</th>
                    <th>Arrive Date</th>
                    <th>Depart Date</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="value in data" @click="viewReservation = value">
                    <td>{{value.id}}</td>
                    <td>{{value.email}}</td>
                    <td>{{value.site_spot_id}}</td>
                    <td>{{value.length_of_stay_text}}</td>
                    <td>{{value.reservation_date_start}}</td>
                    <td>{{value.reservation_date_end}}</td>
                    <td>{{value.price}}</td>
                </tr>
            </tbody>
        </table>

        <div class="myModal" v-if="viewReservation">
            <div class="background" @click="viewReservation = null"></div>
            <div id="reservationHolder">
                <reservation-view-page :reservation="viewReservation"></reservation-view-page>
            </div>
        </div>
    </div>
</template>
<script>
import {apiUrl} from './config'
import ReservationViewPage from './reservation_view_page'
var methods = {}

methods.getReservations = function () {
    const postData = {
        startDate: this.start_date,
        endDate: this.end_date
    }
    axios.post(apiUrl + 'getReservationsTable', postData).then(response => {
        if (response.status === 200 || response.status === 201) {
            console.log(response)
            this.data = response.data
        }
    })
}

export default {
    // ===Component name
    name: "ReservationsTable",
    // ===Props passed to component
    props: {},
    // ===Components used by this component
    components: {
        ReservationViewPage
    },
    // ====component Data properties
    data(){
        return{
            data: null,
            viewReservation: null,
            start_date: '2020-07-01',
            end_date: '2020-07-15'
        }
    },
    // ===Code to be executed when Component is mounted
    mounted() {

    },
    // ===Computed properties for the component
    computed: {},
    // ===Component methods
    methods: methods
}
</script>
<!-- styles -->
<!-- adding scoped attribute will apply the css to this component only -->
<style scoped lang="scss">
    .table {
        font-size:.8em !important;
    }
    .myModal {
        position: fixed;
        top:0;left:0;right:0;bottom:0;
        width:100%;
        height:100%;
        z-index: 100;

        .background {
            position:absolute;
            width:100%;
            height:100%;
            background-color: rgba(0,0,0,.3);
        }


        #reservationHolder {
            padding:15px;
            position: absolute;
            width:85%;
            margin-left:7.5%;margin-right:7.5%;
            height:90%;
            margin-top: 5%;
            margin-bottom: 5%;
            background-color: #fff;
            overflow: scroll;
        }

    }
</style>
