<script setup>
import HeroImageSlide from "@/components/hero/Hero.vue";
import TopNavbar from "@/components/navbar/TopNavbar.vue";
import Footer from "@/components/footer/Footer.vue";
</script>

<template>
    <TopNavbar/>
    <v-main>
        <v-container>
            <HeroImageSlide/>
        </v-container>
        <Footer/>
    </v-main>
</template>

<style scoped>

</style>