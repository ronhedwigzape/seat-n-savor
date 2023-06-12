<template>
    <TopNavbar/>
    <HeroImageSlide/>

    <v-row class="mt-10" justify="center" v-if="authStore.isAuthenticated">
        <v-dialog
            v-model="dialog"
            fullscreen
            :scrim="false"
            transition="dialog-bottom-transition"
            v-if="authStore.getUser.userType === 'customer'"
        >
            <template v-slot:activator="{ props }">
                <v-btn
                    variant="elevated"
                    color="orange-accent-2"
                    height="50"
                    width="250"
                    v-bind="props"
                    :loading="loading"
                    @click="handleOpenBook"
                >
                    Make a booking
                    <template v-slot:loader>
                        <v-progress-circular indeterminate></v-progress-circular>
                    </template>
                </v-btn>
            </template>
            <v-card class="pb-16">
                <v-toolbar color="orange-accent-2">
                    <v-btn icon @click="handleCloseBook">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title class="pally">
                        {{ store.app.title }} Booking Form
                    </v-toolbar-title>
                    <v-spacer/>
                    <v-toolbar-items>
                        <v-btn variant="text">
                            Save
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-container>
                    <v-form>
                        <v-row>
                            <v-col cols="12">
                                <v-list>
                                    <v-list-item class="text-h3 pt-4">Bookings</v-list-item>
                                    <v-list-item prepend-icon="mdi-map-marker" class="text-h5 pt-6">Setting
                                    </v-list-item>
                                </v-list>
                            </v-col>
                            <v-col cols="12">
                                <v-list>
                                    <v-list-item align="center" class="text-h5 text-decoration-underline mb-4">Select
                                        your preferred restaurant
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
                                                <div class="d-flex flex-column align-center justify-center px-8 pb-10">
                                                    <h1><v-icon class="text-green">mdi-check-circle</v-icon> {{ restaurants[restaurantModel].name }}</h1>
                                                    <h4>{{ restaurants[restaurantModel].address }}</h4>
                                                    <h4>Landline/Phone: {{ restaurants[restaurantModel].phone }}</h4>
                                                </div>
                                            </v-sheet>
                                        </transition>
                                    </v-expand-transition>
                                </v-sheet>
                            </v-col>
                            <v-col cols="12">
                                <v-list>
                                    <v-list-item align="center" class="text-h5 text-decoration-underline mb-4">Select
                                        your preferred table
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
                                                <div class="d-flex flex-column align-center justify-center px-8 pb-10">
                                                    <h1><v-icon class="text-green">mdi-check-circle</v-icon> Table #{{ tables[tableModel].number }}</h1>
                                                    <h4>{{ tables[tableModel].description }}</h4>
                                                </div>
                                            </v-sheet>
                                        </transition>
                                    </v-expand-transition>
                                </v-sheet>
                            </v-col>
                            <v-col cols="12">
                                <v-list>
                                    <v-list-item prepend-icon="mdi-calendar-month" class="text-h5 pt-6">
                                        Date and time
                                    </v-list-item>
                                    <v-list-item align="center" class="text-h5 text-decoration-underline mb-4">
                                        <transition name="fade">
                                            <v-icon v-if="selectedDateTime" class="text-green">mdi-check-circle</v-icon>
                                        </transition>
                                        Select your preferred date and time
                                    </v-list-item>
                                </v-list>
                                <VueDatePicker
                                    v-model="selectedDateTime"
                                    placeholder="Click here to select your preferred date and time ..."
                                    text-input
                                    ignore-time-validation
                                    :text-input-options="dateOption"/>
                            </v-col>
                            <v-col cols="12">
                                <v-list>
                                    <v-list-item prepend-icon="mdi-account-group" class="text-h5 pt-6">
                                        Party Size
                                    </v-list-item>
                                    <v-list-item align="center" class="text-h5 text-decoration-underline mb-4">
                                        <transition name="fade">
                                            <v-icon class="text-green" v-if="partySize">mdi-check-circle</v-icon>
                                        </transition>
                                        How many are you in size?
                                    </v-list-item>
                                    <v-list-item align="center" class="text-h3" v-if="partySize">{{ partySize }}</v-list-item>
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
                                    @click="saveBooking"
                                    :disabled="!isBookingFormDone"
                                    block
                                >
                                    Save your spot
                                </v-btn>

                            </v-col>
                        </v-row>
                    </v-form>
                </v-container>
            </v-card>
        </v-dialog>
        <v-snackbar
            v-model="snackbar"
            multi-line
        >
            <v-icon class="text-green">mdi-check-circle</v-icon>
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
    </v-row>
    <Footer/>
</template>

<script setup>
import {computed, onMounted, reactive, ref, watch} from "vue";
import {useAuthStore} from "@/stores/store-auth";
import {useStore} from "@/stores";
import TopNavbar from "@/components/navbar/TopNavbar.vue";
import HeroImageSlide from "@/components/slides/HeroImageSlide.vue";
import Footer from "@/components/footer/Footer.vue";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import $ from "jquery";
import dayjs from 'dayjs';

// stores
const authStore = useAuthStore();
const store = useStore();

// data
const dialog = ref(false);
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

const restaurants = reactive([]);
const tables = reactive([]);
const booking = ref({
    'restaurant_id': null,
    'table_id': null,
    'date': null,
    'time': null,
    'party_size': null,
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

watch(partySize, () => {
    updateBooking();
});


// methods
const updateBooking = () => {
    booking.value.restaurant_id = selectedRestaurant.value?.id;
    booking.value.table_id = selectedTable.value?.id;
    booking.value.date = selectedDate.value;
    booking.value.time = selectedTime.value;
    booking.value.party_size = partySize.value;
};

const handleOpenBook = () => {
    loading.value = true
    setTimeout(() => (
        loading.value = false,
            dialog.value = true
    ), 100);
}

const handleCloseBook = () => {
    loading.value = false
    setTimeout(() => (
        dialog.value = false
    ), 100);
}

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
        partySize.value !== null &&
        partySize.value !== 0 &&
            partySize.value <= selectedTable.value.capacity
    );
});

// ajax
const fetchTables = () => {
    $.ajax({
        url: `${store.appURL}/customer.php`,
        type: 'GET',
        xhrFields: {
            withCredentials: true
        },
        data: {
            getTables: '',
        },
        success: (data) => {
            data = JSON.parse(data);
            Object.assign(tables, data.tables);
        },
        error: (error) => {
            alert(`ERROR ${error.status}: ${error.statusText}`);
        }
    });
}

const fetchRestaurants = () => {
    $.ajax({
        url: `${store.appURL}/customer.php`,
        type: 'GET',
        xhrFields: {
            withCredentials: true
        },
        data: {
            getRestaurants: '',
        },
        success: (data) => {
            data = JSON.parse(data);
            Object.assign(restaurants, data.restaurants);
        },
        error: (error) => {
            alert(`ERROR ${error.status}: ${error.statusText}`);
        }
    });
}

const saveBooking = () => {
    $.ajax({
        url: `${store.appURL}/customer.php`,
        type: 'POST',
        xhrFields: {
            withCredentials: true
        },
        data: {
            booking: booking.value
        },
        success: (data, textStatus, jqXHR) => {
            console.log(`${jqXHR.status}: ${jqXHR.statusText}`);
            if (jqXHR.status === 200) {
                setTimeout(() => {
                    snackbar.value = true;
                    restaurantModel.value = null;
                    tableModel.value = null;
                    selectedRestaurant.value = null;
                    selectedTable.value = null;
                    selectedDateTime.value = null;
                    partySize.value = 0;
                }, 1500);
                dialog.value = false;
            }
        },
        error: (error) => {
            alert(`ERROR ${error.status}: ${error.statusText}`);
        }
    });
}


// mounted
onMounted(() => {
    fetchTables();
    fetchRestaurants();
})
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>