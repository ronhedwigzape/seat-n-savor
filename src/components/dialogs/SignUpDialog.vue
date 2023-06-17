<template>
    <v-dialog
        transition="dialog-bottom-transition"
        v-model="dialog"
        width="700"
    >
        <template v-slot:activator="{ props }">
            <v-btn
                color="text-white"
                v-bind="props"
                :variant="{
                    'flat': $vuetify.display.smAndDown
                }"
                :class="{
                    'px-16': $vuetify.display.smAndDown
                }"
                :stacked="$vuetify.display.mdAndUp"
                :block="$vuetify.display.smAndDown"
            >
                Sign Up
            </v-btn>
        </template>
        <v-row class="d-flex justify-center align-center">
            <v-card width="1500">
                <v-card-title>
                    <v-btn
                        v-if="$vuetify.display.mdAndUp"
                        variant="text"
                        class="mt-2 float-end rounded"
                        @click="dialog = false"
                    >
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-col class="d-flex justify-center flex-column align-center pt-8 pt-sm-0 pt-md-0">
                        <p class="text-lg-h2 text-md-h3 text-sm-h4 text-h5 pally">Create Your Account</p>
                        <div class="text-caption text-md-subtitle-1 text-lg-subtitle-1 supreme text-grey-lighten-1 text-center">
                            Describe yourself clearly so that <p v-if="$vuetify.display.smAndDown">there are no mistakes.</p> <span v-else>there are no mistakes.</span>
                        </div>
                    </v-col>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" sm="6" md="6" class="py-0">
                                <v-text-field
                                    v-model="name"
                                    label="Fullname*"
                                    variant="outlined"
                                    hint="This will be your account name."
                                    :density="$vuetify.display.mdAndDown ? 'compact' : 'comfortable'"
                                >
                                    <template v-slot:prepend-inner>
                                        <v-icon v-if="$vuetify.display.smAndDown" size="20">mdi-account</v-icon>
                                        <v-icon v-else>mdi-account</v-icon>
                                    </template>
                                </v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="6" class="py-0">
                                <v-text-field
                                    v-model="username"
                                    label="Username*"
                                    variant="outlined"
                                    hint="This will be used for your login credentials."
                                    :density="$vuetify.display.mdAndDown ? 'compact' : 'comfortable'"
                                >
                                    <template v-slot:prepend-inner>
                                        <v-icon v-if="$vuetify.display.smAndDown" size="20">mdi-account-circle</v-icon>
                                        <v-icon v-else>mdi-account-circle</v-icon>
                                    </template>
                                </v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="6" lg="6" class="py-0">
                                <v-text-field
                                    v-model="email"
                                    label="Email*"
                                    variant="outlined"
                                    :error-messages="emailError"
                                    :density="$vuetify.display.mdAndDown ? 'compact' : 'comfortable'"
                                    @input="onEmailInputChange"
                                >
                                    <template v-slot:prepend-inner>
                                        <v-icon v-if="$vuetify.display.smAndDown" size="20">mdi-email</v-icon>
                                        <v-icon v-else>mdi-email</v-icon>
                                    </template>
                                </v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="6" lg="6" class="py-0">
                                <v-text-field
                                    v-model="phone"
                                    label="Phone Number*"
                                    type="number"
                                    variant="outlined"
                                    :error-messages="phoneError"
                                    @input="onPhoneInputChange"
                                    :density="$vuetify.display.mdAndDown ? 'compact' : 'comfortable'"
                                    counter
                                >
                                    <template v-slot:prepend-inner>
                                        <v-icon v-if="$vuetify.display.smAndDown" size="20">mdi-phone</v-icon>
                                        <v-icon v-else>mdi-phone</v-icon>
                                        <p class="ps-1">+63</p>
                                    </template>
                                </v-text-field>
                            </v-col>
                            <v-col cols="12" class="py-0">
                                <v-text-field
                                    v-model="address"
                                    label="Address*"
                                    variant="outlined"
                                    :density="$vuetify.display.mdAndDown ? 'compact' : 'comfortable'"
                                >
                                    <template v-slot:prepend-inner>
                                        <v-icon v-if="$vuetify.display.smAndDown" size="20">mdi-map-marker</v-icon>
                                        <v-icon v-else>mdi-map-marker</v-icon>
                                    </template>
                                </v-text-field>
                            </v-col>
                            <v-col cols="12" class="py-0">
                                <v-text-field
                                    v-model="password"
                                    label="Password*"
                                    variant="outlined"
                                    hint="This will be used for your login credentials."
                                    :type="isPasswordShown1 ? 'text' : 'password'"
                                    @click:append-inner="isPasswordShown1 = !isPasswordShown1"
                                    :density="$vuetify.display.mdAndDown ? 'compact' : 'comfortable'"
                                >
                                    <template v-slot:prepend-inner>
                                        <v-icon v-if="$vuetify.display.smAndDown" size="20">mdi-lock</v-icon>
                                        <v-icon v-else>mdi-lock</v-icon>
                                    </template>
                                    <template v-slot:append-inner>
                                        <v-btn
                                            v-if="$vuetify.display.smAndDown"
                                            @click="isPasswordShown1 = !isPasswordShown1"
                                            size="20"
                                            :ripple="false"
                                            flat
                                        >
                                            <v-icon size="20">{{ isPasswordShown1 ? 'mdi-eye' : 'mdi-eye-off' }}</v-icon>
                                        </v-btn>
                                        <v-btn
                                            v-else
                                            @click="isPasswordShown1 = !isPasswordShown1"
                                            :ripple="false"
                                            flat
                                        >
                                            <v-icon>{{ isPasswordShown1 ? 'mdi-eye' : 'mdi-eye-off' }}</v-icon>
                                        </v-btn>
                                    </template>
                                </v-text-field>
                            </v-col>
                            <v-col cols="12" class="py-0">
                                <v-text-field
                                    v-model="confirmPassword"
                                    label="Confirm Password*"
                                    variant="outlined"
                                    :type="isPasswordShown2 ? 'text' : 'password'"
                                    @click:append-inner="isPasswordShown2 = !isPasswordShown2"
                                    :density="$vuetify.display.mdAndDown ? 'compact' : 'comfortable'"
                                >
                                    <template v-slot:prepend-inner>
                                        <v-icon v-if="$vuetify.display.smAndDown" size="20">mdi-lock</v-icon>
                                        <v-icon v-else>mdi-lock</v-icon>
                                    </template>
                                    <template v-slot:append-inner>
                                        <v-btn
                                            v-if="$vuetify.display.smAndDown"
                                            @click="isPasswordShown2 = !isPasswordShown2"
                                            size="20"
                                            :ripple="false"
                                            flat
                                        >
                                            <v-icon size="20">{{ isPasswordShown2 ? 'mdi-eye' : 'mdi-eye-off' }}</v-icon>
                                        </v-btn>
                                        <v-btn
                                            v-else
                                            @click="isPasswordShown2 = !isPasswordShown2"
                                            :ripple="false"
                                            flat
                                        >
                                            <v-icon>{{ isPasswordShown2 ? 'mdi-eye' : 'mdi-eye-off' }}</v-icon>
                                        </v-btn>
                                    </template>
                                </v-text-field>
                            </v-col>
                            <transition name="fade">
                                <v-col
                                    cols="12" v-if="passwordMismatch"
                                    style="z-index: 3;"
                                    class="bg-red-accent-1 py-3"
                                >
                                    Passwords do not match
                                </v-col>
                            </transition>
                        </v-row>
                        <v-btn
                            @click="verifyUser"
                            variant="tonal"
                            class="mt-3"
                            :prepend-icon="solved ? 'mdi-check-decagram' : ''"
                            :color="solved ? 'green-accent-2' : ''"
                            block
                        >
                            {{ solved ? 'User Verified' : 'Click Here to Verify User' }}
                        </v-btn>
                        <v-card-actions class="py-0 d-flex justify-center">
                            <v-btn
                                v-if="$vuetify.display.smAndDown"
                                @click="dialog = false"
                                color="orange-accent-2"
                            >
                                Close
                            </v-btn>
                            <v-spacer v-if="$vuetify.display.smAndDown"/>
                            <v-btn
                                color="orange-accent-4"
                                variant="text"
                                type="submit"
                                @click="createAccount"
                                :disabled="!isFormValid"
                                :block="$vuetify.display.mdAndUp"
                            >
                                Create Account
                            </v-btn>
                        </v-card-actions>
                    </v-container>
                </v-card-text>
            </v-card>
        </v-row>
        <v-snackbar
            v-model="invalidSnackbar"
            multi-line
        >
            <v-icon class="text-red pb-1">mdi-close-circle</v-icon>
            User verification failed. Please provide valid credentials for authentication.
            <template v-slot:actions>
                <v-btn
                    color="red"
                    variant="text"
                    @click="dialog2 = false"
                >
                    Close
                </v-btn>
            </template>
        </v-snackbar>
    </v-dialog>
    <v-snackbar
        v-model="snackbar"
        multi-line
    >
        <v-icon class="text-green pb-1">mdi-check-circle</v-icon>
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
const dialog2 = ref(false);
const loading = ref(false);
const isPasswordShown1 = ref(false);
const isPasswordShown2 = ref(false);
const snackbar = ref(false);
const invalidSnackbar = ref(false);
const solved = ref(false);
const name = ref('');
const username = ref('');
const password = ref('');
const email = ref('');
const emailError = ref('');
const phoneError = ref('');
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
        emailError.value === '' &&
        phoneError.value === '' &&
        solved.value !== false
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

const validatePhoneNumber = () => {
    const phoneNumberPattern = /^9\d{9}$/;
    if (phone.value.match(phoneNumberPattern)) {
        phoneError.value = '';
    } else {
        phoneError.value = 'Invalid phone number.'
    }
}

const onEmailInputChange = () => {
    validateEmail();
};

const onPhoneInputChange = () => {
  validatePhoneNumber();
}

// watch
watch(email, () => {
    validateEmail();
});

watch(phone, () => {
    validatePhoneNumber();
})

// ajax
const verifyUser = () => {
    if (name.value !== '' &&
        username.value !== '' &&
        email.value !== '' &&
        phone.value !== '' &&
        address.value !== '' &&
        password.value !== '' &&
        confirmPassword.value !== '' &&
        !passwordMismatch.value &&
        emailError.value === '' &&
        phoneError.value === '') {
        grecaptcha.ready(function() {
            grecaptcha.execute('6LfuJp0mAAAAAAl8pZfTn87EnZOoqWM3pOVNN3X1', {action: 'submit'}).then(function(token) {
                $.ajax({
                    url: `${store.appURL}/index.php`,
                    type: 'POST',
                    xhrFields: {
                        withCredentials: true
                    },
                    data: {
                        'g-recaptcha-response': token
                    },
                    success: (data, textStatus, jqXHR) => {
                        data = JSON.parse(data);
                        if (jqXHR.status === 200) {
                            solved.value = data.solved;
                        }
                    },
                    error: (error) => {
                        alert(`ERROR ${error.status}: ${error.statusText}`);
                    },
                });
            });
        });
    }
    else {
        invalidSnackbar.value = true;
    }
}

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
                }, 2500);
                name.value = null;
                username.value = null;
                email.value = null;
                phone.value = null;
                address.value = null;
                password.value = null;
                confirmPassword.value = null;
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