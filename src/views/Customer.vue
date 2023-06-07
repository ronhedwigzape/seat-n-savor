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
                >
                    Make a booking
                </v-btn>
            </template>
            <v-card>
                <v-toolbar color="primary">
                    <v-btn icon @click="dialog = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>
                        My Booking Form
                    </v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn variant="text" @click="dialog = false">
                            Save
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>
               <v-container>
                   <v-form>
                       <v-row>
                           <v-col>

                           </v-col>
                       </v-row>
                   </v-form>
               </v-container>
            </v-card>
        </v-dialog>
    </v-row>
    <Footer/>
</template>

<script setup>
import TopNavbar from "@/components/navbar/TopNavbar.vue";
import HeroImageSlide from "@/components/slides/HeroImageSlide.vue";
import {ref} from "vue";
import {useAuthStore} from "@/stores/store-auth";
import Footer from "@/components/footer/Footer.vue";

const authStore = useAuthStore();

const dialog = ref(false);

</script>


<style scoped>

</style>