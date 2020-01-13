<?php

include "Models/CategoryModel.php";
include "Views/CategoryView.php";

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->model = new CategoryModel();
        $this->view = new CategoryView();
    }

    /**
     * Start the main page
     *
     **/
    public function displayHome()
    {
        $catergories = $this->model->getCategories();
        $this->view->displayHome($catergories);
    }

    /**
     * Displays the form to add a new category
     *
     **/
    public function displayCategoryForm()
    {
        $this->view->displayCategoryForm();
    }

    /**
     * Add an article to the database
     *
     * Gets data from the form with POST methods
     * Shoould retrieve values as
     * $_POST['nom']
     * $_POST['description']
     * 
     * @return void
     */
    public function addCategoryDB()
    {
        $this->model->addCategory();
        header("Location: index.php?controller=category");
    }

    /**
     * Displays the form to edit a new category
     *
     **/
    public function editCategoryForm()
    {
        $catergory = $this->model->getCategory();
        $this->view->displayEditCategoryForm($catergory);
    }

    /**
     * Update the DB with an category
     *      
     * Gets data from the form with POST methods
     * Shoould retrieve values as
     * $_POST['id']
     * $_POST['nom']
     * $_POST['description']
     * 
     * @return void
     */
    public function editCategoryDB()
    {
        $this->model->editCategory();
        header("Location: index.php?controller=category");
    }

    /**
     * Deletes an category from the DB
     *      
     * Gets ID from the URL with GET methods
     * 
     * @return void
     */
    public function deleteCategoryDB()
    {
        $this->model->deleteCategory();
        header("Location: index.php?controller=category");
    }
}
