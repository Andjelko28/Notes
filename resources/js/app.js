import './bootstrap';
import '../sass/app.scss';
import { createApp } from 'vue';
import routes from "./routes";

import axios from 'axios';
import NavButtons from '../components/NavButtons.vue';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


createApp({
    components: {
        NavButtons
    },
})
    .use(routes)
    .mount('#app')
