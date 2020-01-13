<?php

    include "Views/View.php";
    include "Models/Model.php";
    include "Controllers/Controller.php";
    include "Controllers/CategoryController.php";
    include "Controllers/NewsController.php";
    include "Controllers/UserController.php";
    include "Controllers/SecurityController.php";

    session_start();


    // Get class controller list
    $controller_list = [];
    foreach (get_declared_classes() as $class) {
        if (preg_match("/^([A-Za-z]+)Controller$/", $class, $match)) {
            $controller_list[] = [
                "className" => $match[0],
                "name" => strtolower($match[1])
            ];
        }
    }
    
    // Get controller/action table
    for ($i=0; $i < count($controller_list); $i++) { 
        $controller_list[$i]['methodList'] = get_class_methods($controller_list[$i]["className"]);
    }
    // var_dump($controller_list);
        
    // $controller_list should contain data as such:
    // array (size=3)
    //   'className' => string 'NewsController'
    //   'name' => string 'news'
    //   'methodList' => 
    //     array
    //       0 => string '__construct'
    //       1 => string 'displayHome' (length=11)
    //       2 => string 'addForm' (length=7)
    //       3 => string 'addNewArticle' (length=13)
    //       4 => string 'updateForm' (length=10)
    //       5 => string 'editArticle' (length=11)
    //       6 => string 'deleteArticle' (length=13)

// haha
    // Get requested controller
    // Default is NewsController
    $requested_controller = "NewsController";
    if (isset($_GET) && !empty($_GET["controller"])) {
        foreach ($controller_list as $controller) {
            if ($_GET["controller"] == $controller['name']) {
                $requested_controller = $_GET["controller"] . "Controller";
                $requested_controller = ucfirst($requested_controller);
            }
        }
    }

    // Get requested action
    $action = "displayHome";
    if (isset($_GET) && !empty($_GET["action"])) {
        foreach ($controller_list as $controller) {
            if (in_array($_GET["action"], $controller['methodList'])) {
                $action = $_GET["action"];
            }
        }      
    }
    
    
    // Set authorized combinations for 
    // non-logged in users
    $anonymous_user_actions = array(
        "NewsController" => ["displayHome"],
        "SecurityController" => ["displayLoginForm", "login"],
        "UserController" => ["displayUserPage"]    
    );
    if (!isset($_SESSION) || empty($_SESSION["user"])) { // If user is not logged in
        if (!in_array($requested_controller, array_keys($anonymous_user_actions) )){ //If requested not authorized
            $requested_controller = "NewsController";
            $action = "displayHome";
        } elseif (!in_array($action, $anonymous_user_actions[$requested_controller])) {
            $action = $anonymous_user_actions[$requested_controller][0];
        }
    }
    
    // Set Authorized combinations for 
    // simple users
    $simple_user_actions = array(
        "NewsController" => ["displayHome", "addForm", "addNewArticle", "updateForm", "editArticle", "deleteArticle"],
        "SecurityController" => ["displayLoginForm", "login", "logout"],    
        "UserController" => ["displayHome", "displayUserPage", "updateUserForm", "editUser", "deleteUser"]  
    );
    if (isset($_SESSION) && !empty($_SESSION["user"]) && $_SESSION['user']['rank'] == 3) { // If user is simple user
        if (!in_array($requested_controller, array_keys($simple_user_actions) )){ //If requested not authorized
            $requested_controller = "NewsController";
            $action = "displayHome";
        } elseif (!in_array($action, $simple_user_actions[$requested_controller])) {
            // $$requested_controller = "NewsController";
            // $action = "displayHome";

            // if action not authorized, set action to default value for this controller
            $action = $simple_user_actions[$requested_controller][0];
        }
    }
    
   

    $controller = new $requested_controller();
    $controller->$action();





