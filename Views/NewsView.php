<?php
    


    class NewsView extends View {


        /**
         * Creates the Welcome page 
         * 
         * Contains the list of articles from the database. 
         *
         * @param List $newsList
         * @return void
         */
        public function displayHome($newsList) {
            $this->page .= "<h1> Bienvenue </h1>";
            if (isset($_SESSION) && !empty($_SESSION["user"])) {
                $this->page .= "<p><a href='index.php?action=addForm'><button class='btn btn-primary'> Ajouter un article</button></a></p>";
            }

            $this->page .= "<ul class='list-group'>";
            foreach ($newsList as $news) {
                $this->page .= "<li class='list-group-item row d-flex align-items-center'>";

                // Main content
                $this->page .= "<div class='col-6'><h3>". $news["title"] ."</h3>";
                $this->page .= "<h5 class='categoryName'>". $news["categoryName"] ."</h5>";
                $description = $news['description'];
                $this->page .= "<p class='description hidenContent'>". $description ."</p><p class='showMoreBtn hiddenBtn'>Show More...</p></div>";   

                // User
                $this->page .= "<div class='card col-2 offset-1'>"
                            ."<img class='card-img-top col-12 mt-2' src='img/default-user-image.png'>"
                            ."<div class='card-body'>"
                            ."<h5 class='card-title text-center'><a href='index.php?controller=user&action=displayUserPage&user=". $news['author'] ."'>" . $news["username"]
                            ."</a></h5>"
                            ."<p class='text-center mb-0'>" . $news['post_date'] . "</p>"
                            ."</div>"
                            ."</div>";

                // Buttons
                if (isset($_SESSION) && !empty($_SESSION["user"])) {
                    // Display buttons if user is admin or moderator
                    // Or if the article belongs to the user
                    if ($_SESSION['user']['rank'] < 3 || $_SESSION['user']['id'] == $news['author']) {
                        $this->page .= "<div class='col-3 ml-auto'>"
                                    ."<a href='index.php?action=updateForm&article=" . $news['id'] 
                                    ."'class='btn btn-success'>Edit</a>"
                                    ."<a href='index.php?action=deleteArticle&article=" . $news['id'] 
                                    ."' class='btn btn-danger ml-2'>Delete</a></div>";
                    }
                         
                }
                $this->page .= "</li>";
            }
            $this->page .= "</ul>";
            $this->displayPage();
        }

        /**
         * Adds the conponents for the add article form
         *
         * @return void
         */
        public function displayForm($categories){
            $this->page .= "<h1> Ajout d'un nouvel Article </h1>";
            $this->page .= file_get_contents("pages/newsForm.html");

            if (isset($_SESSION) && !empty($_SESSION["newArticleData"])) {
                $errors = $_SESSION['newArticleData']['errors'];
                $title = $_SESSION['newArticleData']['$title'];
                $description = $_SESSION['newArticleData']['$description'];
                $selected_category = $_SESSION['newArticleData']['$category']; 
                
                // Display errors
                $text = "";
                if (in_array('title', $errors)) {
                    $text = '<div class="alert alert-danger col-12" role="alert">Veuillez renseigner un titre !</div>';
                } 
                $this->page = str_replace("{error_title}", $text, $this->page);
                
                $text = "";
                if (in_array('description', $errors)) {
                    $text = '<div class="alert alert-danger col-12" role="alert">Veuillez remplir le contenu de l\'article !</div>';
                }
                $this->page = str_replace("{error_description}", $text, $this->page);
                
                $text = "";
                if (in_array('category', $errors)) {
                    $text = '<div class="alert alert-danger col-12" role="alert">Veuillez choisir une catégorie !</div>';
                }
                $this->page = str_replace("{error_category}", $text, $this->page);

            } else {
                $title = "";
                $description = "";
                $selected_category = ""; 
            }

            $this->page = str_replace("{action}", "addNewArticle", $this->page);
            $this->page = str_replace("{display}", "hidden", $this->page); // Hides the ID
            $this->page = str_replace("{title}", $title, $this->page); // Hides the ID
            $this->page = str_replace("{description}", $description, $this->page); // Hides the ID
            $this->page = str_replace("{authorModification}", "", $this->page); // Hides the author
            
            $this->page = str_replace("{error_title}", "", $this->page); // Hides the author
            $this->page = str_replace("{error_category}", "", $this->page); // Hides the author
            $this->page = str_replace("{error_description}", "", $this->page); // Hides the author

            $categoriesOptions = "<option disabled selected>Choisissez une categorie</option>\n";
            foreach ($categories as $category) {
                $selected = "";
                if ($category['id'] == $selected_category) {
                    $selected = "selected";
                }
                $categoriesOptions .= "<option value='" . $category["id"] . "'". $selected .">" . $category["nom"] . "</option>\n";
            }
            
            $this->page = str_replace("{categoryOptions}", $categoriesOptions, $this->page); 
            $this->displayPage();
        }

        /**
         * Adds the form to edit an article
         *
         * Displays information form the article
         *
         * @param array $article Contains data from database for the 
         * selected aticle
         **/
        public function displayEditArticle($article, $categories, $users) {
            $id = $article['id'];
            $title = $article['title'];
            $description = $article['description'];

            $this->page .= file_get_contents("pages/newsForm.html");
            $this->page = str_replace("{action}", "editArticle", $this->page);
            $this->page = str_replace("{display}", "", $this->page); // Shows the ID
            
            $categoriesOptions = "";
            foreach ($categories as $category) {
                $selected = "";
                if ($category['id'] == $article["category"]) {
                    $selected = "selected";  
                } 
                $categoriesOptions .= "<option value='" . $category["id"] . "' ".  $selected .">" . $category["nom"] . "</option>\n";
            }
            
            
            if ($_SESSION['user']['rank'] == 1) {
                $text = "<div class='form-group row'><label class='col-md-3 col-form-label' for='author'>Auteur :</label>";
                $text .= "<select id='author' class='custom-select col-md-9' name='author'>";
                foreach ($users as $user) {
                    $selected = "";
                    if ($user['id'] == $article['author']) {
                        $selected = "selected";
                    }
                    $text .= "<option value='". $user['id'] ."'". $selected .">". $user['username'] ."</option>";
                }
                $text .= "</select></div>";
                $this->page = str_replace("{authorModification}", $text, $this->page); 
            } else {
                $this->page = str_replace("{authorModification}", "", $this->page); 
            }

            $this->page = str_replace("{categoryOptions}", $categoriesOptions, $this->page); 
            $this->page = str_replace("{id}", $id, $this->page);
            $this->page = str_replace("{title}", $title, $this->page);
            $this->page = str_replace("{description}", $description, $this->page);
            $this->displayPage();
        }

    }