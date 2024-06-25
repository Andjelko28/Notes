<template>
    <div class="mt-5 w-50 m-0 mx-auto">
        <h1>Todo list</h1>
        <form @sumbit.prevent="addTodo">
            <input v-model="newTodo" placeholder="New Todo" class="form-control" />
            <button type="submit" class="btn btn-primary mt-2">Add</button>
        </form>
        <ul>
            <li v-for="todo in todos" :key="todo.id"> {{ todo.title }}</li>
            <ul>
                <li v-for="item in todos.items" :key="item.id">
                    <input type="checkbox" v-model="item.completed" @change="updateItem(item)" />
                    {{ item.name }}
                </li>
            </ul>
        </ul>
        <form @sumbit.prevent="addItem(todos.id)">
            <input v-model="newItem" placeholder="New Item" />
            <button type="submit">Add</button>
        </form>
    </div>
</template>

<script>
import axios from 'axios';


export default {
    data() {
        return {
            todos: [],
            newTodo: '',
            newItem: ''
        }
    }, created() {
        this.fetchTodos();
    },
    methods: {
        async fetchTodos() {
            const response = await axios.get('/api/todos');
            this.todos = response.data;
        },
        async addTodo() {
            if (!this.newTodo) return;
            try {
                const response = await axios.post('/api/todos', { title: this.newTodo });
                this.todos.push(response.data);
                this.newTodo = '';
            } catch (error) {
                console.error('Error adding todo', error);
            }
        },
        async addItem(todoId) {
            const response = await axios.post(`/api/todos/${todoId}/items`, { name: this.newItem });
            const todo = this.todos.find(todos => todos.id === todoId);
            todo.items.push(response.data);
            this.newItem = '';
        },
        async updateItem(item) {
            await axios.put(`/api/items/${item.id}`, { completed: item.completed });
        }
    },
}
</script>
