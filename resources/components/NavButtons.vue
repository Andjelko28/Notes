<template>
    <div>
        <button v-if="isLog" @click="logout" class="btn btn-primary">
            Logout
        </button>

        <router-link v-if="!isLog" to="/login" class="btn btn-primary m-1"
            >Login</router-link
        >
        <router-link v-if="!isLog" to="/register" class="btn btn-primary ml-2"
            >Signup</router-link
        >
    </div>
</template>
<script>
import axios from "axios";
import routes from "../js/routes";

export default {
    data() {
        return {
            isLog: localStorage.getItem("AuthToken"),
        };
    },
    methods: {
        async logout() {
            try {
                const token = localStorage.getItem("AuthToken");
                await axios.post("/api/logout", {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                });
                localStorage.removeItem("AuthToken");
                this.isLog = false;
                routes.push("/login"); // Redirect to login page using Vue Router
            } catch (error) {
                console.error("Error logging out:", error);
            }
        },
    },
};
</script>
