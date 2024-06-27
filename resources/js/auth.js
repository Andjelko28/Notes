import axios from "axios";

function isLoggedIn() {
    const token = localStorage.getItem('AuthToken');
    return !!token;
}

function getAuthToken() {
    return localStorage.getItem('AuthToken');
}

function logOut() {
    localStorage.removeItem('AuthToken');
    delete axios.defaults.headers.common['Authorization'];
}

export default { isLoggedIn, getAuthToken, logOut }
