<template>
    <TopNavbar/>
    <SideNavbar/>
    <v-container v-if="authStore.isAuthenticated">
        <v-row class="d-flex justify-center align-center flex-column mt-16 mx-auto">
            <v-col class="me-16">
                <h1 align="center" class="text-h4 text-md-h3 mx-5 pb-7">
                    <v-icon>mdi-book-account</v-icon>
                    <p class="pally">
                        {{ restaurant.name }} Bookings
                            <v-btn size="small" class="rounded" >
                                <v-icon>mdi-help</v-icon>
                                <v-dialog
                                    v-model="dialog"
                                    activator="parent"
                                    width="auto"
                                >
                                    <v-card width="600">
                                        <v-card-title class="text-h4 ps-6 pt-8 pb-3">Instructions</v-card-title>
                                        <v-card-text>
                                        <ol class="px-6">
                                            <li>Notify customer first if booking is cancelled of confirmed .</li>
                                            <li>Make sure if customer arrived at your restaurant, scan their qr code.</li>
                                        </ol>
                                        </v-card-text>
                                        <v-card-actions class="px-6 pb-4">
                                            <v-spacer/>
                                            <v-btn color="orange-accent-2" @click="dialog = false">OK</v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-dialog>
                            </v-btn>
                    </p>
                </h1>
                <v-table
                    class="mx-5"
                >
                    <thead>
                    <tr>
                        <th class="text-center">
                            <v-icon>mdi-qrcode</v-icon>
                            <p>Code</p>
                        </th>
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
                        <th class="text-center">
                            <v-icon>mdi-bell-badge</v-icon>
                            <p>Notify</p>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(booking, bookingKey, bookingIndex) in bookings" :key="booking.booking_id">
                        <td align="center"> {{ booking.code }} </td>
                        <td align="center"> {{ booking.date }} </td>
                        <td align="center"> {{ booking.time }} </td>
                        <td align="center"> {{ booking.party_size }} </td>
                        <td align="center"> {{ getCustomerNameById(booking.customer_id) }} </td>
                        <td align="center"> #{{ getTableNumberById(booking.table_id) }} </td>
                        <td align="center"
                            class="text-uppercase"
                            :class="{
                                'text-orange-accent-4' : booking.status === 'pending',
                                'text-red-accent-4' : booking.status === 'cancelled',
                                'text-green-accent-4' : booking.status === 'confirmed'
                            }"
                        >
                            {{ booking.status }}
                        </td>
                        <td align="center">
                            <v-btn color="orange-accent-2">
                                View
                                <v-dialog
                                    v-model="view[booking.booking_id]"
                                    activator="parent"
                                    width="auto"
                                >
                                    <v-card width="600">
                                        <v-card-title class="text-h5 ps-6 pt-8 pb-3">
                                            <v-icon size="x-small" class="pb-2">mdi-bell-badge</v-icon> Notify {{ getCustomerNameById(booking.customer_id) }}
                                            <p class="text-subtitle-1 text-grey-lighten-1 pt-1">Code: {{ booking.code }}</p>

                                            <p class="text-subtitle-1 text-grey-darken-1 pt-3">Example:</p>
                                            <p class="text-subtitle-2 text-grey-darken-1">Hello {{ getCustomerNameById(booking.customer_id) }}! </p>
                                            <p class="text-subtitle-2 text-grey-darken-1">You're booking: {{ booking.code }} has been approved.</p>
                                            <p class="text-subtitle-2 text-grey-darken-1">You can now come at {{ restaurant.name }} on {{ booking.date }} @{{ booking.time }}.
                                            </p>
                                        </v-card-title>
                                        <v-card-text class="px-6">
                                           <v-text-field
                                               variant="outlined"
                                               label="Message"
                                               v-model="message"
                                           />
                                        </v-card-text>
                                        <v-card-actions class="px-6 pb-4">
                                            <v-btn color="orange-accent-2" @click="view[booking.booking_id] = false">Close</v-btn>
                                            <v-spacer/>
                                            <v-btn
                                                color="red-accent-4"
                                                @click="sendCancellation(booking.customer_id, booking.table_id, booking.code, booking.booking_id)"
                                            >
                                                Send Cancellation
                                            </v-btn>
                                            <v-btn
                                                color="green-accent-4"
                                                @click="sendConfirmation(booking.customer_id, booking.table_id, booking.code, booking.booking_id)"
                                            >
                                                Send Confirmation
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-dialog>
                            </v-btn>
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
const dialog = ref(false);
const bookings = reactive([]);
const customers = reactive([]);
const tables = reactive([]);
const view = reactive({});
const restaurant = reactive({});
const message = ref('');

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

const sendConfirmation = (customerId, tableId, code, bookingId) => {
    $.ajax({
        url: `${store.appURL}/restaurateur.php`,
        type: 'POST',
        xhrFields: {
            withCredentials: true
        },
        data: {
            confirmation: '',
            customer_id: customerId,
            table_id: tableId,
            code: code,
            message: message.value
        },
        success: (data, textStatus, jqXHR) => {
            data = JSON.parse(data);
            if (jqXHR.status === 200) {
                setTimeout(() => {
                    message.value = null
                }, 1500);
                view.value[bookingId] = false;
            }
        },
        error: (error) => {
            alert(`ERROR ${error.status}: ${error.statusText}`);
        }
    });
}

const sendCancellation = (customerId, tableId, code, bookingId) => {
    $.ajax({
        url: `${store.appURL}/restaurateur.php`,
        type: 'POST',
        xhrFields: {
            withCredentials: true
        },
        data: {
            cancellation: '',
            customer_id: customerId,
            table_id: tableId,
            code: code,
            message: message.value
        },
        success: (data) => {
            data = JSON.parse(data);
            if (jqXHR.status === 200) {
                setTimeout(() => {
                    message.value = null
                }, 1500);
                view.value[bookingId] = false;
            }
        },
        error: (error) => {
            alert(`ERROR ${error.status}: ${error.statusText}`);
        }
    });
}

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