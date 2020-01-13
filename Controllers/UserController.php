<?php
    
    include "Models/UserModel.php";
    include "Views/UserView.php";
    

    class UserController extends Controller{

        /**
         * Controller constructor
         * 
         * Creates an instance of the View and the Model.
         */
        public function __construct() {
            $this->view = new UserView();
            $this->model = new UserModel();
        }

        /**
         * Display the start page with the list of articles
         *
         * @return void
         */
        public function displayHome() {
            $userList = $this->model->getUsers();
            $this->view->displayHome($userList);
        }

        public function displayUserPage()
        {
            $id = $_GET["user"];
            $user = $this->model->getUser($id);
            if ($user) {
                $articles = $this->model->getArticleList($id);
                $this->view->displayUserPage($user, $articles);
            } else {
                header("Location: index.php");
            }
        }
        
        /**
         * Displays the form to add a new article
         *
         * @return void
         */
        public function addForm() {
            $this->view->displayAddUserForm();
        }

        /**
         * Displays the form to modify the password
         *
         * @return void
         */
        public function displayPasswordForm() {
            $this->view->displayPasswordForm();
        }
        
        /**
         * Displays the form to modify the password
         *
         * @return void
         */
        public function editPassword() {
            $this->model->editPassword();
        }



        /**
         * Adds an article to the database
         * Gets data from the pages/form.html
         *
         * @return void
         */
        public function addNewUser() {
            $formIsValid = $this->model->addNewUser();
            if ($formIsValid) {
                header("Location: index.php?controller=user");
                
            } else {
                $this->view->displayAddUserForm();
            }
            // $this->start();
        }

        /**
         * Displays the form to edit the article
         *
         * Uses the model to retrieve the data from the database 
         * for the desired article
         * Then displays the edit page filled with the article data
         * 
         * @return void
         */
        public function updateUserForm() {
            $userToEdit = $this->model->getUser();

            if ($_SESSION['user']['rank'] < $userToEdit['rank'] || $userToEdit['id'] == $_SESSION['user']['id']) {
                $ranks = $this->model->getRanks();
                $this->view->displayEditUserForm($userToEdit, $ranks);
            } else {
                header("Location: index.php?controller=user");
            }

        }

        public function editUser() {
            $this->model->editUser();
            header("Location: index.php?controller=user");
        }

        /**
         * Deletes an article from the database
         * Gets the id of the article from the url
         *
         * @return void
         */
        public function deleteUser() {
            $id = $_GET["user"];
            $user_to_delete = $this->model->getUser($id);

            if ($_SESSION["user"]['rank'] < $user_to_delete["rank"] || $_SESSION['user']['id'] == $user_to_delete['id']) {
                $this->model->deleteUser($id);
            }
            header("Location: index.php?controller=user");
        }
    }