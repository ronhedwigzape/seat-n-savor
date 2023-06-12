<template>
    <div class="position-relative">
        <v-carousel
            cycle
            interval="6000"
            hide-delimiters
            class="carousel-min-height"
            height="600"
        >
            <v-carousel-item
                v-for="(image, index) in images"
                :key="index">
                <v-img
                    :src="image.file"
                    aspect-ratio="16/9"
                    cover
                />
            </v-carousel-item>
        </v-carousel>
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
.searchBar {
    position: absolute;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 5;
    width: 100%;
}

.heading {
    position: absolute;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 5;
}

.appName {
    text-shadow: black 6px 6px 7px;
}

.appName-subheader {
    text-shadow: black 2px 2px 2px;
    color: white !important;
}

.carousel-min-height {
    min-height: 300px;
}

/* Extra small devices (phones, 600px and down) */
@media only screen and (max-width: 600px) {
    .appName-header {
        font-size: 4rem !important;
    }
    .heading-top {
        top: 50%;
    }
    .searchBar-top {
        top: 130%;
    }
    .book {
        font-size: 1rem !important;
        top: 50%;
    }
}

/* Small devices (portrait tablets and large phones, between 600px and 768px) */
@media only screen and (min-width: 600px) and (max-width: 768px) {
    .appName-header {
        font-size: 5rem !important;
    }
    .heading-top {
        top: 45%;
    }
    .searchBar-top {
        top: 150%;
    }
    .book {
        font-size: 1.1rem !important;
    }
}

/* Medium devices (landscape tablets, 768px and up) */
@media only screen and (min-width: 768px) {
    .heading-top {
        top: 48%;
    }
    .searchBar-top {
        top: 160%;
    }
    .book {
        font-size: 1.3rem !important;
    }
}


/* Large devices (laptops/desktops, 992px and up) */
@media only screen and (min-width: 992px) {
    .heading-top {
        top: 45%;
    }
    .searchBar-top {
        top: 170%;
    }
    .book {
        font-size: 1.3rem !important;
    }
}

/* Extra large devices (large laptops and desktops, 1200px and up) */
@media only screen and (min-width: 1200px) {
    .heading-top {
        top: 40%;
    }
    .searchBar-top {
        top: 180%;
    }
    .book {
        font-size: 1.5rem !important;
    }
}
</style>