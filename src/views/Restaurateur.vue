<template>
    <TopNavbar/>
    <SideNavbar/>
    <v-container v-if="authStore.isAuthenticated">
        <v-row class="d-flex justify-center align-center flex-column mt-16 mx-auto">
            <v-col class="me-16">
                <h1 align="center" class="text-h4 text-md-h3 mx-5 pb-7">
                    <v-icon>mdi-book-account</v-icon>
                    <p>{{ restaurant.name }} Bookings</p>
                </h1>
                <v-table
                    class="mx-5"
                >
                    <thead>
                    <tr>
                        <th class="text-center">
                            <v-icon>mdi-calendar-month</v-icon>
                            <p>Date</p>
                        </th>
                        <th class="text-center">
                            <v-icon>mdi-clock-time-nine</v-icon>
                            <p>Time</p>
                        </th>
                        <th class="text-center">
                            <v-icon>mdi-account-group</v-icon>
                            <p>Party Size</p>
                        </th>
                        <th class="text-center">
                            <v-icon>mdi-account</v-icon>
                            <p>Customer</p>
                        </th>
                        <th class="text-center">
                            <v-icon>mdi-table-chair</v-icon>
                            <p>Table</p>
                        </th>
                        <th class="text-center">
                            <v-icon>mdi-list-status</v-icon>
                            <p>Status</p>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(booking, bookingKey, bookingIndex) in bookings" :key="booking.booking_id">
                        <td align="center"> {{ booking.date }} </td>
                        <td align="center"> {{ booking.time }} </td>
                        <td align="center"> {{ booking.party_size }} </td>
                        <td align="center"> {{ getCustomerNameById(booking.customer_id) }} </td>
                        <td align="center"> #{{ getTableNumberById(booking.table_id) }} </td>
                        <td align="center"
                            class="text-uppercase"
                            :class="{
                                'text-orange-accent-3' : booking.status === 'pending',
                                'text-red-lighten-1' : booking.status === 'cancelled',
                                'text-green-accent-2' : booking.status === 'confirmed'
                            }"
                        >
                            {{ booking.status }}
                        </td>
                    </tr>
                    </tbody>
                </v-table>
            </v-col>
            <v-col class="d-flex justify-center align-center flex-column mx-16">
                <StreamBarcodeReader class="me-16" v-if="isShown" @decode="onDecode" @loaded="onLoaded"/>
                <v-btn class="mt-6 me-16" @click="isShown = !isShown">Toggle QR Scanner</v-btn>
            </v-col>
        </v-row>
    </v-container>
</template>
<script setup>

import TopNavbar from "@/components/navbar/TopNavbar.vue";
import SideNavbar from "@/components/navbar/SideNavbar.vue";
import { StreamBarcodeReader } from "vue-barcode-reader";
import {onMounted, reactive, ref} from "vue";
import $ from "jquery";
import {useStore} from "@/stores";
import {useAuthStore} from "@/stores/store-auth";

const isShown = ref(true);
const bookings = reactive([]);
const customers = reactive([]);
const tables = reactive([]);
const view = reactive({});
const restaurant = reactive({});

const store = useStore();
const authStore = useAuthStore();

const onDecode = (decodedText) => {
    console.log(decodedText)
}

const onLoaded = (loaded) => {
    console.log(loaded)
}

const getCustomerNameById = (customerId) => {
    const customer = customers.find((r) => r.id === customerId);
    return customer ? customer.name : '';
};

const getTableNumberById = (tableId) => {
    const table = tables.find((t) => t.id === tableId);
    return table ? table.number : '';
};

const fetchRestaurantBookings = () => {
    $.ajax({
        url: `${store.appURL}/restaurateur.php`,
        type: 'GET',
        xhrFields: {
            withCredentials: true
        },
        data: {
            getRestaurantBookings: '',
        },
        success: (data) => {
            data = JSON.parse(data);
            Object.assign(bookings, data.restaurant_bookings);
        },
        error: (error) => {
            alert(`ERROR ${error.status}: ${error.statusText}`);
        }
    });
}

const fetchCustomerTablesAndRestaurant = () => {
    $.ajax({
        url: `${store.appURL}/restaurateur.php`,
        type: 'GET',
        xhrFields: {
            withCredentials: true
        },
        data: {
            getCustomerTablesAndRestaurant: '',
        },
        success: (data) => {
            data = JSON.parse(data);
            Object.assign(customers, data.customers);
            Object.assign(tables, data.tables);
            Object.assign(restaurant, data.restaurant);
            console.log(data)
        },
        error: (error) => {
            alert(`ERROR ${error.status}: ${error.statusText}`);
        }
    });
}


onMounted(() => {
    fetchRestaurantBookings();
    fetchCustomerTablesAndRestaurant();
})

</script>
<style scoped>
td {
    height: 5rem !important;
}

th {
    padding-top: 1rem !important;
    padding-bottom: 1rem !important;
}

</style>