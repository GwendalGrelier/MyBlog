<?php
    
    include "Models/NewsModel.php";
    include "Views/NewsView.php";
    

    class NewsController extends Controller{

        /**
         * Controller constructor
         * 
         * Creates an instance of the View and the Model.
         */
        public function __construct() {
            $this->view = new NewsView();
            $this->model = new NewsModel();
        }

        /**
         * Display the start page with the list of articles
         *
         * @return void
         */
        public function displayHome() {
            $newsList = $this->model->getNews();
            $this->view->displayHome($newsList);
        }
        
        /**
         * Displays the form to add a new article
         *
         * @return void
         */
        public function addForm() {
            $categories = $this->model->getCategories();
            $this->view->displayForm($categories);
        }

        /**
         * Adds an article to the database
         * Gets data from the pages/form.html
         *
         * @return void
         */
        public function addNewArticle() {
            $formIsValid =$this->model->addNewArticle();
            if (!$formIsValid) {
                $this->addForm();
            } else {
                header("Location: index.php");
            }
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
        public function updateForm() {

            $id = $_GET["article"];
            $articleToEdit = $this->model->getArticle($id);
            
            if ($articleToEdit["author"] == $_SESSION['user']['id'] || $_SESSION['user']['rank'] < 3) {
                $categories = $this->model->getCategories();
                $users = $this->model->getUsers();
                $this->view->displayEditArticle($articleToEdit, $categories, $users);
            } else {
                header("Location: index.php");
            }
        }

        public function editArticle() {
            
            $this->model->editArticle();
            header("Location: index.php");
        }

        /**
         * Deletes an article from the database
         * Gets the id of the article from the url
         *
         * @return void
         */
        public function deleteArticle() {
            $id = $_GET["article"];
            $article = $this->model->getArticle($id);
            if ($article["author"] == $_SESSION['user']['id'] || $_SESSION['user']['rank'] < 3) {
                $this->model->deleteArticle($id);
            }
            header("Location: index.php");
        }
    }