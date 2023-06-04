<template>
    <div class="position-relative">
        <ul class="rslides">
            <li v-for="image in images">
                <img :src="`/img/${image.file}`" :alt="image.name">
            </li>
        </ul>
        <v-row class="heading">
            <v-col>
                <h2 class="text-h1 pally text-shades-white">{{ store.app.title }}</h2>
            </v-col>
        </v-row>
        <v-card
            class="mx-auto searchBar"
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
    </div>
</template>
<script setup>
import {onMounted, reactive, ref} from "vue";
import {useStore} from "@/stores";

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
    margin: 0;
    background-size: cover;
}

.rslides li {
    -webkit-backface-visibility: hidden;
    position: absolute;
    display: none;
    width: 100%;
    left: 0;
    top: 0;
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
}

.searchBar {
    position: absolute;
    top: 40%;
    left: 25%;
    right: 25%;
    z-index: 5;
    width: 100%;
}

.heading {
    position: absolute;
    top: 15%;
    left: 37%;
    z-index: 5;
    text-shadow: black 4px 4px 6px;
}
</style>