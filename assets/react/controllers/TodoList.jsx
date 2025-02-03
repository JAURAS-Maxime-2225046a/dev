import React, { useState, useEffect } from "react";
import axios from "axios";

const TodoList = () => {
    const [todos, setTodos] = useState([]);
    const [newTodo, setNewTodo] = useState("");
    const [loading, setLoading] = useState(true);

    // Récupérer la liste des todo
    useEffect(() => {
        axios
            .get("/api/todos")
            .then((response) => {
                setTodos(response.data);
                setLoading(false);
            })
            .catch((error) => {
                console.error("Error fetching todos:", error);
                setLoading(false);
            });
    }, []);

    // Ajouter une nouvelle tâche
    const handleAddTodo = () => {
        if (!newTodo.trim()) return;

        axios
            .post("/api/todo", { title: newTodo })
            .then((response) => {
                setTodos([...todos, response.data]);
                setNewTodo(""); // Clear input
            })
            .catch((error) => {
                console.error("Error adding todo:", error);
            });
    };

    // Marquer une tâche comme terminée
    const handleToggleCompleted = (id, currentStatus) => {
        axios
            .put(`/api/todo/${id}`, { completed: !currentStatus })
            .then((response) => {
                const updatedTodos = todos.map((todo) =>
                    todo.id === id ? { ...todo, completed: !currentStatus } : todo
                );
                setTodos(updatedTodos);
            })
            .catch((error) => {
                console.error("Error updating todo:", error);
            });
    };

    // Supprimer une tâche
    const handleDeleteTodo = (id) => {
        axios
            .delete(`/api/todo/${id}`)
            .then(() => {
                setTodos(todos.filter((todo) => todo.id !== id));
            })
            .catch((error) => {
                console.error("Error deleting todo:", error);
            });
    };

    return (
        <div className="max-w-lg mx-auto p-6 bg-white rounded-lg shadow-lg">
            <h1 className="text-2xl font-bold mb-4">Todo List</h1>

            {loading ? (
                <p>Loading...</p>
            ) : (
                <>
                    <div className="mb-4">
                        <input
                            type="text"
                            value={newTodo}
                            onChange={(e) => setNewTodo(e.target.value)}
                            placeholder="Add a new task"
                            className="w-full px-4 py-2 border rounded-md"
                        />
                        <button
                            onClick={handleAddTodo}
                            className="mt-2 w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600"
                        >
                            Add Todo
                        </button>
                    </div>

                    <ul>
                        {todos.map((todo) => (
                            <li
                                key={todo.id}
                                className="flex items-center justify-between p-2 border-b"
                            >
                                <div className="flex items-center">
                                    <input
                                        type="checkbox"
                                        checked={todo.completed}
                                        onChange={() => handleToggleCompleted(todo.id, todo.completed)}
                                        className="mr-2"
                                    />
                                    <span
                                        className={`${
                                            todo.completed ? "line-through text-gray-500" : ""
                                        }`}
                                    >
                    {todo.title}
                  </span>
                                </div>

                                <button
                                    onClick={() => handleDeleteTodo(todo.id)}
                                    className="text-red-500 hover:text-red-700"
                                >
                                    Delete
                                </button>
                            </li>
                        ))}
                    </ul>
                </>
            )}
        </div>
    );
};

export default TodoList;