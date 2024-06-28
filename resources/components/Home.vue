<template>
    <div class="mt-5 w-50 m-0 mx-auto">
        <h1>Todo list</h1>
        <form @submit.prevent="addTodo">
            <input v-model="newTodo" placeholder="New Todo" class="form-control" />
            <button type="submit" class="btn btn-primary mt-2">Add</button>
        </form>
        <ul class="list-group mt-4">
            <li v-for="todo in todos" :key="todo.id" class="list-group-item">
                <div v-if="todo.isEditing">
                    <input type="text" v-model="todo.newTitle" class="form-control" />
                    <div class="d-flex justify-content-between m-3">
                        <button @click="saveTodoTitle(todo)" class="btn btn-success">Save</button>
                        <button @click="cancelEdit(todo)" class="btn btn-secondary">Cancel</button>
                    </div>
                </div>
                <div v-else class="d-flex justify-content-between align-items-center">
                    <span @click="selectElement(todo)">{{ todo.title }}</span>
                    <div>
                        <button @click="editElement(todo)" class="btn btn-primary m-1">Edit</button>
                        <button @click="deleteTodo(todo.id)" class="btn btn-danger">Delete</button>
                    </div>
                </div>
                <ul v-if="todo.items && todo.items.length > 0" class="list-group">
                    <li v-for="item in todo.items" :key="item.id" class="list-group-item mt-2">
                        <div v-if="item.isEditing">
                            <input type="text" v-model="item.name" class="form-control" />
                            <div class="d-flex justify-content-between m-3">
                                <button @click="updateItem(todo.id, item)" class="btn btn-success">Save</button>
                                <button @click="cancelEdit(item)" class="btn btn-secondary">Cancel</button>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <input type="checkbox" v-model="item.completed"
                                        @change="updateItem(todo.id, item)" />
                                    {{ item.name }}
                                </div>
                                <div>
                                    <button @click="editElement(item)" class="btn btn-primary m-1">Edit</button>
                                    <button @click="deleteItem(todo.id, item.id)" class="btn btn-danger">Delete</button>
                                </div>
                            </div>
                        </div>
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
                let updateData = {};

                // Update item's name if it's edited
                if (item.newName !== undefined) {
                    updateData.name = item.newName;
                }

                // Update item's completion status
                if (item.completed !== undefined) {
                    await axios.put(`/api/todos/${todoId}/items/${item.id}`, { completed: item.completed }, {
                        headers: {
                            Authorization: `Bearer ${token}`
                        }
                    });

                    let storedCompletedStates = JSON.parse(localStorage.getItem('completedStates')) || {};
                    if (!storedCompletedStates[todoId]) {
                        storedCompletedStates[todoId] = {};
                    }
                    storedCompletedStates[todoId][item.id] = item.completed;
                    localStorage.setItem('completedStates', JSON.stringify(storedCompletedStates));
                }

                // If there are any updates to send, do it
                if (Object.keys(updateData).length > 0) {
                    const response = await axios.put(`/api/todos/${todoId}/items/${item.id}`, updateData, {
                        headers: {
                            Authorization: `Bearer ${token}`
                        }
                    });
                }

                // After successful update, reset item's state
                item.isEditing = false;
            } catch (error) {
                console.error('Error updating item', error);
            }
        },
        selectElement(x) {
            this.selectedTodo = x;
        },
        editElement(x) {
            x.isEditing = true;
        },
        async saveTodoTitle(todo) {
            try {
                const token = localStorage.getItem('AuthToken');
                const response = await axios.put(`/api/todos/${todo.id}`, { title: todo.newTitle }, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });
                todo.title = response.data.title;
                todo.isEditing = false;
            } catch (error) {
                console.error('Error updating title', error);
            }
        },
        cancelEdit(x, y) {
            x.isEditing = false;
            x.newTitle = y.title;
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
        },
        async deleteTodo(todoId) {
            try {
                const token = localStorage.getItem('AuthToken');
                await axios.delete(`/api/todos/${todoId}`, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });
                this.todos = this.todos.filter(todo => todo.id !== todoId);
                this.selectedTodo = null;
            } catch (error) {
                console.error('Error deleting todo', error);
            }
        },
        async deleteItem(todoId, itemId) {
            try {
                const token = localStorage.getItem('AuthToken');
                await axios.delete(`/api/todos/${todoId}/items/${itemId}`, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });
                this.todos = this.todos.map(todo => {
                    if (todo.id === todoId) {
                        todo.items = todo.items.filter(item => item.id !== itemId);
                    }
                    return todo;
                });
            } catch (error) {
                console.error('Error deleting item:', error);
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
