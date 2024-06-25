import './bootstrap';
import '../sass/app.scss'
import { createApp } from 'vue';
import routes from "./routes";

import axios from 'axios';
window.axios = axios;

// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

createApp({})
    .use(routes)
    .mount('#app')
