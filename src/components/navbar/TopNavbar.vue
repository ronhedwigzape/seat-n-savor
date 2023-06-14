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
            <v-tooltip
                activator="parent"
                location="bottom"
            >{{ darkMode ? 'Dark Mode' : 'Light Mode' }}
            </v-tooltip>
            <span class="slider"></span>
        </label>
        <v-btn
            v-if="authStore.isAuthenticated"
            stacked
        >
            <v-chip
                class="me-3"
                pill
            >
            <v-avatar start>
                <v-img :src="authStore.getUser.avatar"></v-img>
            </v-avatar>
            {{ authStore.getUser.name }}
            </v-chip>
            <v-tooltip
                activator="parent"
                location="bottom"
            >Profile
            </v-tooltip>
        </v-btn>
        <template v-if="authStore.isAuthenticated">
            <v-btn
                v-if="authStore.getUser.userType === 'customer'"
                class="text-none"
                stacked
            >
                <v-badge
                    :color="notifications ? 'red-darken-3' : ''"
                    :content="notifications.length"
                >
                    <v-icon>mdi-bell-outline</v-icon>
                </v-badge>
                <v-tooltip
                    activator="parent"
                    location="bottom"
                >
                    Notifications
                </v-tooltip>
                <v-menu
                    activator="parent"
                    :close-on-content-click="false"
                >
                    <v-list width="300" height="300">
                        <v-list-item-title
                            class="text-h5 px-4 py-4 position-sticky pally"
                        >
                            Notifications
                        </v-list-item-title>
                        <v-list-item v-for="notification in notifications" class="bg-grey-darken-3 pt-3">
                            <div class="d-flex">
                                <div class="me-1">
                                    <v-avatar>
                                        <v-img :src="getRestaurateurAvatarById(notification.sender_id)"/>
                                    </v-avatar>
                                </div>
                                <div class="ms-3 text-start">
                                    <v-list-item-title
                                        class="m-0 text-body-1 font-weight-bold supreme"
                                        style="line-height: 1.1;"
                                    >
                                        {{ getRestaurateurNameById(notification.sender_id) }}
                                    </v-list-item-title>
                                    <p class="text-subtitle-2 text-grey-lighten-1 pt-1 supreme">
                                        {{notification.message }}
                                    </p>
                                    <p class="text-caption text-orange-accent-4 supreme">
                                        {{ notificationTimestamp(notification.updated_at) }}
                                    </p>
                                </div>
                            </div>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </v-btn>
            <SignOut/>
        </template>
        <template v-else>
            <SignIn/>
            <SignUp/>
        </template>
    </v-app-bar>
</template>

<script setup>
import {onMounted, reactive, ref} from "vue";
import SignUp from "@/components/dialogs/SignUpDialog.vue";
import SignIn from "@/components/dialogs/SignInDialog.vue";
import {useTheme} from "vuetify";
import SignOut from "@/components/dialogs/SignOutDialog.vue";
import {useAuthStore} from "@/stores/store-auth";
import {useStore} from "@/stores";
import $ from "jquery";

// data
const dialog = ref(false);
const theme = useTheme();
const darkMode = ref(true);
const image = ref('dine.jpg');
const status = ref('cancelled');
const items = ref('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad delectus dolore dolorem dolores doloribus ea explicabo hic illo impedit in, laborum officiis perspiciatis provident quasi quisquam quod recusandae rerum soluta.');
const restaurateurs = reactive([]);
const notifications = reactive([]);
const authStore = useAuthStore();
const store = useStore();

// methods
const toggleDarkMode = () => {
    theme.global.name.value = darkMode.value ? "dark" : "light";
    localStorage.setItem("darkTheme", darkMode.value.toString());
};

const getRestaurateurNameById = (senderId) => {
    const restaurateur = restaurateurs.find((r) => r.id === senderId);
    return restaurateur ? restaurateur.name : '';
};

const getRestaurateurAvatarById = (senderId) => {
    const restaurateur = restaurateurs.find((r) => r.id === senderId);
    return restaurateur ? restaurateur.avatar : '';
};

const notificationTimestamp = (updatedAt) => {
    const timestamp = new Date(updatedAt).getTime();
    const now = Date.now();
    const timeDifference = now - timestamp;

    if (timeDifference < 1000) {
        return 'Just now';
    }

    // calculate the time differences in seconds, minutes, hours, days, weeks, and months
    const seconds = Math.floor(timeDifference / 1000);
    const minutes = Math.floor(seconds / 60);
    const hours = Math.floor(minutes / 60);
    const days = Math.floor(hours / 24);
    const weeks = Math.floor(days / 7);
    const months = Math.floor(days / 30);

    if (seconds < 60) {
        return `${seconds} second${seconds !== 1 ? 's' : ''} ago`;
    } else if (minutes < 60) {
        return `${minutes} minute${minutes !== 1 ? 's' : ''} ago`;
    } else if (hours < 24) {
        return `${hours} hour${hours !== 1 ? 's' : ''} ago`;
    } else if (days < 7) {
        return `${days} day${days !== 1 ? 's' : ''} ago`;
    } else if (weeks < 4) {
        return `${weeks} week${weeks !== 1 ? 's' : ''} ago`;
    } else {
        return `${months} month${months !== 1 ? 's' : ''} ago`;
    }
};


const fetchCustomerNotifications = () => {
    if (authStore.isAuthenticated) {
        $.ajax({
            url: `${store.appURL}/${authStore.getUser.userType}.php`,
            type: 'GET',
            xhrFields: {
                withCredentials: true
            },
            data: {
                getCustomerNotifications: '',
            },
            success: (data, textStatus, jqXHR) => {
                data = JSON.parse(data);
                if (JSON.stringify(notifications) !== JSON.stringify(data.notifications))
                    Object.assign(notifications, data.notifications);
                Object.assign(restaurateurs, data.restaurateurs);
                setTimeout(() => {
                    fetchCustomerNotifications();
                }, 6000);
            },
            error: (error) => {
                alert(`ERROR ${error.status}: ${error.statusText}`);
            }
        });
    }
}

onMounted(() => {
    fetchCustomerNotifications();
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