<template>
    <div class="d-flex justify-content-center">
        <form @submit.prevent="login" class="mb-3 w-50">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" v-model="form.email" class="form-control" />
            <span v-if="formErrors.email" class="error">{{ formErrors.email[0] }}</span>
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" class="form-control" v-model="form.password" />
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
import routes from '../js/routes';

export default {
    data() {
        return {
            form: {
                email: '',
                password: ''
            },
            formErrors: {}
        }
    },
    methods: {
        async login() {
            try {
                const response = await axios.post('/api/login', this.form);
                const token = response.data.token;
                localStorage.setItem('AuthToken', token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                return routes.push('/');
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.formErrors = error.data.errors;
                } else {
                    console.error('Registration error', error);
                    console.error('Logging in failed, please try again');
                }
            }
        }
    }
}

</script>
