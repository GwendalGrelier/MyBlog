<?php
    
    class UserModel extends Model {




        /**
         * Adds a new user to the database
         * 
         * Check for errors for the password
         * If there are errors, valid data are stored in $_SESSION as well
         * as the errors
         * The form is then displayed again with valid input filled in
         * and error messages displayed
         *
         * If there are errors, False is retruned, the Controler should start again.
         * 
         * @return bool
         */
        public function addNewUser() {
            $name = $_POST["name"];
            $firstname = $_POST["firstname"];
            $username = $_POST["username"];
            $password = $_POST["password"];
            $password_check = $_POST["password_check"];
            $avatar = ($_POST["avatar"] != "" ? $_POST["avatar"] : "img/default-user-image.png");
            $quote = $_POST["quote"];
            $date = date("Y-m-d");

            $rank = "3"; // define rank as `user` for every new user

            // Check for errors
            $errors = [];
            if ($password != $password_check) {
                $errors[] = "password";    
            }

            // If Errors store values in $_SESSION
            // Else, proceed to the DB request
            if ($errors) {
                $_SESSION['newUserData']['username'] = $username;
                $_SESSION['newUserData']['name'] = $name;
                $_SESSION['newUserData']['firstname'] = $firstname;
                $_SESSION['newUserData']['password'] = $password;
                $_SESSION['newUserData']['quote'] = $quote;
                $_SESSION['newUserData']['errors'] = $errors;

                if (in_array('password', $errors)) {
                    $_SESSION['newUserData']['password'] = "";
                }
                return false; 
            }

            // Password crypting
            $password = password_hash($password, PASSWORD_BCRYPT);

            $request = $this->connexion->prepare("INSERT INTO `user` VALUES (null, :name, :firstname, :username, :password, :avatar, :date, :quote, :rank)");
            $request->bindParam(':name', $name);
            $request->bindParam(':firstname', $firstname);
            $request->bindParam(':username', $username);
            $request->bindParam(':password', $password);
            $request->bindParam(':avatar', $avatar);
            $request->bindParam(':date', $date);
            $request->bindParam(':quote', $quote);
            $request->bindParam(':rank', $rank);

            $result = $request->execute();
            unset($_SESSION["newUserData"]);
            return true;
        }

        /**
         * Deletes a user from the database
         *
         * @return void
         */
        public function deleteUser($id) {
            
            $request = $this->connexion->prepare("DELETE FROM `user` WHERE id=:id");
            $request->bindParam(':id', $id);
            
            $result = $request->execute();
            // var_dump($result);
        }

        /**
         * Gets a user from the database
         *
         * Gets the ID of the user from URL and searches
         * for a match in the database
         *
         * @return array
         **/
        public function getUser() {
            $id = $_GET["user"];

            $request = $this->connexion->prepare("SELECT * FROM user WHERE id=:id");
            $request->bindParam(':id', $id);
            
            $result = $request->execute();
            $user = $request->fetch(PDO::FETCH_ASSOC);
            return $user;
        }

        /**
         * Updates an article to the database
         *
         * 
         **/
        public function editUser()
        {
            $id = $_POST["id"];
            $nom = $_POST["name"];
            $prenom = $_POST["firstname"];
            $username = $_POST["username"];
            $quote = $_POST["quote"];

            if (!empty($_POST["rank"])) {
                $rank = $_POST["rank"];
            } else {
                $rank = "3";
            }
            

            $request = $this->connexion->prepare("UPDATE `user`
                SET `id`=:id, `nom`=:nom, `prenom`=:prenom, 
                    `username`=:username, `quote`=:quote, `rank`=:rank 
                    WHERE id=:id");
            $request->bindParam(':id', $id);
            $request->bindParam(':nom', $nom);
            $request->bindParam(':prenom', $prenom);
            $request->bindParam(':username', $username);
            $request->bindParam(':quote', $quote);
            $request->bindParam(':rank', $rank);
            
            $result = $request->execute();

        }

        public function editPassword()
        {
            $password = $_POST["password"];
            $password_check = $_POST["password_check"];
            //  Todo finish this
            // Check for errors
            $errors = [];
            if ($password != $password_check) {
                $errors[] = "password";    
            }
        }
    }