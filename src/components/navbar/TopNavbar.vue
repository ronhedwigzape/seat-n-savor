<template>
    <v-app-bar
        color="orange-darken-4"
        :image="image"
    >
        <template v-slot:image>
            <v-img
                gradient="to top right, rgba(226,139,86,.8), rgba(239,135,72,.8)"
            />
        </template>

        <template v-slot:prepend>
            <v-app-bar-nav-icon />
        </template>

        <v-app-bar-title class="pally">{{ store.app.brand }}</v-app-bar-title>

        <v-spacer />
        <template v-if="authStore.isAuthenticated">
            <!--	Sign out	-->
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
                                :class="$vuetify.display.mdAndDown ? 'ma-1' : 'ma-3'"
                                icon="mdi-dots-vertical"
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
                                Log Out
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </template>
                <v-card>
                    <v-card-title class="bg-deep-orange-darken-2">
                        <v-icon id="remind">mdi-alert-circle</v-icon>
                        Confirm Logout
                    </v-card-title>
                    <v-card-text>Are you sure you want to log out?</v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="green-darken-1"
                            variant="text"
                            @click="dialog = false"
                            :disabled="signingOut"
                        >
                            Go Back
                        </v-btn>
                        <v-btn
                            color="red-darken-1"
                            variant="text"
                            @click="signOut"
                            :loading="signingOut"
                        >
                            Log Out
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </template>
        <template v-else>
            <SignIn />
            <SignUp />
        </template>


    </v-app-bar>
</template>

<script setup>
import {ref} from "vue";
import SignUp from "@/components/dialogs/SignUpDialog.vue";
import {useStore} from "@/stores";
import SignIn from "@/components/dialogs/SignInDialog.vue";
import {useAuthStore} from "@/stores/store-auth";
import {useRouter} from "vue-router";

const dialog = ref(false);
const signingOut = ref(false);
const signedOut = ref(false);

const store = useStore();
const router = useRouter();
const authStore = useAuthStore();
const image = ref('dine.jpg');

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
            router.push('/')
            signingOut.value = false;
        },
        error: (error) => {
            alert(`ERROR ${error.status}: ${error.statusText}`);
            signingOut.value = false;
        },
    });
}

</script>

<style scoped>

</style>