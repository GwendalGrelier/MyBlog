<?php
    

    /**
     * undocumented class
     */
    class CategoryView extends View {

        public function displayHome($categories) {
            $this->page .= "<h1>Welcome Here</h1>";
            $this->page .= "<p><a href='index.php?action=displayCategoryForm&controller=category' class='btn btn-primary'>Add Category</a></p>";
            
            $this->page .= "<ul class='list-group'>";
            foreach ($categories as $catergory) {
                
                $this->page .= "<li class='list-group-item row d-flex align-items'>";
                $this->page .= "<div><h3>". $catergory["nom"] ."</h3>";

                $this->page .= "<p>". $catergory["description"] ."</p></div>";
        
                $this->page .= "<div class='col-5 ml-auto'>"
                                ."<a href='index.php?controller=category&action=editCategoryForm&category=" . $catergory['id'] 
                                ."'class='btn btn-success'>Edit</a>"
                                ."<a href='index.php?controller=category&action=deleteCategoryDB&category=" . $catergory['id'] 
                                ."' class='btn btn-danger ml-2'>Delete</a></div>";
                
                $this->page .= "</li>";
            }
            $this->page .= "</ul>";
            $this->displayPage();
        }
        
        /**
         * Displays the form to add a new category
         *
         * @return void
         */
        public function displayCategoryForm() {
            $this->page .= "<h1> Ajout d'une nouvelle Catégorie </h1>";
            $this->page .= file_get_contents("pages/categoryForm.html");
            $this->page = str_replace("{action}", "addCategoryDB", $this->page);
            $this->page = str_replace("{hidden}", "hidden", $this->page);
            $this->page = str_replace("{nom}", "", $this->page);
            $this->page = str_replace("{description}", "", $this->page);
            $this->displayPage();
        }

        /**
         * Display the form to edit a category
         * 
         * Gets data from the catagory passed as an argument
         * Displays the form with the corresponding values
         *
         * @param array $category
         * @return void
         */
        public function displayEditCategoryForm($category)
        {
            $id = $category['id'];
            $nom = $category['nom'];
            $description = $category['description'];

            $this->page .= "<h1> Edition d'une Catégorie </h1>";
            $this->page .= file_get_contents("pages/categoryForm.html");
            $this->page = str_replace("{action}", "editCategoryDB", $this->page);
            $this->page = str_replace("{hidden}", "", $this->page);
            $this->page = str_replace("{id}", $id, $this->page);
            $this->page = str_replace("{nom}", $nom, $this->page);
            $this->page = str_replace("{description}", $description, $this->page);
            $this->displayPage();
        }
    }
    

