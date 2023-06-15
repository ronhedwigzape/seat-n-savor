<template>
    <v-row class="mx-auto">
        <v-col class="d-flex justify-center align-center flex-column mt-10">
                <h2 align="center" class="pally text-h3 text-sm-h2 text-md-h2 text-lg-h1">{{ store.app.title }}</h2>
                <p align="center" class="text-grey-lighten-1">The fastest restaurant booking in Rinconada</p>
            <v-card
                class="w-75 rounded-pill my-10 my-sm-13 mx-lg-10 "
                :class="{
                    'w-100': $vuetify.display.mdAndUp,
                }"
                color="grey-lighten-3"
            >
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

            </v-card>
        </v-col>
    </v-row>
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


}

/* Small devices (portrait tablets and large phones, between 600px and 768px) */
@media only screen and (min-width: 600px) and (max-width: 768px) {

}

/* Medium devices (landscape tablets, 768px and up) */
@media only screen and (min-width: 768px) {

}


/* Large devices (laptops/desktops, 992px and up) */
@media only screen and (min-width: 992px) {

}

/* Extra large devices (large laptops and desktops, 1200px and up) */
@media only screen and (min-width: 1200px) {

}
</style>