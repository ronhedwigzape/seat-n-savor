<template>
    <v-dialog
        transition="dialog-bottom-transition"
        v-model="dialog"
        persistent
        width="1024"
    >
        <template v-slot:activator="{ props }">
            <v-btn
                color="text-white"
                v-bind="props"
                stacked
            >
                Sign Up
            </v-btn>
        </template>
        <v-row class="d-flex justify-center align-center">
            <v-card width="1500">
                <v-card-title>
                    <v-btn
                        variant="text"
                        class="mt-2 float-end rounded"
                        @click="dialog = false"
                    >
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-col class="d-flex justify-center align-center">
                        <p class="text-lg-h2 text-md-h3 text-h4 pally">Create Your Account</p>
                    </v-col>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-form @submit.prevent="createAccount">
                            <v-row>
                                <v-col cols="12" sm="6" md="6">
                                    <v-text-field
                                        v-model="name"
                                        label="Fullname*"
                                        variant="outlined"
                                        hint="This will be your account name."
                                        prepend-inner-icon="mdi-account"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="6">
                                    <v-text-field
                                        v-model="username"
                                        label="Username*"
                                        variant="outlined"
                                        hint="This will be used for your login credentials."
                                        prepend-inner-icon="mdi-account-circle"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="6">
                                    <v-text-field
                                        v-model="email"
                                        label="Email*"
                                        variant="outlined"
                                        prepend-inner-icon="mdi-email"
                                        :error-messages="emailError"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="6">
                                    <v-text-field
                                        v-model="phone"
                                        label="Phone Number*"
                                        type="number"
                                        variant="outlined"
                                        prepend-inner-icon="mdi-phone"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        v-model="address"
                                        label="Address*"
                                        variant="outlined"
                                        prepend-inner-icon="mdi-map-marker"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        v-model="password"
                                        label="Password*"
                                        variant="outlined"
                                        hint="This will be used for your login credentials."
                                        prepend-inner-icon="mdi-lock"
                                        :append-inner-icon="isPasswordShown1 ? 'mdi-eye' : 'mdi-eye-off'"
                                        :type="isPasswordShown1 ? 'text' : 'password'"
                                        @click:append-inner="isPasswordShown1 = !isPasswordShown1"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        v-model="confirmPassword"
                                        label="Confirm Password*"
                                        variant="outlined"
                                        prepend-inner-icon="mdi-lock"
                                        :append-inner-icon="isPasswordShown2 ? 'mdi-eye' : 'mdi-eye-off'"
                                        :type="isPasswordShown2 ? 'text' : 'password'"
                                        @click:append-inner="isPasswordShown2 = !isPasswordShown2"
                                    ></v-text-field>
                                </v-col>
                                <transition name="fade">
                                    <v-col cols="12" v-if="passwordMismatch" class="bg-red-accent-1">Passwords do not
                                        match
                                    </v-col>
                                </transition>
                            </v-row>
                            <v-btn
                                color="blue-darken-1"
                                variant="text"
                                type="submit"
                                block
                                :disabled="!isFormValid"
                            >
                                Create Account
                            </v-btn>

                        </v-form>
                    </v-container>
                </v-card-text>
                <v-card-text>
                    <v-col class="d-flex justify-center align-center" cols="12">
                        <p>Already have an account? Sign in Now!</p>
                    </v-col>
                </v-card-text>
            </v-card>
        </v-row>
    </v-dialog>
    <v-snackbar
        v-model="snackbar"
        multi-line
    >
        <v-icon class="text-green">mdi-check-circle</v-icon>
        Your account has been created successfully.
        <template v-slot:actions>
            <v-btn
                color="red"
                variant="text"
                @click="dialog1 = false"
            >
                Close
            </v-btn>
        </template>
    </v-snackbar>
</template>

<script setup>
import {computed, ref, watch} from 'vue';
import $ from 'jquery';
import {useStore} from '@/stores';

// store
const store = useStore();

// data
const dialog = ref(false);
const dialog1 = ref(false);
const loading = ref(false);
const isPasswordShown1 = ref(false);
const isPasswordShown2 = ref(false);
const snackbar = ref(false);
const name = ref('');
const username = ref('');
const password = ref('');
const email = ref('');
const emailError = ref('');
const phone = ref('');
const address = ref('');
const confirmPassword = ref('');

// computed
const passwordMismatch = computed(
    () => password.value !== '' && password.value !== confirmPassword.value
);

const isFormValid = computed(() => {
    return (
        name.value !== '' &&
        username.value !== '' &&
        email.value !== '' &&
        phone.value !== '' &&
        address.value !== '' &&
        password.value !== '' &&
        confirmPassword.value !== '' &&
        !passwordMismatch.value &&
        emailError.value === 'Invalid email address.'
    );
});

// methods
const validateEmail = () => {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regex.test(email.value)) {
        emailError.value = 'Invalid email address.';
    } else {
        emailError.value = '';
    }
};

// watch
watch(email, () => {
    validateEmail();
});

// ajax
const createAccount = () => {
    loading.value = true;
     $.ajax({
        url: `${store.appURL}/index.php`,
        type: 'POST',
        xhrFields: {
            withCredentials: true
        },
        data: {
            createAccount: '',
            name: name.value,
            userName: username.value,
            passWord: password.value,
            email: email.value,
            phone: phone.value,
            address: address.value
        },
        success: (data, textStatus, jqXHR) => {
            if (jqXHR.status === 200) {
                setTimeout(() => {
                    snackbar.value = true;
                    name.value = null;
                    username.value = null;
                    email.value = null;
                    phone.value = null;
                    address.value = null;
                    password.value = null;
                    confirmPassword.value = null;
                }, 1000);
                loading.value = false;
                dialog.value = false;
            }
        },
        error: (error) => {
            loading.value = false;
            alert(`ERROR ${error.status}: ${error.statusText}`);
        },
    });
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

</style>