import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import vuetify from './vuetify/vuetify'
import {createPinia} from "pinia";
import './styles.css'

const app = createApp(App)

app.use(router)
    .use(createPinia())
   .use(vuetify)
   .mount('#app')

