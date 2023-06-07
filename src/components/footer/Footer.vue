<template>
    <v-footer class="mt-16">
        <v-row class="d-flex ma-16">
            <v-col>
                <ul>
                    <li v-for="leftLink in leftFooterLinks"
                        class="d-flex justify-end py-5"
                        :class="{
                            'underlined-text underlined-text-left' : leftLink === store.app.title
                        }"
                    >
                        {{ leftLink }}
                    </li>
                </ul>
            </v-col>
            <v-col class="d-flex justify-center align-center">
                <ul class="">
                    <li class=" text-h2 pally py-5">{{ store.app.title }}</li>
                </ul>
            </v-col>
            <v-col>
                <ul>
                    <li v-for="rightLink in rightFooterLinks"
                        class="d-flex justify-start py-5"
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

<script setup>
import {useStore} from "@/stores";
import {reactive} from "vue";

const store = useStore();

const leftFooterLinks = reactive([store.app.title, 'About Us', 'FAQ']);
const rightFooterLinks = reactive(['Support', 'Contact Us', 'Terms of Use']);


</script>

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