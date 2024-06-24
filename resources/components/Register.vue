<template>
    <form @submit.prevent="registerUser">
        <div>
            <label for="email">Email</label>
            <input type="email" id="email" v-model="form.email" required />
            <span v-if="formErrors.email" class="error">{{ formErrors.email[0] }}</span>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" v-model="form.password" required />
            <span v-if="formErrors.password" class="error">{{ formErrors.password[0] }}</span>
        </div>
        <div>
            <label for="password_confirmation">Confirm Password:</label>
            <input type="password" id="password_confirmation" v-model="form.password_confirmation" required />
        </div>
        <button type="submit">Register</button>
    </form>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            form: {
                email: '',
                password: '',
                password_confirmation: ''
            },
            formErrors: {}
        };
    },
    methods: {
        async registerUser() {
            try {
                const response = await axios.post('/api/register', this.form);
                // Handle successful registration
                console.log(response.data); // Assuming backend returns data on success
                // Reset form and errors
                this.resetForm();
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    // Handle validation errors
                    this.formErrors = error.response.data.errors;
                } else {
                    // Handle other errors
                    console.error('Registration error:', error);
                    alert('Registration failed. Please try again.');
                }
            }
        },
        resetForm() {
            this.form.email = '';
            this.form.password = '';
            this.form.password_confirmation = '';
            this.formErrors = {};
        }
    }
};

</script>
