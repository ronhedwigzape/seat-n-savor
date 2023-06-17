<template>
    <TopNavbar/>
    <v-main>
        <Hero/>
        <v-container>
            <v-divider class="mt-10"/>
            <v-row class="mt-10" justify="center" v-if="authStore.isAuthenticated">
                <v-btn
                    v-if="authStore.getUser.userType === 'customer'"
                    variant="elevated"
                    color="orange-accent-2"
                    height="50"
                    width="250"
                    @click="dialog"
                >
                    {{ bookings ? 'Make a booking' : 'Make your first booking' }}
                    <v-dialog
                        v-model="dialog"
                        fullscreen
                        :scrim="false"
                        activator="parent"
                        transition="dialog-bottom-transition"
                    >
                        <v-card class="pb-16">
                            <v-toolbar color="orange-accent-2">
                                <v-toolbar-title class="pally">
                                    {{ store.app.title }} Booking Form
                                </v-toolbar-title>
                                <v-spacer/>
                                <v-toolbar-items>
                                    <v-btn icon @click="dialog = false">
                                        <v-icon>mdi-close</v-icon>
                                    </v-btn>
                                </v-toolbar-items>
                            </v-toolbar>
                            <v-container>
                                <v-form>
                                    <v-row>
                                        <v-col cols="12">
                                            <v-list>
                                                <v-list-item class="text-h3 pt-4 pally">
                                                    Create your Booking
                                                </v-list-item>
                                                <v-list-item
                                                    prepend-icon="mdi-map-marker"
                                                    class="text-h5 pt-6 text-orange-lighten-4 supreme"
                                                    style="letter-spacing: 3px !important;"
                                                >
                                                    Setting
                                                </v-list-item>
                                            </v-list>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-list>
                                                <v-list-item
                                                    align="center"
                                                    class="text-h5 supreme mb-4"
                                                    style="letter-spacing: 3px !important;"
                                                >
                                                    Select your preferred restaurant
                                                </v-list-item>
                                            </v-list>
                                            <v-sheet
                                                class="mx-auto"
                                                elevation="8"
                                                max-width="1200"
                                            >
                                                <v-slide-group
                                                    v-model="restaurantModel"
                                                    class="pa-4"
                                                    show-arrows
                                                >
                                                    <v-slide-group-item
                                                        v-for="(restaurant, restaurantKey, restaurantIndex) in restaurants"
                                                        :key="restaurant.id"
                                                        v-slot="{ isSelected, toggle, selectedClass }"
                                                    >
                                                        <v-card
                                                            color="grey-lighten-1"
                                                            :class="['ma-4', selectedClass]"
                                                            height="300"
                                                            width="400"
                                                            @click="toggle"
                                                        >
                                                            <v-img
                                                                :src="restaurant.image"
                                                                :lazy-src="restaurant.image"
                                                                aspect-ratio="1"
                                                                cover
                                                                class="bg-grey-lighten-2"
                                                            >
                                                                <template v-slot:placeholder>
                                                                    <v-row
                                                                        class="fill-height ma-0"
                                                                        align="center"
                                                                        justify="center"
                                                                    >
                                                                        <v-progress-circular
                                                                            indeterminate
                                                                            color="grey-lighten-5"
                                                                        ></v-progress-circular>
                                                                    </v-row>
                                                                </template>
                                                            </v-img>
                                                        </v-card>
                                                    </v-slide-group-item>
                                                </v-slide-group>

                                                <v-expand-transition>
                                                    <transition name="fade">
                                                        <v-sheet
                                                            v-if="restaurantModel != null"
                                                        >
                                                            <div
                                                                class="d-flex flex-column align-center justify-center px-8 pb-10">
                                                                <h1>
                                                                    <v-icon class="text-green">mdi-check-circle</v-icon>
                                                                    {{ restaurants[restaurantModel].name }}
                                                                </h1>
                                                                <h4>{{ restaurants[restaurantModel].address }}</h4>
                                                                <h4>Landline/Phone:
                                                                    {{ restaurants[restaurantModel].phone }}</h4>
                                                            </div>
                                                        </v-sheet>
                                                    </transition>
                                                </v-expand-transition>
                                            </v-sheet>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-list>
                                                <v-list-item align="center" class="text-h5 supreme mb-4 "
                                                             style="letter-spacing: 3px !important;">
                                                    Select your preferred table
                                                </v-list-item>
                                            </v-list>
                                            <v-sheet
                                                class="mx-auto"
                                                elevation="8"
                                                max-width="1200"
                                            >
                                                <v-slide-group
                                                    v-model="tableModel"
                                                    class="pa-4"
                                                    show-arrows
                                                >
                                                    <v-slide-group-item
                                                        v-for="(table, tableKey, tableIndex) in tables"
                                                        :key="table.id"
                                                        v-slot="{ isSelected, toggle, selectedClass }"
                                                    >
                                                        <v-card
                                                            color="grey-lighten-1"
                                                            :image="table.image"
                                                            :class="['ma-4', selectedClass]"
                                                            height="300"
                                                            width="400"
                                                            @click="toggle"
                                                        >
                                                            <v-img
                                                                :src="table.image"
                                                                :lazy-src="table.image"
                                                                aspect-ratio="1"
                                                                cover
                                                                class="bg-grey-lighten-2"
                                                            >
                                                                <template v-slot:placeholder>
                                                                    <v-row
                                                                        class="fill-height ma-0"
                                                                        align="center"
                                                                        justify="center"
                                                                    >
                                                                        <v-progress-circular
                                                                            indeterminate
                                                                            color="grey-lighten-5"
                                                                        ></v-progress-circular>
                                                                    </v-row>
                                                                </template>
                                                            </v-img>
                                                        </v-card>
                                                    </v-slide-group-item>
                                                </v-slide-group>
                                                <v-expand-transition>
                                                    <transition name="fade">
                                                        <v-sheet
                                                            v-if="tableModel != null"
                                                        >
                                                            <div
                                                                class="d-flex flex-column align-center justify-center px-8 pb-10">
                                                                <h1>
                                                                    <v-icon class="text-green">mdi-check-circle</v-icon>
                                                                    Table #{{ tables[tableModel].number }}
                                                                </h1>
                                                                <h4>{{ tables[tableModel].description }}</h4>
                                                            </div>
                                                        </v-sheet>
                                                    </transition>
                                                </v-expand-transition>
                                            </v-sheet>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-list>
                                                <v-list-item prepend-icon="mdi-calendar-month"
                                                             class="text-h5 pt-6 supreme text-orange-lighten-4"
                                                             style="letter-spacing: 3px !important;">
                                                    Date and time
                                                </v-list-item>
                                                <v-list-item align="center" class="text-h5 mb-4 pt-10 supreme"
                                                             style="letter-spacing: 3px !important;">
                                                    <transition name="fade">
                                                        <v-icon v-if="selectedDateTime" class="text-green">
                                                            mdi-check-circle
                                                        </v-icon>
                                                    </transition>
                                                    Select your preferred date and time
                                                </v-list-item>
                                            </v-list>
                                            <VueDatePicker
                                                v-model.trim="selectedDateTime"
                                                placeholder="Click here to select your preferred date and time ..."
                                                text-input
                                                ignore-time-validation
                                                :text-input-options="dateOption"/>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-list>
                                                <v-list-item prepend-icon="mdi-account-group"
                                                             class="text-h5 pt-6 supreme text-orange-lighten-4"
                                                             style="letter-spacing: 3px !important;">
                                                    Party Size
                                                </v-list-item>
                                                <v-list-item align="center" class="text-h5 supreme mb-4"
                                                             style="letter-spacing: 3px !important;">
                                                    <transition name="fade">
                                                        <v-icon class="text-green" v-if="partySize">mdi-check-circle
                                                        </v-icon>
                                                    </transition>
                                                    How many are you in size?
                                                </v-list-item>
                                                <v-list-item align="center" class="text-h3" v-if="partySize">
                                                    {{ partySize }}
                                                </v-list-item>
                                            </v-list>
                                            <v-slider
                                                step="1"
                                                show-ticks="always"
                                                tick-size="4"
                                                :min="0"
                                                :max="9"
                                                v-model="partySize"
                                            ></v-slider>
                                        </v-col>
                                        <v-col>
                                            <v-btn
                                                variant="tonal"
                                                :disabled="!isBookingFormDone"
                                                block
                                                style="letter-spacing: 6px !important;"
                                            >
                                                Save your spot
                                                <v-dialog
                                                    v-model="dialog1"
                                                    activator="parent"
                                                    width="auto"
                                                >
                                                    <v-card>
                                                        <v-card-title class="px-5 pt-5 text-orange-lighten-4">
                                                            <v-icon class="pb-1 text-orange-accent-4">mdi-alert-circle
                                                            </v-icon>
                                                            Booking Confirmation
                                                        </v-card-title>
                                                        <v-divider/>
                                                        <v-card-text class="mx-auto px-5">
                                                            <p>Are you certain about your booking? Once the booking is
                                                                made, it cannot be undone.</p>
                                                        </v-card-text>
                                                        <v-card-actions class="px-5">
                                                            <v-spacer/>
                                                            <v-btn color="orange-accent-2" @click="dialog1 = false">
                                                                Close
                                                            </v-btn>
                                                            <v-btn color="orange-accent-4" @click="saveBooking">Confirm
                                                                booking
                                                            </v-btn>
                                                        </v-card-actions>
                                                    </v-card>
                                                </v-dialog>
                                            </v-btn>
                                        </v-col>
                                    </v-row>
                                </v-form>
                            </v-container>
                        </v-card>
                    </v-dialog>
                </v-btn>
                <v-snackbar
                    v-model="snackbar"
                    multi-line
                >
                    <v-icon class="text-green pb-1">mdi-check-circle</v-icon>
                    Your booking has been created successfully.
                    <template v-slot:actions>
                        <v-btn
                            color="red"
                            variant="text"
                            @click="dialog = false"
                        >
                            Close
                        </v-btn>
                    </template>
                </v-snackbar>
                <v-divider class="my-10"/>
                <v-col>
                    <div align="center" class="text-h3 pb-7 pally">
                        <v-icon>mdi-book-account</v-icon>
                        <p class="text-h3 pb-7 pally">My Current Bookings</p>
                    </div>
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
                                <v-icon>mdi-book-information-variant</v-icon>
                                <p>Booking Info</p>
                            </th>
                            <th class="text-center">
                                <v-icon>mdi-account-group</v-icon>
                                <p>Party Size</p>
                            </th>
                            <th class="text-center">
                                <v-icon>mdi-silverware</v-icon>
                                <p>Restaurant</p>
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
                                <v-icon>mdi-eye</v-icon>
                                <p>Customer Arrived</p>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr
                            v-for="(booking, bookingKey, bookingIndex) in bookings"
                            :key="booking.booking_id"
                            :class="{
                                'bg-grey-darken-3': booking.is_shown === 0,
                            }"
                        >
                            <td align="center"> {{ booking.date }}</td>
                            <td align="center"> {{ booking.time }}</td>
                            <td align="center">
                                <v-btn color="orange-accent-2">
                                    View
                                    <v-dialog
                                        v-model="view[booking.booking_id]"
                                        activator="parent"
                                        width="auto"
                                    >
                                        <v-card
                                            :width="$vuetify.display.mdAndUp && $vuetify.display.mdAndDown ? 300 :
                                            $vuetify.display.smAndUp && $vuetify.display.smAndDown ? 280 : 600"
                                        >
                                            <v-card-text class="d-flex justify-center flex-column">
                                                <VueQrcode
                                                    :id="`qr${booking.booking_id}`"
                                                    class="pt-4 mt-3 mb-5 mx-auto"
                                                    :value="booking.code"
                                                    :options="{ width: $vuetify.display.mdAndDown ? 230 : 280 }"
                                                />
                                                <p align="center">Code:</p>
                                                <p align="center"
                                                   class="text-subtitle-2 text-grey-lighten-1 font-weight-bold pb-3"
                                                >
                                                    {{ booking.code }}</p>
                                                <p align="center">Booking Reference Number:</p>
                                                <p align="center"
                                                   class="text-subtitle-2 text-grey-lighten-1 font-weight-bold pb-3"
                                                >
                                                    {{ booking.reference_number }}</p>
                                                <p class="text-caption text-grey-darken-1" align="center">
                                                    Kindly present this QR code exclusively to the restaurant manager and
                                                    ensure its confidentiality by refraining from sharing it with any other
                                                    individuals.
                                                </p>
                                            </v-card-text>
                                            <v-card-actions class="d-flex justify-center px-16 pb-6 pt-3">
                                                <v-btn color="orange-accent-2"
                                                       @click="view[booking.booking_id] = false">Close Dialog
                                                </v-btn>
                                                <v-spacer/>
                                                <v-btn color="orange-accent-4" @click="downloadQR(booking.booking_id)">
                                                    Download QR
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                </v-btn>
                            </td>
                            <td align="center"> {{ booking.party_size }}</td>
                            <td align="center"> {{ getRestaurantNameById(booking.restaurant_id) }}</td>
                            <td align="center"> #{{ getTableNumberById(booking.table_id) }}</td>
                            <td
                                align="center"
                                class="text-uppercase "
                                :class="{
                                    'text-orange-accent-4' : booking.status === 'pending',
                                    'text-red-accent-4' : booking.status === 'cancelled',
                                    'text-green-accent-4' : booking.status === 'confirmed'
                                }"
                            >
                                {{ booking.status }}
                            </td>
                            <td align="center">
                                <v-icon :color="booking.is_shown ? 'green' : 'red'">
                                    {{ booking.is_shown ? 'mdi-eye' : 'mdi-eye-off'}}
                                </v-icon>
                            </td>
                        </tr>
                        </tbody>
                    </v-table>
                </v-col>
            </v-row>
        </v-container>
        <Footer/>
    </v-main>
</template>

<script setup>
import {computed, onMounted, reactive, ref, watch} from "vue";
import {useAuthStore} from "@/stores/store-auth";
import {useStore} from "@/stores";
import TopNavbar from "@/components/navbar/TopNavbar.vue";
import Hero from "@/components/hero/Hero.vue";
import Footer from "@/components/footer/Footer.vue";
import VueDatePicker from '@vuepic/vue-datepicker';
import VueQrcode from "@chenfengyuan/vue-qrcode";
import '@vuepic/vue-datepicker/dist/main.css'
import $ from "jquery";
import dayjs from 'dayjs';

// stores
const authStore = useAuthStore();
const store = useStore();

// data
const dialog = ref(false);
const dialog1 = ref(false);
const snackbar = ref(false);
const loading = ref(false);
const tableModel = ref(null);
const selectedTable = ref(null);
const restaurantModel = ref(null);
const selectedRestaurant = ref(null);
const selectedDateTime = ref();
const partySize = ref(null);
const dateOption = ref({
    format: 'MM.dd.yyyy HH:mm'
})

const view = reactive({});
const restaurants = reactive([]);
const bookings = reactive([]);
const tables = reactive([]);
const booking = reactive({
    'restaurant_id': null,
    'table_id': null,
    'date': null,
    'time': null,
    'party_size': null,
});

// computed
const selectedDate = computed(() => {
    if (selectedDateTime.value) {
        return dayjs(selectedDateTime.value).format('YYYY-MM-DD');
    }
    return null;
});

const selectedTime = computed(() => {
    if (selectedDateTime.value) {
        return dayjs(selectedDateTime.value).format('HH:mm');
    }
    return null;
});

const isBookingFormDone = computed(() => {
    return (
        selectedRestaurant.value !== null &&
        selectedRestaurant.value !== undefined &&
        selectedTable.value !== null &&
        selectedTable.value !== undefined &&
        selectedDateTime.value !== null &&
        selectedDateTime.value !== undefined &&
        partySize.value !== null &&
        partySize.value !== 0 &&
        partySize.value <= selectedTable.value.capacity
    );
});

// watch
watch(tableModel, () => {
    if (tableModel.value !== null) {
        selectedTable.value = tables[tableModel.value];
    } else {
        selectedTable.value = null;
    }
});

watch(restaurantModel, () => {
    if (restaurantModel.value !== null) {
        selectedRestaurant.value = restaurants[restaurantModel.value];
    } else {
        selectedRestaurant.value = null;
    }
});

watch(selectedRestaurant, () => {
    updateBooking();
});

watch(selectedTable, () => {
    updateBooking();
});

watch(selectedDate, () => {
    updateBooking();
});

watch(selectedTime, () => {
    updateBooking();
});

watch(partySize, () => {
    updateBooking();
});

// methods
const updateBooking = () => {
    booking.restaurant_id = selectedRestaurant.value?.id;
    booking.table_id = selectedTable.value?.id;
    booking.date = selectedDate.value;
    booking.time = selectedTime.value;
    booking.party_size = partySize.value;
};

const getRestaurantNameById = (restaurantId) => {
    const restaurant = restaurants.find((r) => r.id === restaurantId);
    return restaurant ? restaurant.name : '';
};

const getTableNumberById = (tableId) => {
    const table = tables.find((t) => t.id === tableId);
    return table ? table.number : '';
};

const downloadQR = (bookingId) => {
    const canvas = document.getElementById(`qr${bookingId}`);
    const pngUrl = canvas
        .toDataURL("image/png")
        .replace("image/png", "image/octet-stream");
    let downloadLink = document.createElement("a");
    downloadLink.href = pngUrl;
    downloadLink.download = `QR_CODE_${authStore.getUser.name}_${new Date(Date.now()).toLocaleDateString()}.png`;
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
};

// ajax
const fetchRestaurants = () => {
    $.ajax({
        url: `${store.appURL}/${authStore.getUser.userType}.php`,
        type: 'GET',
        xhrFields: {
            withCredentials: true
        },
        data: {
            getRestaurants: '',
        },
        success: (data) => {
            data = JSON.parse(data);
            Object.assign(tables, data.tables);
            Object.assign(restaurants, data.restaurants);
        },
        error: (error) => {
            alert(`ERROR ${error.status}: ${error.statusText}`);
        }
    });
}

const saveBooking = () => {
    $.ajax({
        url: `${store.appURL}/${authStore.getUser.userType}.php`,
        type: 'POST',
        xhrFields: {
            withCredentials: true
        },
        data: {
            booking: booking
        },
        success: (data, textStatus, jqXHR) => {
            console.log(`${jqXHR.status}: ${jqXHR.statusText}`);
            if (jqXHR.status === 200) {
                setTimeout(() => {
                    snackbar.value = true;
                }, 4500);
                restaurantModel.value = null;
                tableModel.value = null;
                selectedRestaurant.value = null;
                selectedTable.value = null;
                selectedDateTime.value = null;
                partySize.value = 0;
                dialog.value = false;
                dialog1.value = false;
            }
        },
        error: (error) => {
            alert(`ERROR ${error.status}: ${error.statusText}`);
        }
    });
}

const fetchCustomerBookings = () => {
    if (authStore.isAuthenticated) {
        $.ajax({
            url: `${store.appURL}/${authStore.getUser.userType}.php`,
            type: 'GET',
            xhrFields: {
                withCredentials: true
            },
            data: {
                getBookings: '',
            },
            success: (data, textStatus, jqXHR) => {
                data = JSON.parse(data);
                if (JSON.stringify(bookings) !== JSON.stringify(data.bookings))
                    Object.assign(bookings, data.bookings);
                setTimeout(() => {
                    fetchCustomerBookings();
                }, 4300);
            },
            error: (error) => {
                alert(`ERROR ${error.status}: ${error.statusText}`);
            }
        });
    }
}

// mounted
onMounted(() => {
    fetchRestaurants();
    fetchCustomerBookings();
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

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>