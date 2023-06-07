<template>
    <div class="position-relative">
        <ul class="rslides">
            <li v-for="image in images">
                <img :src="`/img/${image.file}`" :alt="image.name">
            </li>
        </ul>
        <v-row class="heading heading-top">
            <v-col>
                <h2 class="text-h1 pally text-shades-white appName">{{ store.app.title }}</h2>
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
import {onMounted, reactive, ref} from "vue";
import {useStore} from "@/stores";
import {useAuthStore} from "@/stores/store-auth";

const store = useStore();
const loaded = ref(false);
const loading = ref(false);


const images = reactive([
    {
        name: 'Brick Wall Restaurant',
        file: 'restaurant1.jpg'
    },
    {
        name: 'Cocktails Restaurant',
        file: 'restaurant2.jpg'
    },
    {
        name: 'Coffee Shop',
        file: 'coffee-shop.jpg'
    },
    {
        name: 'Couple in a Restaurant',
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

onMounted(() => {
    $(function() {
        $(".rslides").responsiveSlides({
            auto: true,             // Boolean: Animate automatically, true or false
            speed: 700,            // Integer: Speed of the transition, in milliseconds
            timeout: 4000,          // Integer: Time between slide transitions, in milliseconds
            pager: false,           // Boolean: Show pager, true or false
            nav: false,             // Boolean: Show navigation, true or false
            random: true,          // Boolean: Randomize the order of the slides, true or false
            pause: false,           // Boolean: Pause on hover, true or false
            pauseControls: true,    // Boolean: Pause when hovering controls, true or false
            prevText: "Previous",   // String: Text for the "previous" button
            nextText: "Next",       // String: Text for the "next" button
            maxWidth: "",           // Integer: Max-width of the slideshow, in pixels
            navContainer: "",       // Selector: Where controls should be appended to, default is after the 'ul'
            manualControls: "",     // Selector: Declare custom pager navigation
            namespace: "rslides",   // String: Change the default namespace used
            before: function(){},   // Function: Before callback
            after: function(){}     // Function: After callback
        });
    });
});
</script>

<style scoped>
.rslides {
    position: relative;
    list-style: none;
    overflow: hidden;
    width: 100%;
    padding: 0;
    margin: 0 auto;
    background-size: cover;
}

.rslides li {
    -webkit-backface-visibility: hidden;
    position: absolute;
    display: none;
    width: 100%;
    left: 0;
    top: 0;
    margin: 0 auto;
}

.rslides li:first-child {
    position: relative;
    display: block;
    float: left;
}

.rslides img {
    display: block;
    height: auto;
    float: left;
    width: 100%;
    border: 0;
    margin: 0 auto;
}

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
/* Extra small devices (phones, 600px and down) */
@media only screen and (max-width: 600px) {
    .appName {
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
    .appName {
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
        top: 40%;
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
        top: 35%;
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
        top: 30%;
    }
    .searchBar-top {
        top: 180%;
    }
    .book {
        font-size: 1.5rem !important;
    }
}
</style>