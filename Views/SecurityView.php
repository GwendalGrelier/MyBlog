<?php


class SecurityView extends View
{

    public function displayLoginForm()
    {
        $this->page .= "<h1> Login </h1>";
        $this->page .= file_get_contents("pages/loginForm.html");
        
        $this->page = str_replace("{action}", "login", $this->page);

        $this->displayPage();
    }

}
