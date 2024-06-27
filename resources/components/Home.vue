<template>
    <div class="mt-5 w-50 m-0 mx-auto">
        <h1>Todo list</h1>
        <form @submit.prevent="addTodo">
            <input v-model="newTodo" placeholder="New Todo" class="form-control" />
            <button type="submit" class="btn btn-primary mt-2">Add</button>
        </form>
        <ul>
            <li v-for="todo in todos" :key="todo.id">
                <span @click="selectTodo(todo)">{{ todo.title }}</span>
                <ul v-if="todo.items && todo.items.length > 0">
                    <li v-for="item in todo.items" :key="item.id">
                        <input type="checkbox" v-model="item.completed" @change="updateItem(todo.id, item)" />
                        {{ item.name }}
                    </li>
                </ul>
            </li>
        </ul>
        <div v-if="selectedTodo">
            <h2>Add item to {{ selectedTodo.title }}</h2>
            <form @submit.prevent="addItem(selectedTodo.id)">
                <input v-model="newItem" placeholder="New Item" class="form-control" />
                <button type="submit" class="btn btn-primary mt-2">Add</button>
            </form>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import routes from '../js/routes';

export default {
    data() {
        return {
            todos: [],
            newTodo: '',
            newItem: '',
            selectedTodo: null,
            isLog: window.isAuth,
        };
    },
    methods: {
        async fetchTodos() {
            try {
                const token = localStorage.getItem('AuthToken');
                if (!token) {
                    routes.push('/login');
                    return;
                }
                const response = await axios.get(`/api/todos`, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });
                this.todos = response.data;

                const storedCompletedStates = JSON.parse(localStorage.getItem('completedStates')) || {};
                this.todos.forEach(todo => {
                    if (storedCompletedStates[todo.id] !== undefined) {
                        todo.items.forEach(item => {
                            item.completed = storedCompletedStates[todo.id][item.id] || false;
                        });
                    }
                });
            } catch (error) {
                console.error('Error fetching data', error);
            }
        }, async addTodo() {
            if (this.newTodo) {
                try {
                    const token = localStorage.getItem('AuthToken');
                    const response = await axios.post(`/api/todos`, { title: this.newTodo }, {
                        headers: {
                            Authorization: `Bearer ${token}`
                        }
                    })
                    this.todos.push(response.data);
                    this.newTodo = '';
                } catch (error) {
                    console.error('Error adding todo', error);
                }
            }
        },
        async addItem(todoId) {
            const token = localStorage.getItem('AuthToken');
            const response = await axios.post(`/api/todos/${todoId}/items`, { name: this.newItem }, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });
            const todo = this.todos.find(todo => todo.id === todoId);
            if (todo) {
                todo.items = todo.items || [];
                todo.items.push(response.data);
            }
            this.newItem = '';
        },
        async updateItem(todoId, item) {
            try {
                const token = localStorage.getItem('AuthToken');
                await axios.put(`/api/todos/${todoId}/items/${item.id}`, { completed: item.completed }, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                })

                let storedCompletedStates = JSON.parse(localStorage.getItem('completedStates')) || {};
                if (!storedCompletedStates[todoId]) {
                    storedCompletedStates[todoId] = {};
                }
                storedCompletedStates[todoId][item.id] = item.completed;
                localStorage.setItem('completedStates', JSON.stringify(storedCompletedStates));
            } catch (error) {
                console.error('Error updating item', error);
            }
        },
        selectTodo(todo) {
            this.selectedTodo = todo;
        },
        async logoutUser() {
            try {
                const token = localStorage.getItem('AuthToken');
                await axios.post('/api/logout', {}, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });
                localStorage.removeItem('AuthToken');
                delete axios.defaults.headers.common['Authorization'];
                this.isLog = false;
                routes.push('/login');
            } catch (error) {
                console.error('Error logging out', error);
            }
        }
    },
    mounted() {
        this.fetchTodos();

        const logoutLink = document.querySelector('#logout');
        if (logoutLink) {
            logoutLink.addEventListener('click', this.logoutUser());
        }
    }
};
</script>
