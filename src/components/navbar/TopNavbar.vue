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

        <SignIn />
        <SignUp />
    </v-app-bar>
</template>

<script setup>
import {ref} from "vue";
import SignUp from "@/components/dialogs/SignUpDialog.vue";
import {useStore} from "@/stores";
import SignIn from "@/components/dialogs/SignInDialog.vue";

const store = useStore();
const image = ref('dine.jpg');

</script>

<style scoped>

</style>