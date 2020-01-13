<?php


abstract class Model
{
    const SERVER = 'localhost';
    const USER = 'root';
    const PASSWORD = '';
    const BASE = 'testajax';

    // const SERVER = 'sqlprive-pc2372-001.privatesql.ha.ovh.net:3306';
    // const USER = 'cefiidev957';
    // const PASSWORD = '4iC9Ze6t';
    // const BASE = 'cefiidev957';

    protected $connexion;

    public function __construct()
    {
        //connexion
        try {
            $this->connexion = new PDO("mysql:host=" . self::SERVER . ";dbname=" . self::BASE, self::USER, self::PASSWORD);
            $this->connexion->exec("SET NAMES 'UTF8'");
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }

    /**
     * Gets all categoies from the DB
     *
     * Yields an array of DB entries with following properties,
     * cat['id']
     * cat['nom']
     * cat['description']
     * 
     * @return array
     */
    public function getCategories()
    {
        $request = "SELECT * FROM category";
        $request = $this->connexion->query($request);
        $categories = $request->fetchAll(PDO::FETCH_ASSOC);
        return $categories;
    }

    /**
     * Gets all rank from the DB
     *
     * @return array 
     */
    public function getRanks()
    {
        $request = "SELECT * FROM rank";
        $request = $this->connexion->query($request);
        $ranks = $request->fetchAll(PDO::FETCH_ASSOC);
        return $ranks;
    }

    /**
     * Retrieves the list of news from the database
     *
     * @return List $userList
     */
    public function getUsers()
    {
        $request = "SELECT user.*, rank.name as rank_name FROM `user` join rank on user.rank = rank.id order by rank.id";
        $request = $this->connexion->query($request);
        $newsList = $request->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($newsList);
        return $newsList;
    }

    public function getArticleList($userID)
    {
        $request = $this->connexion->prepare("SELECT news.*, category.nom as category_name FROM news JOIN category ON news.category = category.id WHERE author=:userID");
        $request->bindParam(':userID', $userID);
        
        $result = $request->execute();
        $news = $request->fetchAll(PDO::FETCH_ASSOC);
        return $news;
    }
}
