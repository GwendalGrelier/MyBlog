<?php
    
    abstract class View {

        protected $page;

        /**
         * View constructor
         */
        public function __construct() {
        
            $this->page = file_get_contents('pages/parts/head.html');
            $this->page .= file_get_contents('pages/parts/nav.html');

            // Build menu links
            $links = [];
            if (isset($_SESSION) && !empty($_SESSION["user"]) && $_SESSION['user']['rank'] < 3) {
                $links[] = array("active" => "category", "link" => "index.php?controller=category", "name" => "Categories");
                $links[] = array("active" => "user", "link" => "index.php?controller=user", "name" => "Users");
            }
            $links_text = "";
            foreach ($links as $link) {
                $links_text .= "<li class='nav-item'><a class='nav-link {active-" .$link['active']. "}' "
                ."href='" .$link['link']. "'>" .$link['name']. "</a></li>";
            }
            $this->page = str_replace("{nav_links}", $links_text, $this->page);

            // Set the login/logout btn
            if (isset($_SESSION) && !empty($_SESSION["user"])) {
                $text = "<p class='display-5 text-light ml-auto mt-3 mr-3'>Logged in as : <a href=index.php?controller=user&action=displayUserPage&user=". $_SESSION['user']['id'] .">" . $_SESSION['user']["username"] ." (" . $_SESSION['user']['rank_name'] . ")</a></p><a href='index.php?action=logout&controller=security'>Logout</a>";
            } else {
                $text = "<a class='ml-auto' href='index.php?action=displayLoginForm&controller=security'>Login</a>";
            }
            $this->page = str_replace("{connexionBtn}", $text, $this->page);


            // Set active class for the nav
            // ! Has to be set at the end of the constructor to avoir overwriting tags !
            $query_string = $_SERVER["QUERY_STRING"];
            if (preg_match("/controller=([a-z]+)/", $query_string, $matches)) {
                $result = $matches[1];
            } else {
                $result = 'news';
            }
            $matched_str = '{active-' .$result .'}';
            $this->page = str_replace($matched_str, "active", $this->page);
            $this->page = preg_replace("/{active-[a-z]+}/", "", $this->page);



            // if (preg_match_all("/controller\=([a-z]+)&?/", $query_string, $matches)) {
            //     $result = $matches[1][0];
            // } else {
            //     $result = "news";
            // }
            // $matched_str = "{" . $result . "}";
            // $this->page = str_replace($matched_str, "active", $this->page);
            // $this->page = preg_replace("/{.+}/", "", $this->page);
            


        }
        
        /**
         * Display the content of the page
         * Single echo of the view
         * 
         * @return void
         */
        protected function displayPage()
        {
            $this->page .= file_get_contents("pages/parts/footer.html");
            echo $this->page;
        }
    }