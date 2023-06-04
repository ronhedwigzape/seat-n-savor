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
                                required
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field
                                label="Password"
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
                        Login
                    </v-btn>
                </v-container>
            </v-card-actions>
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