import axios from "axios";

export function isAuth() {
    const token = localStorage.getItem('AuthToken');
    if (token) {
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        console.log('You are auth');
        return true;
    }
    return false;
}

export function logOut() {
    localStorage.removeItem('AuthToken');
    delete axios.defaults.headers.common['Authorization'];
}