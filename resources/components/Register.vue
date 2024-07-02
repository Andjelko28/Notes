<template>
    <div class="d-flex justify-content-center">
        <form @submit.prevent="registerUser" class="mb-3 w-50">
            <div>
                <label for="email" class="form-label">Email</label>
                <input
                    type="email"
                    id="email"
                    class="form-control"
                    v-model="form.email"
                    required
                />
                <span v-if="formErrors.email" class="error">
                    {{ formErrors.email }}</span
                >
            </div>
            <div>
                <label for="password" class="form-label">Password</label>
                <input
                    type="password"
                    id="password"
                    class="form-control"
                    v-model="form.password"
                    required
                />
                <span v-if="formErrors.password" class="error">{{
                    formErrors.password[0]
                }}</span>
            </div>
            <div>
                <label for="password_confirmation" class="form-label"
                    >Confirm Password</label
                >
                <input
                    type="password"
                    id="password_confirmation"
                    class="form-control"
                    v-model="form.password_confirmation"
                    required
                />
                <span v-if="formErrors.password_confirmation" class="error">{{
                    formErrors.password_confirmation[0]
                }}</span>
            </div>
            <div class="mt-3 d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Register</button>
                <button class="btn btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
</template>

<script>
import axios from "axios";
import routes from "../js/routes";

export default {
    data() {
        return {
            form: {
                email: "",
                password: "",
                password_confirmation: "",
            },
            formErrors: {},
        };
    },
    methods: {
        async registerUser() {
            this.formErrors.email = "";
            try {
                const response = await axios.post("/api/register", this.form);
                console.log(response);
                const token = response.data.token;
                localStorage.setItem("AuthToken", token);
                axios.defaults.headers.common[
                    "Authorization"
                ] = `Bearer ${token}`;
                console.log("Registration successful", response.data);
                window.location = "/";
                this.resetForm();
                return routes.push("/");
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.formErrors = error.response.data.errors;
                } else {
                    console.error("Registration error:", error);
                    alert("Registration failed. Please try again.");
                }
            }
        },
        resetForm() {
            this.form.email = "";
            this.form.password = "";
            this.form.password_confirmation = "";
            this.formErrors = {};
        },
    },
};
</script>
