<template>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Todo list</h1>
        <form @submit.prevent="addTodo" class="mb-3">
            <div class="input-group">
                <input
                    v-model="newTodo"
                    placeholder="New Todo"
                    class="form-control"
                />
                <button type="submit" class="btn btn-primary">Add</button>
            </div>
        </form>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4">
            <div v-for="todo in todos" :key="todo.id" class="col mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div v-if="todo.isEditing">
                            <input
                                type="text"
                                v-model="todo.title"
                                class="form-control mb-2"
                            />
                            <div class="d-flex justify-content-end">
                                <i
                                    @click="saveTodoTitle(todo)"
                                    class="bi bi-check-circle-fill text-success cursor-pointer mx-2"
                                ></i>
                                <i
                                    @click="cancelEdit(todo)"
                                    class="bi bi-x-circle-fill text-secondary cursor-pointer ml-2"
                                ></i>
                            </div>
                        </div>
                        <div
                            v-else
                            class="d-flex justify-content-between align-items-center"
                        >
                            <h5
                                @click="selectElement(todo)"
                                class="card-title cursor-pointer"
                            >
                                {{ todo.title }}
                            </h5>
                            <div class="d-flex justify-content-between">
                                <i
                                    @click="editElement(todo)"
                                    class="bi bi-pencil-square text-primary cursor-pointer mx-2"
                                ></i>
                                <i
                                    @click="deleteTodo(todo.id)"
                                    class="bi bi-trash text-danger cursor-pointer"
                                ></i>
                            </div>
                        </div>
                        <ul
                            v-if="todo.items && todo.items.length > 0"
                            class="list-group mt-3"
                        >
                            <li
                                v-for="item in todo.items"
                                :key="item.id"
                                class="list-group-item bg-grey"
                            >
                                <div v-if="item.isEditing">
                                    <input
                                        type="text"
                                        v-model="item.name"
                                        class="form-control mb-2"
                                    />
                                    <div class="d-flex justify-content-end">
                                        <i
                                            @click="updateItem(todo.id, item)"
                                            class="bi bi-check-circle-fill text-success cursor-pointer mx-2"
                                        ></i>
                                        <i
                                            @click="cancelEdit(item)"
                                            class="bi bi-x-circle-fill text-secondary cursor-pointer ml-2"
                                        ></i>
                                    </div>
                                </div>
                                <div
                                    v-else
                                    class="d-flex justify-content-between align-items-center"
                                >
                                    <div class="form-check">
                                        <input
                                            type="checkbox"
                                            v-model="item.completed"
                                            @change="updateItem(todo.id, item)"
                                            class="form-check-input border border-dark"
                                        />
                                        <label class="form-check-label">{{
                                            item.name
                                        }}</label>
                                    </div>
                                    <div>
                                        <i
                                            @click="editElement(item)"
                                            class="bi bi-pencil-square text-primary cursor-pointer mx-2"
                                        ></i>
                                        <i
                                            @click="
                                                deleteItem(todo.id, item.id)
                                            "
                                            class="bi bi-trash text-danger cursor-pointer ml-2"
                                        ></i>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="selectedTodo" class="card mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="card-title d-flex justify-content-between">
                    Add item to {{ selectedTodo.title }}
                    <i
                        class="bi bi-x-circle-fill text-secondary cursor-pointer ml-2"
                        @click="selectedTodo = null"
                    ></i>
                </h5>
                <form @submit.prevent="addItem(selectedTodo.id)">
                    <div class="input-group">
                        <input
                            v-model="newItem"
                            placeholder="New Item"
                            class="form-control"
                        />
                        <button type="submit" class="btn btn-primary">
                            Add
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
//  <style scoped>
//  .google-keep-card {
//      border-radius: 8px;
//      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
// transition: transform 0.2s;
//  }

//  .google-keep-card:hover {
//      transform: translateY(-5px);
//  }
//  </style>

import axios from "axios";
import routes from "../js/routes";
import { todo } from "node:test";

export default {
    data() {
        return {
            todos: [],
            newTodo: "",
            newItem: "",
            selectedTodo: null,
            isLog: window.isAuth,
        };
    },
    errors: {},
    methods: {
        async fetchTodos() {
            try {
                const token = localStorage.getItem("AuthToken");
                if (!token) {
                    routes.push("/login");
                    return;
                }
                const response = await axios.get(`/api/todos`, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                });
                this.todos = response.data;

                const storedCompletedStates =
                    JSON.parse(localStorage.getItem("completedStates")) || {};
                this.todos.forEach((todo) => {
                    if (storedCompletedStates[todo.id] !== undefined) {
                        todo.items.forEach((item) => {
                            item.completed =
                                storedCompletedStates[todo.id][item.id] ||
                                false;
                        });
                    }
                });
            } catch (error) {
                console.error("Error fetching data", error);
            }
        },
        async addTodo() {
            if (this.newTodo) {
                try {
                    const token = localStorage.getItem("AuthToken");
                    const response = await axios.post(
                        `/api/todos`,
                        { title: this.newTodo },
                        {
                            headers: {
                                Authorization: `Bearer ${token}`,
                            },
                        }
                    );
                    this.todos.push(response.data);
                    this.newTodo = "";
                } catch (error) {
                    console.error("Error adding todo", error);
                }
            }
        },
        async addItem(todoId) {
            const token = localStorage.getItem("AuthToken");
            const response = await axios.post(
                `/api/todos/${todoId}/items`,
                { name: this.newItem },
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                }
            );
            const todo = this.todos.find((todo) => todo.id === todoId);
            if (todo) {
                todo.items = todo.items || [];
                todo.items.push(response.data);
            }
            this.newItem = "";
        },
        async updateItem(todoId, item) {
            try {
                const token = localStorage.getItem("AuthToken");
                const response = await axios.put(
                    `/api/todos/${todoId}/items/${item.id}`,
                    { name: item.name, completed: item.completed },
                    {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    }
                );

                // Update the local state with the response from the server
                item.name = response.data.name;
                item.isEditing = false;

                // Update local storage for completed states
                let storedCompletedStates =
                    JSON.parse(localStorage.getItem("completedStates")) || {};
                if (!storedCompletedStates[todoId]) {
                    storedCompletedStates[todoId] = {};
                }
                storedCompletedStates[todoId][item.id] = item.completed;
                localStorage.setItem(
                    "completedStates",
                    JSON.stringify(storedCompletedStates)
                );
            } catch (error) {
                console.error("Error updating item", error);
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
                const token = localStorage.getItem("AuthToken");
                const response = await axios.put(
                    `/api/todos/${todo.id}`,
                    { title: todo.title },
                    {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    }
                );
                todo.title = response.data.title;
                todo.isEditing = false;
            } catch (error) {
                console.error("Error updating title", error);
            }
        },
        cancelEdit(x, y) {
            x.isEditing = false;
            x.newTitle = y.title;
        },

        async deleteTodo(todoId) {
            try {
                const token = localStorage.getItem("AuthToken");
                await axios.delete(`/api/todos/${todoId}`, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                });
                this.todos = this.todos.filter((todo) => todo.id !== todoId);
                this.selectedTodo = null;
            } catch (error) {
                console.error("Error deleting todo", error);
            }
        },
        async deleteItem(todoId, itemId) {
            try {
                const token = localStorage.getItem("AuthToken");
                await axios.delete(`/api/todos/${todoId}/items/${itemId}`, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                });
                this.todos = this.todos.map((todo) => {
                    if (todo.id === todoId) {
                        todo.items = todo.items.filter(
                            (item) => item.id !== itemId
                        );
                    }
                    return todo;
                });
            } catch (error) {
                console.error("Error deleting item:", error);
            }
        },
    },
    mounted() {
        this.fetchTodos();
    },
};
</script>
