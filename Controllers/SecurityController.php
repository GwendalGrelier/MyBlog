<?php
    
    include "Models/SecurityModel.php";
    include "Views/SecurityView.php";
    

    class SecurityController extends Controller{

        /**
         * Controller constructor
         * 
         * Creates an instance of the View and the Model.
         */
        public function __construct() {
            $this->view = new SecurityView();
            $this->model = new SecurityModel();
        }

        public function displayLoginForm()
        {
            $this->view->displayLoginForm();
        }

        public function login()
        {
            $isLoginCorrect = $this->model->login();
            if ($isLoginCorrect) {
                header("Location: index.php");
            } else {
                $this->view->displayLoginForm();
            }
        }
        
        public function logout()
        {
            $this->model->logout();
            header("Location: index.php");
        }
    }