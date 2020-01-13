<?php
    
    class SecurityModel extends Model {

        /**
         * Get parameters from the login form and check for
         * DB match
         *
         * If there is a match, the user information is stored in $_SESSION['user']
         * 
         * @return array containing the user data or false
         */
        public function login()
        {
            $username = $_POST["username"];
            $password = $_POST["password"];

            // $request = $this->connexion->prepare("SELECT * FROM user WHERE username=:username and password=:password");
            // $request->bindParam(':username', $username);
            // $request->bindParam(':password', $password);
            
            // $result = $request->execute();
            // $user = $request->fetch(PDO::FETCH_ASSOC);
            // if ($user) {
            //     $_SESSION['user'] = $user;
            // }
            // return $user;
            
            // ? Code version with password Hashing
            $request = $this->connexion->prepare("SELECT user.*, rank.name as rank_name FROM user join rank on user.rank = rank.id WHERE username=:username");
            $request->bindParam(':username', $username);
            
            $result = $request->execute();
            $user = $request->fetch(PDO::FETCH_ASSOC);
            
            
            // verify Password
            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
                return $user;
            } else {
                return false;
            }

        }

        /**
         * User logout
         *
         * Delete the $_SESSION["user"]
         * 
         * @return void
         */
        public function logout()
        {
            unset($_SESSION['user']);
        }
        
    }