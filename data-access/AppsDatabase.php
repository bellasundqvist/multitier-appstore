<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

// Use "require_once" to load the files needed for the class

require_once __DIR__ . "/Database.php";
require_once __DIR__ . "/../models/AppModel.php";  // Måste jag lägga till UserModel också här?



class AppsDatabase extends Database
{
    private $table_name = "apps";

    // Get one customer by using the inherited function getOneRowByIdFromTable
    public function getOne($app_id)
    {
        $result = $this->getOneRowByIdFromTable($this->table_name, 'app_id', $app_id);

        $app = $result->fetch_object("AppModel");

        return $app;
    }


    // Get all customers by using the inherited function getAllRowsFromTable
    public function getAll()
    {
        $result = $this->getAllRowsFromTable($this->table_name);

        $apps = [];

        while($app = $result->fetch_object("AppModel")){
            $apps[] = $app;
        }

        return $apps;
    }

    // Create one by creating a query and using the inherited $this->conn 
    public function insert(AppModel $app){
        $query = "INSERT INTO apps (app_name, description, price) VALUES (?, ?)";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ssi", $app->app_name, $app->description, $app->price);

        $success = $stmt->execute();

        return $success;
    }

    // Create all by creating a query and using the inherited $this->conn 
    public function select(AppModel $app){
        $query = "SELECT * FROM apps";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ssi", $app->app_name, $app->description, $app->price);

        $success = $stmt->execute();

        return $success;
    }
}
