<template>
    <v-dialog
        transition="dialog-bottom-transition"
        v-model="dialog"
        persistent
    >
        <template v-slot:activator="{ props }">
            <v-btn
                color="text-white"
                v-bind="props"
            >
                Sign In
            </v-btn>
        </template>
        <v-row class="d-flex justify-center align-center">
        <v-card class="pa-1">
            <v-card-title >
                <v-btn
                    color="black"
                    variant="text"
                    class="mt-2 float-end rounded"
                    @click="dialog = false"
                >
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-col class="d-flex justify-center align-center">
                    <p class="text-lg-h2 text-md-h3 text-h4 pally">Sign In</p>
                </v-col>
            </v-card-title>
            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col cols="12">
                            <v-text-field
                                label="Username"
                                variant="outlined"
                                v-model="username"
                                prepend-inner-icon="mdi-account"
                                :rules="[rules.required]"
                                required
                                autofocus
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field
                                label="Password"
                                variant="outlined"
                                v-model="password"
                                prepend-inner-icon="mdi-lock"
                                :append-inner-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                                :type="show ? 'text' : 'password'"
                                :rules="[rules.required]"
                                @click:append-inner="show = !show"
                                required
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-container>
                    <v-btn
                        color="blue-darken-1"
                        variant="text"
                        @click="dialog = false"
                        block
                    >
                        Login
                    </v-btn>
                </v-container>
            </v-card-actions>
        </v-card>
        </v-row>
    </v-dialog>
</template>

<script setup>

import {reactive, ref} from "vue";

// data
const dialog = ref(false);
const username = ref('');
const password = ref('');
const show = ref(false);

const rules = reactive({
   required: value => !!value || 'Required.',
});


// methods

</script>


<style scoped>

</style>