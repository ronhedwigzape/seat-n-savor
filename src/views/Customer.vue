<template>
    <TopNavbar />
    <HeroImageSlide />
    <v-row class="mt-10" justify="center" v-if="authStore.isAuthenticated">
        <v-dialog
            v-model="dialog"
            fullscreen
            :scrim="false"
            transition="dialog-bottom-transition"
        >
            <template v-slot:activator="{ props }">
                <v-btn
                    v-if="authStore.getUser.userType === 'customer'"
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
                <v-toolbar
                    dark
                    color="primary"
                >
                    <v-btn
                        icon
                        dark
                        @click="dialog = false"
                    >
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Settings</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn
                            variant="text"
                            @click="dialog = false"
                        >
                            Save
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-list
                    lines="two"
                    subheader
                >
                    <v-list-subheader>User Controls</v-list-subheader>
                    <v-list-item title="Content filtering" subtitle="Set the content filtering level to restrict apps that can be downloaded"></v-list-item>
                    <v-list-item title="Password" subtitle="Require password for purchase or use password to restrict purchase"></v-list-item>
                </v-list>
                <v-divider></v-divider>
                <v-list
                    lines="two"
                    subheader
                >
                    <v-list-subheader>General</v-list-subheader>
                    <v-list-item title="Notifications" subtitle="Notify me about updates to apps or games that I downloaded">
                        <template v-slot:prepend>
                            <v-checkbox v-model="notifications"></v-checkbox>
                        </template>
                    </v-list-item>
                    <v-list-item title="Sound" subtitle="Auto-update apps at any time. Data charges may apply">
                        <template v-slot:prepend>
                            <v-checkbox v-model="sound"></v-checkbox>
                        </template>
                    </v-list-item>
                    <v-list-item title="Auto-add widgets" subtitle="Automatically add home screen widgets">
                        <template v-slot:prepend>
                            <v-checkbox v-model="widgets"></v-checkbox>
                        </template>
                    </v-list-item>
                </v-list>
            </v-card>
        </v-dialog>
    </v-row>
    <Footer />
</template>

<script setup>

import TopNavbar from "@/components/navbar/TopNavbar.vue";
import HeroImageSlide from "@/components/slides/HeroImageSlide.vue";
import {ref} from "vue";
import {useAuthStore} from "@/stores/store-auth";
import Footer from "@/components/footer/Footer.vue";

const authStore = useAuthStore();

const dialog = ref(false);
const notifications = ref(false);
const sound = ref(true);
const widgets = ref(false);

</script>


<style scoped>

</style>