<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">

    <title>Todos app</title>

    <style>

        .todos {

            background: #fdb7b7;
            padding: 20px 0;
            border-radius: 5px;
            margin-top: 15px;
        }

        .todo-title {

            padding-left: 10px;
        }

        .todo {

            padding: 10px 0;
        }
    </style>

  </head>
  <body>
    

    <div id="app">
        <div class="container">
            <div class="row">

                <div class="offset-md-3 col-md-6">

                    <h1 class="text-center">My tasks</h1>
                    <form @submit.prevent="">
                        <input @keyup.enter="addTodo" class="form-control" v-model="todoTitle" placeholder="What i need to do">
                    </form>

                    <div v-if="hasTodos" class="todos">
                        <div class="todo" v-for="todo in todos">

                            <div class="row">

                                <div class="col-md-8">

                                    <span class="lead todo-title" v-html="todo.title"></span>

                                </div>

                                <div class="col-md-4">
                                    <button class="btn btn-sm btn-danger" @click="deleteTodo(todo)">
                                        delete
                                    </button>

                                    <span class="btn btn-sm btn-primary" @click="doneTodo(todo)" v-html="status(todo.done)"></span>

                                </div>
                            </div>

                            

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Axios -->
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <!-- Vue Js -->
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script>

        new Vue ({
            el : '#app',
            data : {

                todoTitle : "",
                todo : {},
                todos : [],
                message : "",

            },

            computed : {

                hasTodos : function () {

                    return (this.todos.length != 0) ? true : false;
                }
            },

            methods : {

                addTodo : function () {

                    this.todo = { title : this.todoTitle, done : false};

                    this.todos.push(this.todo);

                    this.todoTitle = "";

                    axios
                    .post('http://127.0.0.1:8000/api/addTodo', {

                        todo : this.todo,
                        
                    })
                    .then(function (response) {
                        alert(response.data.message);
                    })

                    this.refreshTodos();
                },

                deleteTodo : function (todo) {

                    var todoIndex = this.todos.indexOf(todo);

                    this.todos.splice(todoIndex, 1);

                    axios
                    .post('http://127.0.0.1:8000/api/deleteTodo', {

                        id : todo.id
                    })
                    .then(function (response) {
                        alert(response.data.message);
                    })

                },

                doneTodo : function (todo) {

                    axios
                    .post('http://127.0.0.1:8000/api/doneTodo', {

                        id : todo.id,
                        done : !todo.done,
                    })
                    .then(function (response) {
                        alert(response.data.message);
                    })

                    this.refreshTodos();

                    this.status(todo.done);

                },

                refreshTodos : function () {

                    axios
                    .get('http://127.0.0.1:8000/api/todos')
                    .then(response => {

                        this.todos = response.data.todos;
                    })
                },

                status : function (done) {

                    return  (done) ? "pending" : "done";

                }
            },

            created () {

                axios
                .get('http://127.0.0.1:8000/api/todos')
                .then(response => {

                    if(response.data.todos == null){

                        this.todos = [];

                    }else {

                        this.todos = response.data.todos;
                    }
                })
            }

        });
    </script>
  </body>
</html>