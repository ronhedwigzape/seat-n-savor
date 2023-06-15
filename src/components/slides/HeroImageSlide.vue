<template>
    <div>
        <v-row class="heading heading-top">
            <v-col>
                <h2 class="text-h1 pally text-shades-white appName appName-header">{{ store.app.title }}</h2>
                <p align="center" class="appName-subheader">The fastest restaurant booking in Rinconada</p>
                <v-card
                    class="mx-auto searchBar searchBar-top "
                    color="grey-lighten-3"
                    max-height="300"
                    max-width="400"
                >
                    <v-card-text>
                        <v-text-field
                            :loading="loading"
                            density="comfortable"
                            variant="solo"
                            label="Search restaurants, tables.."
                            append-inner-icon="mdi-magnify"
                            single-line
                            hide-details
                            @click:append-inner="onClick"
                        ></v-text-field>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>
<script setup>
import {reactive, ref} from "vue";
import {useStore} from "@/stores";

const store = useStore();
const loaded = ref(false);
const loading = ref(false);


const images = reactive([
    {
        name: 'Brick Wall Restaurants',
        file: 'restaurant1.jpg'
    },
    {
        name: 'Cocktails Restaurants',
        file: 'restaurant2.jpg'
    },
    {
        name: 'Coffee Shop',
        file: 'coffee-shop.jpg'
    },
    {
        name: 'Couple in a Restaurants',
        file: 'couple.jpg'
    }
]);

const onClick = () => {
    loading.value = true;

    setTimeout(() => {
        loading.value = false;
        loaded.value = true;
    }, 2000);
};

</script>

<style scoped>


.appName {
    text-shadow: black 6px 6px 7px;
}

.appName-subheader {
    text-shadow: black 2px 2px 2px;
    color: white !important;
}


/* Extra small devices (phones, 600px and down) */
@media only screen and (max-width: 600px) {
    .appName-header {
        font-size: 4rem !important;
    }

}

/* Small devices (portrait tablets and large phones, between 600px and 768px) */
@media only screen and (min-width: 600px) and (max-width: 768px) {
    .appName-header {
        font-size: 5rem !important;
    }
}

/* Medium devices (landscape tablets, 768px and up) */
@media only screen and (min-width: 768px) {
    .book {
        font-size: 1.3rem !important;
    }
}


/* Large devices (laptops/desktops, 992px and up) */
@media only screen and (min-width: 992px) {

    .book {
        font-size: 1.3rem !important;
    }
}

/* Extra large devices (large laptops and desktops, 1200px and up) */
@media only screen and (min-width: 1200px) {
    .book {
        font-size: 1.5rem !important;
    }
}
</style>