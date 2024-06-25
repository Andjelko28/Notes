import './bootstrap';
import '../sass/app.scss';
import { isAuth, logOut } from '../js/auth';
import { createApp } from 'vue';
import routes from "./routes";

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const app = createApp({
    data() {
        return {
            isAuthenticated: isAuth()
        };
    },
    methods: {
        logOut() {
            logOut();
            this.isAuthenticated = false;
            routes.push('/');
        },
        login(token) {
            localStorage.setItem('AuthToken', token);
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            this.isAuthenticated = true;
        }
    }
});
app.use(routes)
    .mount('#app')
