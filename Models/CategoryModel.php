<?php
    
    class CategoryModel extends Model {

       

        /**
         * Gets one category from DB
         * 
         * Retireves on category matching the desired id
         * Gets the ID from URL through the GET methods
         * Should then display the form for this entry
         *
         * @return array
         */ 
        public function getCategory()
        {
            $id = $_GET['category'];

            $request = $this->connexion->prepare("SELECT * FROM category WHERE id=:id");
            $request->bindParam(':id', $id);
            $result = $request->execute();
            $category = $request->fetch(PDO::FETCH_ASSOC);
            return $category;
        }

        /**
         * Adds a category to the DB
         *
         * @return void
         */
        public function addCategory() {
            $nom = $_POST["nom"];
            $description = $_POST["description"];
            
            $request = $this->connexion->prepare("INSERT INTO `category` VALUES (NULL, :nom, :description)");
            $request->bindParam(':nom', $nom);
            $request->bindParam(':description', $description);
            
            $result = $request->execute();
        }

        /**
         * Deletes a category from the DB
         * 
         * Gets the desired ID from the URL with the GET method
         *
         * @return void
         */
        public function deleteCategory()
        {
            $id = $_GET['category'];

            $request = $this->connexion->prepare("DELETE FROM category WHERE id=:id");
            $request->bindParam(':id', $id);
            
            $result = $request->execute();
        }

        /**
         * Update one DB category
         * 
         * Gets values form the form through POST methods as
         * $_POST["id"]
         * $_POST["nom"]
         * $_POST["description"]
         *
         * @return void
         */
        public function editCategory()
        {
            $id = $_POST["id"];
            $nom = $_POST["nom"];
            $description = $_POST["description"];

            $request = $this->connexion->prepare("UPDATE `category`
                SET `id`=:id, `nom`=:nom, `description`=:description WHERE id=:id");
            $request->bindParam(':id', $id);
            $request->bindParam(':nom', $nom);
            $request->bindParam(':description', $description);
            
            $result = $request->execute();
        }

    }