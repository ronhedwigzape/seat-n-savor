<template>
    <v-app>
        <Welcome />
    </v-app>
</template>

<script setup>
import Welcome from "@/views/Welcome.vue";
import {onMounted} from "vue";
import {useStore} from "@/stores";
import {useAuthStore} from "@/stores/store-auth";

const store = useStore();
const authStore = useAuthStore();

onMounted(() => {
    // check for authenticated user
    $.ajax({
        url: `${store.appURL}/index.php`,
        type: 'GET',
        xhrFields: {
            withCredentials: true
        },
        data: {
            getUser: ''
        },
        success: (data) => {
            data = JSON.parse(data);
            if (data.user) {
                authStore.setUser(data.user);

                // router.replace({
                //     name: data.user.userType
                // });
            }

        },
        error: (error) => {
            alert(`ERROR ${error.status}: ${error.statusText}`);
        },
    });
});

</script>
<style scoped>

</style>
