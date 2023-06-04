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
            >
                Sign Up
            </v-btn>
        </template>
        <v-row class="d-flex justify-center align-center">
        <v-card>
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
                    <p class="text-lg-h2 text-md-h3 text-h4 pally">Create Your Account</p>
                </v-col>
            </v-card-title>
            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col
                            cols="12"
                            sm="6"
                            md="6"
                        >
                            <v-text-field
                                label="Fullname*"
                                variant="outlined"
                                required
                            ></v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            sm="6"
                            md="6"
                        >
                            <v-text-field
                                label="Username*"
                                variant="outlined"
                                hint="This will be used for login."
                                required
                            ></v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <v-text-field
                                label="Email*"
                                variant="outlined"
                                required
                            ></v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <v-text-field
                                label="Phone Number*"
                                variant="outlined"
                                required
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field
                                label="Password*"
                                variant="outlined"
                                type="password"
                                required
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field
                                label="Confirm Password*"
                                variant="outlined"
                                type="password"
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
                        Create Account
                    </v-btn>
                </v-container>
            </v-card-actions>
<!--                <v-card-text>-->
<!--                    <v-col class="d-flex justify-center align-center" cols="12">-->
<!--                        <p>Already have an account? Sign in Now!</p>-->
<!--                    </v-col>-->
<!--                </v-card-text>-->
        </v-card>
        </v-row>
    </v-dialog>
</template>

<script setup>

import {ref} from "vue";

const dialog = ref(false);

</script>


<style scoped>

</style>