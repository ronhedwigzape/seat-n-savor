<template>
    <v-card :image="images[0].file" class="py-6">
        <v-row class="mx-auto">
            <v-col class="d-flex justify-center align-center flex-column mt-10 px-9">
                <h2 align="center"
                    class="pally text-h3 text-sm-h2 text-md-h2 text-lg-h1"
                    :class="{
                        'appName-dark' : theme.global.name.value === 'dark',
                        'appName-light' : theme.global.name.value === 'light'
                    }"
                >
                    {{ store.app.title }}
                </h2>
                <p
                    align="center"
                    class="font-weight-bold"
                    :class="{
                        'appName-subheader-dark' : theme.global.name.value === 'dark',
                        'appName-subheader-light' : theme.global.name.value === 'light'
                    }"
                >
                    The fastest restaurant booking in Rinconada
                </p>
                <v-card
                    class="w-100 rounded-pill my-10 my-sm-13 mx-lg-10"
                    variant="tonal"
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
    </v-card>
</template>
<script setup>
import {reactive, ref} from "vue";
import {useStore} from "@/stores";
import {useTheme} from "vuetify";

const store = useStore();
const loaded = ref(false);
const loading = ref(false);
const theme = useTheme();


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
.appName-dark {
    text-shadow: #000000 3px 3px 5px !important;
}

.appName-light {
    text-shadow: #ffffff 3px 3px 5px !important;
}

.appName-subheader-dark {
    text-shadow: #000000 1px 1px 2px !important;
}

.appName-subheader-light {
    text-shadow: #ffffff 1px 1px 2px !important;
}

</style>