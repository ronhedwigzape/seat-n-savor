<template>
    <TopNavbar/>
    <v-main>
        <v-container>
            <v-row class="mt-2" justify="center" v-if="authStore.isAuthenticated">
                <v-col>
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
                                    <v-card :width="$vuetify.display.mdAndUp && $vuetify.display.mdAndDown ? 400 : $vuetify.display.smAndUp && $vuetify.display.smAndDown ? 300 : 600">
                                        <v-card-title class="text-h5 ps-6 pt-8 pb-3">Instructions</v-card-title>
                                        <v-divider/>
                                        <v-card-text>
                                            <ol class="px-6">
                                                <li>Please notify the customer first whether the booking is canceled or confirmed.</li>
                                                <li>Ensure that if a customer arrives at your restaurant, their QR code is scanned.</li>
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
                                        <v-card :width="$vuetify.display.smAndDown ? 300 : $vuetify.display.mdAndDown ? 450 : 750">
                                            <v-card-title class="px-2 ps-sm-6 ps-md-6 ps-lg-6 pt-3 pt-sm-6 pt-md-8 pb-3">
                                                <v-btn v-if="$vuetify.display.mdAndUp" class="float-end rounded me-3 me-sm-4 me-md-4 me-lg-4" variant="text" @click="view[booking.booking_id] = false"><v-icon>mdi-close</v-icon></v-btn>
                                                <v-col class="text-subtitle-2 text-grey-darken-1 ">
                                                    <h6 class="text-h6 text-sm-h5 text-md-h5 text-lg-h5 text-white">
                                                        <v-icon size="x-small" class="pb-2">mdi-bell-badge</v-icon>
                                                        Notify {{ getCustomerNameById(booking.customer_id) }}
                                                    </h6>
                                                    <p class="text-subtitle-1 text-grey-lighten-1 pt-1">Code: {{ booking.code }}</p>
                                                    <p class="text-subtitle-1 text-grey-darken-1 pt-3 pb-3">Sample:</p>
                                                    <p class="pb-2">Hello {{ getCustomerNameById(booking.customer_id) }}!</p>
                                                    <p class="pt-2">Your booking with code</p>
                                                    <p v-if="$vuetify.display.smAndDown" class="pt-1">{{ booking.code }}</p>
                                                    <p v-if="$vuetify.display.smAndDown" class="pt-1">has been approved.</p>
                                                    <span v-else>{{ booking.code }} has been approved.</span>
                                                    <p class="pt-1">You have reserved a table for {{ booking.party_size }},</p>
                                                    <p v-if="$vuetify.display.smAndDown" class="pb-3">and table number is #{{ getTableNumberById(booking.table_id) }}.</p>
                                                    <span v-else class="pb-2">and table number is #{{ getTableNumberById(booking.table_id) }}.</span>
                                                    <p class="pb-2 pt-2">You are welcome to visit </p>
                                                    <p v-if="$vuetify.display.smAndDown" class="pb-1">{{ restaurant.name }} </p>
                                                    <p v-if="$vuetify.display.smAndDown" class="pb-3">on {{ booking.date }} at {{ booking.time }}</p>
                                                    <span v-else class="pb-2 pt-2">{{ restaurant.name }} on {{ booking.date }} at {{ booking.time }}</span>
                                                    <p class="pt-2">Thank you.</p>
                                                </v-col>
                                            </v-card-title>
                                            <v-card-text class="px-4 px-sm-6 px-md-6 px-lg-6">
                                                <v-text-field
                                                    variant="outlined"
                                                    label="Message"
                                                    v-model.trim="message"
                                                />
                                            </v-card-text>
                                            <v-card-actions class="px-4 px-lg-6 pb-4 d-flex flex-column flex-sm-column flex-md-row justify-center justify-md-space-between justify-lg-space-between">
                                                <v-btn
                                                    v-if="$vuetify.display.smAndDown"
                                                    class="mb-2"
                                                    color="orange-lighten-4"
                                                    variant="text"
                                                    @click="view[booking.booking_id] = false"
                                                >
                                                    Close
                                                </v-btn>
                                                <v-btn
                                                    color="orange-accent-4"
                                                    @click="markPending(booking.customer_id, booking.table_id, booking.code)"
                                                    class="my-2"
                                                    :block="$vuetify.display.smAndDown"
                                                >
                                                    Mark as Pending
                                                </v-btn>
                                                <v-btn
                                                    color="red-accent-4"
                                                    @click="sendCancellation(booking.customer_id, booking.table_id, booking.code)"
                                                    :disabled="!message || message.length === 0"
                                                    class="my-2"
                                                    :block="$vuetify.display.smAndDown"
                                                >
                                                    Send Cancellation
                                                </v-btn>
                                                <v-btn
                                                    color="green-accent-4"
                                                    @click="sendConfirmation(booking.customer_id, booking.table_id, booking.code)"
                                                    :disabled="!message || message.length === 0"
                                                    :block="$vuetify.display.smAndDown"
                                                    class="my-2"
                                                >
                                                    Send Confirmation
                                                </v-btn>
                                            </v-card-actions>
                                            <v-snackbar
                                                v-model="snackbar"
                                                multi-line
                                            >
                                                <v-icon class="text-green">mdi-check-circle</v-icon>
                                                Your notification message has been sent successfully.
                                                <template v-slot:actions>
                                                    <v-btn
                                                        color="red"
                                                        variant="text"
                                                        @click="dialog2 = false"
                                                    >
                                                        Close
                                                    </v-btn>
                                                </template>
                                            </v-snackbar>
                                            <v-snackbar
                                                v-model="pendingSnackbar"
                                                multi-line
                                            >
                                                <v-icon class="text-green">mdi-check-circle</v-icon>
                                                Booking status has been set to pending.
                                                <template v-slot:actions>
                                                    <v-btn
                                                        color="red"
                                                        variant="text"
                                                        @click="dialog2 = false"
                                                    >
                                                        Close
                                                    </v-btn>
                                                </template>
                                            </v-snackbar>
                                        </v-card>
                                    </v-dialog>
                                </v-btn>
                            </td>
                        </tr>
                        </tbody>
                    </v-table>
                </v-col>
                <v-col class="d-flex justify-center align-center flex-column mx-16">
                    <StreamBarcodeReader v-if="isShown" @decode="onDecode"/>
                    <v-btn class="mt-6" @click="isShown = !isShown">Toggle QR Scanner</v-btn>
                </v-col>
                <v-snackbar
                    v-model="qrSnackbar"
                    multi-line
                >
                    <v-icon class="text-green">mdi-check-circle</v-icon>
                    QR code scanned successfully.
                    <p>Decoded Value: {{ decodedQr }}</p>
                    <template v-slot:actions>
                        <v-btn
                            color="red"
                            variant="text"
                            @click="dialog3 = false"
                        >
                            Close
                        </v-btn>
                    </template>
                </v-snackbar>
            </v-row>
        </v-container>
        <Footer/>
    </v-main>
</template>
<script setup>

import TopNavbar from "@/components/navbar/TopNavbar.vue";
import SideNavbar from "@/components/navbar/SideNavbar.vue";
import { StreamBarcodeReader } from "vue-barcode-reader";
import {onMounted, reactive, ref, watch} from "vue";
import $ from "jquery";
import {useStore} from "@/stores";
import {useAuthStore} from "@/stores/store-auth";
import Footer from "@/components/footer/Footer.vue";

const isShown = ref(false);
const dialog = ref(false);
const dialog2 = ref(false);
const dialog3 = ref(false);
const snackbar = ref(false);
const qrSnackbar = ref(false);
const pendingSnackbar = ref(false);
const timer = ref(null);
const bookings = reactive([]);
const customers = reactive([]);
const tables = reactive([]);
const view = reactive({});
const restaurant = reactive({});
const message = ref('');
const decodedQr = ref('');

const store = useStore();
const authStore = useAuthStore();

const getCustomerNameById = (customerId) => {
    const customer = customers.find((r) => r.id === customerId);
    return customer ? customer.name : '';
};

const getTableNumberById = (tableId) => {
    const table = tables.find((t) => t.id === tableId);
    return table ? table.number : '';
};

const messageHasValue = () => {
    return message.value === null;
}

watch(message, messageHasValue);

const sendConfirmation = (customerId, tableId, code) => {
    $.ajax({
        url: `${store.appURL}/${authStore.getUser.userType}.php`,
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
            if (jqXHR.status === 200) {
                setTimeout(() => {
                    message.value = null
                    snackbar.value = true
                }, 1500);
                Object.assign(view, false);
            }
        },
        error: (error) => {
            alert(`ERROR ${error.status}: ${error.statusText}`);
        }
    });
}

const sendCancellation = (customerId, tableId, code) => {
    $.ajax({
        url: `${store.appURL}/${authStore.getUser.userType}.php`,
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
        success: (data, textStatus, jqXHR) => {
            if (jqXHR.status === 200) {
                setTimeout(() => {
                    snackbar.value = true;
                    message.value = null
                }, 1500);
                Object.assign(view, false);
            }
        },
        error: (error) => {
            alert(`ERROR ${error.status}: ${error.statusText}`);
        }
    });
}

const markPending = (customerId, tableId, code) => {
    $.ajax({
        url: `${store.appURL}/${authStore.getUser.userType}.php`,
        type: 'POST',
        xhrFields: {
            withCredentials: true
        },
        data: {
            pending: '',
            customer_id: customerId,
            table_id: tableId,
            code: code
        },
        success: (data, textStatus, jqXHR) => {
            if (jqXHR.status === 200) {
                setTimeout(() => {
                    pendingSnackbar.value = true;
                    message.value = null
                }, 1500);
                Object.assign(view, false);
            }
        },
        error: (error) => {
            alert(`ERROR ${error.status}: ${error.statusText}`);
        }
    });
}

const onDecode = (decodedText) => {
    decodedQr.value = decodedText;
    $.ajax({
        url: `${store.appURL}/${authStore.getUser.userType}.php`,
        type: 'POST',
        xhrFields: {
            withCredentials: true
        },
        data: {
            decodedQr: decodedText
        },
        success: (data, textStatus, jqXHR) => {
            if (jqXHR.status === 200) {
                qrSnackbar.value = true;
                decodedText = null;
                decodedQr.value = null;
            }
        },
        error: (error) => {
            alert(`ERROR ${error.status}: ${error.statusText}`);
        }
    });
}


const fetchRestaurantBookings = () => {
    if (authStore.isAuthenticated) {
        $.ajax({
            url: `${store.appURL}/${authStore.getUser.userType}.php`,
            type: 'GET',
            xhrFields: {
                withCredentials: true
            },
            data: {
                getRestaurantBookings: '',
            },
            success: (data, textStatus, jqXHR) => {
                data = JSON.parse(data);
                if (JSON.stringify(bookings) !== JSON.stringify(data.restaurant_bookings))
                    Object.assign(bookings, data.restaurant_bookings);
                setTimeout(() => {
                    fetchRestaurantBookings();
                }, 10000);
            },
            error: (error) => {
                alert(`ERROR ${error.status}: ${error.statusText}`);
            }
        });
    }
};

const fetchCustomerTablesAndRestaurant = () => {
    $.ajax({
        url: `${store.appURL}/${authStore.getUser.userType}.php`,
        type: 'GET',
        xhrFields: {
            withCredentials: true
        },
        data: {
            getCustomerTablesAndRestaurant: '',
        },
        success: (data, textStatus, jqXHR) => {
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