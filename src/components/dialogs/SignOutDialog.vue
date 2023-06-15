<template>
    <v-dialog
        v-model="dialog"
        max-width="400"
    >
        <template v-slot:activator="{ props }">
            <v-menu>
                <template
                    v-slot:activator="{ props }"
                >
                    <v-btn
                        icon="mdi-dots-vertical"
                        flat
                        v-bind="props"/>
                </template>
                <v-list>
                    <v-list-item
                        v-bind="props"
                        class="text-red-darken-3 text-uppercase"
                        style="font-size: 1rem;"
                        variant="text"
                    >
                        <v-icon icon="mdi-logout"/>
                        Sign Out
                    </v-list-item>
                </v-list>
            </v-menu>
        </template>
        <v-card>
            <v-card-title class="bg-orange-accent-2">
                <v-icon id="remind">mdi-alert-circle</v-icon>
                Confirm Sign Out
            </v-card-title>
            <v-card-text>Are you sure you want to sign out?</v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="orange-accent-4"
                    variant="text"
                    @click="dialog = false"
                    :disabled="signingOut"
                >
                    Go Back
                </v-btn>
                <v-btn
                    color="orange-accent-1"
                    variant="text"
                    @click="signOut"
                    :loading="signingOut"
                >
                    Sign Out
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup>
import $ from "jquery";
import {ref} from "vue";
import {useRouter} from "vue-router";
import {useAuthStore} from "@/stores/store-auth";
import {useStore} from "@/stores";

const dialog = ref(false);
const signingOut = ref(false);
const signedOut = ref(false);

const store = useStore();
const authStore = useAuthStore();
const router = useRouter();

const signOut = async () => {
    signingOut.value = true;
    await $.ajax({
        url: `${store.appURL}/index.php`,
        type: 'POST',
        xhrFields: {
            withCredentials: true
        },
        data: {
            signOut: signedOut.value
        },
        success: (data) => {
            data = JSON.parse(data);
            authStore.setUser(data.user = null);
            router.push('/');
            signingOut.value = false;
        },
        error: (error) => {
            alert(`ERROR ${error.status}: ${error.statusText}`);
            signingOut.value = false;
        },
    });
};

</script>

<style scoped>

</style>