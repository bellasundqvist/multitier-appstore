<?php
// hanterar validering och skapar olika resurser - services och classes kan delas mellan presentationslagret


// Kollar om konstant 'My_APP' är definierad
// och om den nuvarande php filen är samma som filen som körs
// Om en fil inte är definierat, så har vi inte tillgång till den. 
//If satsen finns för att kunna ansluta sig till mappen projektet är kopplat till
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

//Inkluderar filen AppsDatabase som innehåller data access logik. 
require_once __DIR__ . "/../data-access/AppsDatabase.php";

class AppsService{

    // Get one app by creating a database object 
    // from data-access layer and calling its getOne function with the given id.
    public static function getAppById($id){ //Statiska metoder kan kallas på direkt - utan att först skapa en instans av klassen.
        $apps_database = new AppsDatabase();

        $app = $apps_database->getOne($id);

        // If you need to remove or hide data that shouldn't
        // be shown in the API response you can do that here
        // An example of data to hide is users password hash 
        // or other secret/sensitive data that shouldn't be 
        // exposed to users calling the API

        return $app;
    }

    // Get all apps by creating a database object 
    // from data-access layer and calling its getAll function.
    public static function getAllApps(){
        $apps_database = new AppsDatabase();

        $apps = $apps_database->getAll();


        return $apps;
    }

    // Save an app to the database by creating a database object 
    // from data-access layer and calling its insert function.
    public static function saveApp (AppModel $app){
        $apps_database = new AppsDatabase();

        // If you need to validate data or control what 
        // gets saved to the database you can do that here.
        // This makes sure all input from any presentation
        // layer will be validated and handled the same way.

        $success = $apps_database->insert($app); // kallar på insert metoden för att spara ny app

        return $success;
    }

     // Update an app to the database by updating a database object 
    // from data-access layer and calling its updateApp function.
    public static function updateApp (AppModel $app, $id){
        $apps_database = new AppsDatabase();

        $success = $apps_database->updateApp($app, $id);

        return $success;
    }

      // Delete the customer from the database by creating a database object 
    // from data-access layer and calling its delete function.
    public static function deleteAppById($app_id){
        $apps_database = new AppsDatabase();

        $success = $apps_database->deleteById($app_id);

        return $success;
    }
}