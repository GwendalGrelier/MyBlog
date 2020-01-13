<?php
    
    class NewsModel extends Model {

        /**
         * Retrieves the list of news from the database
         *
         * @return List $newsList
         */
        public function getNews() {
            $request = "SELECT news.*, category.nom as categoryName, user.username FROM `news` JOIN category on news.category = category.id JOIN user on news.author = user.id ORDER BY post_date DESC";
            $request = $this->connexion->query($request);
            $newsList = $request->fetchAll(PDO::FETCH_ASSOC);
            // var_dump($newsList);
            return $newsList;
        }

        /**
         * Adds an article to the database
         *
         * @return void
         */
        public function addNewArticle() {
            // var_dump($_POST);
            $title = $_POST["title"];
            $description = $_POST["description"];
            $category = $_POST["category"];
            $date = date("Y-m-d");
            $author = $_SESSION["user"]['id'];

            // Check form validity
            $errors = [];
            if (empty($title)) {
                $errors[] = 'title';
            }
            if (empty($description)) {
                $errors[] = 'description';
            }
            if (empty($category)) {
                $errors[] = 'category';
            }
            if (!empty($errors)) {
                $_SESSION['newArticleData']['errors'] = $errors;
                $_SESSION['newArticleData']['$title'] = $title;
                $_SESSION['newArticleData']['$description'] = $description;
                $_SESSION['newArticleData']['$category'] = $category;
                return false;
            }
            
            
            $request = $this->connexion->prepare("INSERT INTO `news` VALUES (null, :title, :description, :category, :date, :author)");
            $request->bindParam(':title', $title);
            $request->bindParam(':category', $category);
            $request->bindParam(':description', $description);
            $request->bindParam(':date', $date);
            $request->bindParam(':author', $author);
            
            $result = $request->execute();
            unset($_SESSION['newArticleData']);
            return true;
        }

        /**
         * Deletes an article from the database
         *
         * @return void
         */
        public function deleteArticle($article_id) {
            $request = $this->connexion->prepare("DELETE FROM `news` WHERE id=:id");
            $request->bindParam(':id', $article_id);
            
            $result = $request->execute();
            // var_dump($result);
        }

        /**
         * Gets an article from the database
         *
         * Gets the ID of the article from URL and searches
         * for a match in the database
         *
         * @return array
         **/
        public function getArticle($id) {


            $request = $this->connexion->prepare("SELECT * FROM news WHERE id=:id");
            $request->bindParam(':id', $id);
            
            $result = $request->execute();
            $article = $request->fetch(PDO::FETCH_ASSOC);
            return $article;
        }

        /**
         * Updates an article to the database
         *
         * 
         **/
        public function editArticle()
        {
            $id = $_POST["id"];
            $title = $_POST["title"];
            $description = $_POST["description"];
            $category = $_POST["category"];


            $request = $this->connexion->prepare("UPDATE `news`
                SET `id`=:id, `title`=:title, `description`=:description, `category`=:category WHERE id=:id");
            $request->bindParam(':id', $id);
            $request->bindParam(':title', $title);
            $request->bindParam(':description', $description);
            $request->bindParam(':category', $category);
            
            $result = $request->execute();
            // var_dump($result);
        }
    }