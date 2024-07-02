<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <form @submit.prevent="registerUser" class="mb-3">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input
                            type="email"
                            id="email"
                            class="form-control"
                            v-model="form.email"
                            required
                        />
                        <span v-if="formErrors.email" class="alert alert-danger mt-2">
                            {{ formErrors.email }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input
                            type="password"
                            id="password"
                            class="form-control"
                            v-model="form.password"
                            required
                        />
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input
                            type="password"
                            id="password_confirmation"
                            class="form-control"
                            v-model="form.password_confirmation"
                            required
                        />
                    </div>
                    <div v-if="formErrors.password" class="alert alert-danger my-3">
                        {{ formErrors.password[0] }}
                    </div>
                    <div class="text-center m-3">
                        <a href="/login" class="deco-none">Already have an account? Log In.</a>
                    </div>
                    <div class="mt-3 d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">Register</button>
                        <button type="button" @click="cancel" class="btn btn-secondary">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
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
                if (error.response) {
                    if (error.response.status === 422) {
                        this.formErrors = error.response.data.errors;
                    } else {
                        this.registerError =
                            "Registration failed, please try again.";
                    }
                } else {
                    this.registerError =
                        "Registration failed, please try again.";
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
