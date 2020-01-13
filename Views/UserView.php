<?php


class UserView extends View
{

    /**
     * Creates the Welcome page 
     * 
     * Contains the list of articles from the database
     *
     * @param List $userList
     * @return void
     */
    public function displayHome($userList)
    {
        $this->page .= "<h1> Bienvenue sur la page User </h1>";
        $this->page .= "<table class='table'><thead>";
        $this->page .= "<tr class='sticky_custom'><th scope='col'>ID</th><th scope='col'>Username</th><th scope='col'>Rank</th><th scope='col'>Name</th>";
        $this->page .= "<th scope='col' class='text-center col-1'>Avatar</th><th scope='col'>Creation Date</th><th scope='col' class='col-2' >Quote</th><th scope='col'><a href='index.php?action=addForm&controller=user'><button class='btn btn-primary'>Add New User</button></a></th></tr></thead>";
        $this->page .= "<tbody>";
        $user_text = [];
        foreach ($userList as $user) {
            
            if ($user['id'] == $_SESSION['user']['id']) {
                $text_list_to_add = "<tr class='text-light bg-secondary font-weight-bold'>";
            } else {
                $text_list_to_add = "<tr>";
            }
            // Main content
            $text_list_to_add .= "<th>" . $user["id"] . "</th>";
            if ($user['id'] == $_SESSION['user']['id']) {
                $text_list_to_add .= "<td><a class='text-light' href='index.php?controller=user&action=displayUserPage&user=" .$user['id']. "'>" . $user["username"] . "</a></td>";
            } else {
                $text_list_to_add .= "<td><a href='index.php?controller=user&action=displayUserPage&user=" .$user['id']. "'>" . $user["username"] . "</a></td>";
            }
            $text_list_to_add .= "<td>" . $user["rank_name"] . "</td>";
            $text_list_to_add .= "<td>" . $user["nom"] . " ". $user["prenom"] . "</td>";
            $text_list_to_add .= "<td class='text-center'><img class='avatar'  src='img/default-user-image.png'></td>";
            $text_list_to_add .= "<td>" . $user["creation_date"] . "</td>";
            $text_list_to_add .= "<td>" . $user["quote"] . "</td>";
            
            if ($_SESSION['user']['rank'] < $user['rank'] || $user['id'] == $_SESSION['user']['id']) { // If user has a higher rank than the user, delete button is shown
                $text_list_to_add .= "<td>"
                    ."<a href='index.php?controller=user&action=updateUserForm&user=" . $user['id']."'class='btn btn-success'>Edit</a>";
                $text_list_to_add .= "<a href='index.php?controller=user&action=deleteUser&user=" . $user['id']."' class='btn btn-danger ml-2'>Delete</a></td>";
            }

            $text_list_to_add .= "</tr>";
            $user_text[] = $text_list_to_add;
        }

        foreach ($user_text as $user_line) {
            $this->page .= $user_line;
        }
        $this->page .= "</tbody>";
        $this->page .= "</table>";
        $this->displayPage();
    }

    public function displayUserPage($user, $articles)
    {   
        $post_number = count($articles);
        $this->page .= "<h1 class='text-center'>" .$user["username"]. "</h1>";
        $this->page .= file_get_contents("pages/userProfile.html");
        
        if ($_SESSION['user']['rank'] < $user['rank'] || $_SESSION['user']['id'] == $user['id']) {
            $this->page = str_replace("{modify_user}", "<a href='index.php?controller=user&action=updateUserForm&user=". $user['id'] ."'>Edit Profile</a>", $this->page);
        } else {
            $this->page = str_replace("{modify_user}", "", $this->page);
        }

        $this->page = str_replace("{avatar}", $user['avatar'], $this->page);
        $this->page = str_replace("{username}", $user['username'], $this->page);
        $this->page = str_replace("{name}", $user['nom'], $this->page);
        $this->page = str_replace("{firstname}", $user['prenom'], $this->page);
        $this->page = str_replace("{quote}", $user['quote'], $this->page);
        $this->page = str_replace("{post_number}", $post_number, $this->page);
        $this->page = str_replace("{creation_date}", $user['creation_date'], $this->page);
        
        $articles_text = (count($articles) > 0) ? "" : "No Article Found For This User" ;
        foreach ($articles as $article) {
            $articles_text .= $this->buildArticleText($article);
        }
        $this->page = str_replace("{articleList}", $articles_text, $this->page);

        $this->displayPage();
    }

    /**
     * Adds the conponents for the add article form
     *
     * @return void
     */
    public function displayAddUserForm()
    {
        $this->page .= "<h1> Ajout d'un nouvel Utilisateur </h1>";
        $this->page .= file_get_contents("pages/userForm.html");

        // Exectute this part if there has been errors in the form
        if (isset($_SESSION) && !empty($_SESSION["newUserData"])) {
            $username = $_SESSION['newUserData']['username'];
            $name = $_SESSION['newUserData']['name'];
            $firstname = $_SESSION['newUserData']['firstname'];
            $password = $_SESSION['newUserData']['password'];
            $quote = $_SESSION['newUserData']['quote'];
            $errors = $_SESSION['newUserData']['errors'];
            
            $this->page = str_replace("{username}", $username, $this->page);
            $this->page = str_replace("{name}", $name, $this->page);
            $this->page = str_replace("{firstname}", $firstname, $this->page);
            $this->page = str_replace("{password}", $password, $this->page);
            $this->page = str_replace("{quote}", $quote, $this->page);
            
            if (in_array('password', $errors)) {
                $text = '<div class="alert alert-danger col-12" role="alert">Les mots de passe ne correspondent pas !</div>';
                $this->page = str_replace("{error_password}", $text, $this->page);
            }
        }
        // Stop errors code

        $this->page = str_replace("{action}", "addNewUser", $this->page);
        $this->page = str_replace("{display}", "hidden", $this->page);
        $this->page = str_replace("{username}", "", $this->page);
        $this->page = str_replace("{name}", "", $this->page);
        $this->page = str_replace("{firstname}", "", $this->page);
        $this->page = str_replace("{password}", "", $this->page);
        $this->page = str_replace("{quote}", "", $this->page);
        $this->page = str_replace("{rank_select}", "", $this->page);
        
        $this->page = str_replace("{password_protection}", "", $this->page);

        $text = "<input class='col-md-9 form-control mt-1' id='password_check' name='password_check' type='password' placeholder='Repeat your password'>";
        $this->page = str_replace("{password_complement}", $text, $this->page);        
           

        $this->page = str_replace("{error_password}", "", $this->page);

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
    public function displayEditUserForm($user, $ranks) {
        $id = $user['id'];
        $username = $user['username'];
        $name = $user['nom'];
        $firstname = $user['prenom'];
        $password = $user['password'];
        $quote = $user['quote'];
        $avatar = $user['avatar'];
        $creation_date = $user['creation_date'];

        $this->page .= file_get_contents("pages/userForm.html");
        $this->page = str_replace("{action}", "editUser", $this->page);
        $this->page = str_replace("{display}", "", $this->page); // Shows the ID
        $this->page = str_replace("{id}", $id, $this->page); 
        $this->page = str_replace("{username}", $username, $this->page); 
        $this->page = str_replace("{name}", $name, $this->page); 
        $this->page = str_replace("{firstname}", $firstname, $this->page); 
        $this->page = str_replace("{password}", "**********", $this->page); 
        $this->page = str_replace("{quote}", $quote, $this->page); 
        $this->page = str_replace("{creation_date}", $creation_date, $this->page); 
        $this->page = str_replace("{password_protection}", "readonly", $this->page); 
        
        $password_complement = "";
        if ($_SESSION['user']['id'] == $user['id']) {
            $password_complement = "<a class='col-md-3 form-control mt-1 btn btn-light ml-auto border' href='index.php?controller=user&action=displayPasswordForm'>Changer de mot de passe</a>";
        }
        $this->page = str_replace("{password_complement}", $password_complement, $this->page);        
        $this->page = str_replace("{error_password}", "", $this->page); 
        
        
        // if admin is logged in, the rank selection is displayed
        if ($_SESSION['user']['rank'] == 1) {
            $text = "";
            $text .= "<select class='custom-select col-4' name='rank'>";
            foreach ($ranks as $rank) {
                $select = "";
                if ($user["rank"] == $rank["id"]) {
                    $select = "selected";
                }
                $text .= "<option value='".$rank["id"]."'" .$select. ">".$rank["name"]."</option>";    
            }
            $text .= "</select>";
            $this->page = str_replace("{rank_select}", $text, $this->page); 
        } else {
            $text = "<select class='custom-select col-4' name='rank'>";
            foreach ($ranks as $rank) {
                if ($user["rank"] == $rank['id']) {
                    $text .= "<option value='".$user["rank"]."' selected readonly>".$rank["name"]."</option>";    
                }
            }
            $text .= "</select>";
            $this->page = str_replace("{rank_select}", $text, $this->page); 
        }

        $this->displayPage();
    }

    /**
     * Builds the text for an article
     *
     * @param array $article
     * @return string containing the HTML for on article
     */
    private function buildArticleText($article) {
        $text = "<div class='row mb-5'>";
        $text .= "<div class='col-12'>";
        $text .= "<h2 class='text-center'><u>". $article['title'] ."</u></h2>";
        $text .= "<h4 class='text-right mr-5'><em>". $article['category_name'] ."</em></h4>";
        $text .= "<div class='text-justify p-2'>". $article['description'] ."</div>";
        $text .= "<p class='text-right'>". $article['post_date'] ."</p>";
        
        $text .= "</div>";
        $text .= "</div>";
        $text .= "<hr class='my-4'>";

        return $text;
    }
}
