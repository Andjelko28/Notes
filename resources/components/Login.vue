<template>
    <div class="d-flex justify-content-center">
        <form @submit.prevent="loginUser" class="mb-3 w-50">
            <label for="email" class="form-label">Email</label>
            <input type="email" v-model="form.email" class="form-control" />
            <span v-if="formErrors.email" class="error">{{ formErrors.email[0] }}</span>
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" v-model="form.password" />
            <span v-if="formErrors.password" class="error">{{ formErrors.password[0] }}</span>
            <div class="mt-3 d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Log in</button>
                <button class="btn btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            form: {
                email: '',
                password: ''
            },
            formErrors: {}
        }
    }, methods: {
        async loginUser() {
            try {
                const response = await axios.post('/api/login', this.form);
                console.log(response.data);
                this.resetForm();
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.formErrors = error.response.data.errors;
                } else {
                    console.error('Login error', error);
                    alert('Logging in failed, please try again');
                }
            }
        },
        resetForm() {
            this.form.email = '';
            this.form.password = '';
            this.formErrors = {};
        }
    }
};
</script>
