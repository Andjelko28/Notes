<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <form @submit.prevent="login" class="mb-3">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input
                            type="email"
                            id="email"
                            v-model="form.email"
                            class="form-control"
                            required
                        />
                        <span v-if="formErrors.email" class="error">{{
                            formErrors.email[0]
                        }}</span>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label"
                            >Password</label
                        >
                        <input
                            type="password"
                            id="password"
                            class="form-control"
                            v-model="form.password"
                            required
                        />
                    </div>
                    <span v-if="formErrors.password" class="error">{{
                        formErrors.password[0]
                    }}</span>
                    <div v-if="loginError" class="alert alert-danger mt-3">
                        {{ loginError }}
                    </div>

                    <div class="text-center m-3">
                        <a href="/register" class="deco-none"
                            >You don't have an account? Sign Up.</a
                        >
                    </div>
                    <div class="mt-3 d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            Log in
                        </button>
                        <button class="btn btn-secondary" @click="cancel">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            form: {
                email: "",
                password: "",
            },
            formErrors: {},
            loginError: "",
        };
    },
    methods: {
        async login() {
            try {
                const response = await axios.post("/api/login", this.form);
                const token = response.data.token;
                localStorage.setItem("AuthToken", token);
                axios.defaults.headers.common[
                    "Authorization"
                ] = `Bearer ${token}`;

                window.location = "/";
                return this.$router.push("/");
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.loginError = "Your email or password are incorrect!";
                } else {
                    this.loginError = "Logging in failed, please try again!";
                }
            }
        },
        cancel() {
            this.form.email = "";
            this.form.password = "";
            this.formErrors = "";
            this.loginError = "";
        },
    },
};
</script>
