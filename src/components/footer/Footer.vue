<script setup>
import {useStore} from "@/stores";
import {reactive} from "vue";

const store = useStore();

const leftFooterLinks = reactive([store.app.title, 'About Us', 'FAQ']);
const rightFooterLinks = reactive(['Support', 'Contact Us', 'Terms of Use']);

</script>
<template>
    <v-footer class="mt-lg-16 mb-0 pb-0">
        <v-row class="d-flex flex-column flex-sm-row flex-md-row flex-lg-row ma-lg-16 py-10 px-8">
            <v-col class="d-flex justify-start justify-sm-center justify-md-center">
                <ul class="text-center">
                    <li v-for="leftLink in leftFooterLinks"
                        class="d-flex justify-lg-end py-2 py-lg-5"
                        :class="{
                            'underlined-text underlined-text-left' : leftLink === store.app.title
                        }"
                    >
                        {{ leftLink }}
                    </li>
                </ul>
            </v-col>
            <v-col class="d-flex justify-start justify-sm-center justify-md-center align-center py-10 px-lg-8">
                <ul>
                    <li class="text-h4 text-lg-h2 pally py-lg-5">{{ store.app.title }}</li>
                </ul>
            </v-col>
            <v-col class="d-flex justify-start justify-sm-center justify-md-center">
                <ul>
                    <li v-for="rightLink in rightFooterLinks"
                        class="d-flex justify-lg-start py-2 py-lg-5"
                        :class="{
                            'underlined-text underlined-text-right': rightLink === 'Support'
                        }"
                    >
                        {{ rightLink }}
                    </li>
                </ul>
            </v-col>
        </v-row>
    </v-footer>
</template>

<style scoped>
li {
    list-style-type: none;
}

.underlined-text {
    position: relative;
}

.underlined-text::after {
    content: '';
    position: absolute;
    bottom: 0;
    width: 30%;
    height: 2px;
    background-color: #545454;
    margin-top: 0px;
}

.underlined-text-left {
    left: 0;
}

.underlined-text-right {
    right: 0;
}
</style>