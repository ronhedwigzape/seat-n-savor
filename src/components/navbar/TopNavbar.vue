<template>
    <v-app-bar
        class="position-fixed"
        :image="image"
    >
        <template v-slot:image>
            <v-img
                gradient="to top right, rgba(226,139,86,.8), rgba(239,135,72,.8)"
            />
        </template>

        <template v-if="authStore.isAuthenticated" v-slot:prepend>
            <v-app-bar-nav-icon/>
        </template>

        <v-app-bar-title class="pally">{{ store.app.brand }}</v-app-bar-title>

        <v-spacer/>

        <label class="switch me-3">
            <span class="sun">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <g fill="#ffd43b">
                        <circle r="5" cy="12" cx="12">
                        </circle>
                        <path
                            d="m21 13h-1a1 1 0 0 1 0-2h1a1 1 0 0 1 0 2zm-17 0h-1a1 1 0 0 1 0-2h1a1 1 0 0 1 0 2zm13.66-5.66a1 1 0 0 1 -.66-.29 1 1 0 0 1 0-1.41l.71-.71a1 1 0 1 1 1.41 1.41l-.71.71a1 1 0 0 1 -.75.29zm-12.02 12.02a1 1 0 0 1 -.71-.29 1 1 0 0 1 0-1.41l.71-.66a1 1 0 0 1 1.41 1.41l-.71.71a1 1 0 0 1 -.7.24zm6.36-14.36a1 1 0 0 1 -1-1v-1a1 1 0 0 1 2 0v1a1 1 0 0 1 -1 1zm0 17a1 1 0 0 1 -1-1v-1a1 1 0 0 1 2 0v1a1 1 0 0 1 -1 1zm-5.66-14.66a1 1 0 0 1 -.7-.29l-.71-.71a1 1 0 0 1 1.41-1.41l.71.71a1 1 0 0 1 0 1.41 1 1 0 0 1 -.71.29zm12.02 12.02a1 1 0 0 1 -.7-.29l-.66-.71a1 1 0 0 1 1.36-1.36l.71.71a1 1 0 0 1 0 1.41 1 1 0 0 1 -.71.24z">
                        </path>
                    </g>
                </svg>
            </span>
            <span class="moon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                    <path
                        d="m223.5 32c-123.5 0-223.5 100.3-223.5 224s100 224 223.5 224c60.6 0 115.5-24.2 155.8-63.4 5-4.9 6.3-12.5 3.1-18.7s-10.1-9.7-17-8.5c-9.8 1.7-19.8 2.6-30.1 2.6-96.9 0-175.5-78.8-175.5-176 0-65.8 36-123.1 89.3-153.3 6.1-3.5 9.2-10.5 7.7-17.3s-7.3-11.9-14.3-12.5c-6.3-.5-12.6-.8-19-.8z">
                    </path>
                </svg>
            </span>
            <input
                type="checkbox"
                class="input"
                v-model="darkMode"
                @change="toggleDarkMode"
            >
            <span class="slider"></span>
        </label>
        <v-chip
            v-if="authStore.isAuthenticated"
            pill
        >
            <v-avatar start>
                <v-img :src="`/img/avatars/${authStore.getUser.avatar}`"></v-img>
            </v-avatar>
            {{ authStore.getUser.name }}
        </v-chip>

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
                            Sign Out
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </template>
        <template v-else>
            <SignIn/>
            <SignUp/>
        </template>

    </v-app-bar>
</template>

<script setup>
import {onMounted, ref} from "vue";
import SignUp from "@/components/dialogs/SignUpDialog.vue";
import {useStore} from "@/stores";
import SignIn from "@/components/dialogs/SignInDialog.vue";
import {useAuthStore} from "@/stores/store-auth";
import {useRouter} from "vue-router";
import {useTheme} from "vuetify";
import $ from 'jquery';


// data
const dialog = ref(false);
const signingOut = ref(false);
const signedOut = ref(false);
const theme = useTheme();
const darkMode = ref(true);

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
};

const toggleDarkMode = () => {
    theme.global.name.value = darkMode.value ? "dark" : "light";
    localStorage.setItem("darkTheme", darkMode.value.toString());
};

onMounted(() => {
    const storedTheme = localStorage.getItem("darkTheme");
    if (storedTheme) {
        darkMode.value = storedTheme === "true";
    }
    theme.global.name.value = darkMode.value ? "dark" : "light";
});

</script>

<style scoped>
.switch {
    font-size: 17px;
    position: relative;
    display: inline-block;
    width: 64px;
    height: 34px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #73C0FC;
    transition: .4s;
    border-radius: 30px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 30px;
    width: 30px;
    border-radius: 20px;
    left: 2px;
    bottom: 2px;
    z-index: 2;
    background-color: #e8e8e8;
    transition: .4s;
}

.sun svg {
    position: absolute;
    top: 6px;
    left: 36px;
    z-index: 1;
    width: 24px;
    height: 24px;
}

.moon svg {
    fill: #73C0FC;
    position: absolute;
    top: 5px;
    left: 5px;
    z-index: 1;
    width: 24px;
    height: 24px;
}

/* .switch:hover */
.sun svg {
    animation: rotate 15s linear infinite;
}

@keyframes rotate {

    0% {
        transform: rotate(0);
    }

    100% {
        transform: rotate(360deg);
    }
}

/* .switch:hover */
.moon svg {
    animation: tilt 5s linear infinite;
}

@keyframes tilt {

    0% {
        transform: rotate(0deg);
    }

    25% {
        transform: rotate(-10deg);
    }

    75% {
        transform: rotate(10deg);
    }

    100% {
        transform: rotate(0deg);
    }
}

.input:checked + .slider {
    background-color: #183153;
}

.input:focus + .slider {
    box-shadow: 0 0 1px #183153;
}

.input:checked + .slider:before {
    transform: translateX(30px);
}
</style>