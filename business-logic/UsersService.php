<?php
// hanterar validering och skapar olika resurser - services och classes kan delas mellan presentationslagret

// Kollar om konstant 'My_APP' är definierad
// och om den nuvarande php filen är samma som filen som körs
// Om en fil inte är definierat, så har vi inte tillgång till den. 
//If satsen finns för att kunna ansluta sig till mappen projektet är kopplat till
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

//Inkluderar filen UsersDatabase som innehåller data access logik. 
require_once __DIR__ . "/../data-access/UsersDatabase.php";

class UsersService{

    // Get one user by creating a database object 
    // from data-access layer and calling its getOne function with the given id.
    public static function getUserById($id){ //Statiska metoder kan kallas på direkt - utan att först skapa en instans av klassen.
        $users_database = new UsersDatabase();

        $user = $users_database->getOne($id);

        // If you need to remove or hide data that shouldn't
        // be shown in the API response you can do that here
        // An example of data to hide is users password hash 
        // or other secret/sensitive data that shouldn't be 
        // exposed to users calling the API

        return $user;
    }

    // Get all users by creating a database object 
    // from data-access layer and calling its getAll function.
    public static function getAllUsers(){ 
        $users_database = new UsersDatabase();

        $users = $users_database->getAll();

        return $users;
    }

    // Save a user to the database by creating a database object 
    // from data-access layer and calling its insert function.
    public static function saveUser(UserModel $user){
        $users_database = new UsersDatabase();

        // If you need to validate data or control what 
        // gets saved to the database you can do that here.
        // This makes sure all input from any presentation
        // layer will be validated and handled the same way.

        $success = $users_database->insert($user); // kallar på insert metoden för att spara ny app

        return $success;
    }

    // Update a user to the database by updating a database object 
    // from data-access layer and calling its insert function.
    public static function updateUser (UserModel $user, $id){
        $users_database = new UsersDatabase();

        $success = $users_database->updateUser($user, $id);

        return $success;
    }

      // Delete the user from the database by creating a database object 
    // from data-access layer and calling its delete function.
    public static function deleteUserById($user_id){
        $users_database = new UsersDatabase();

        $success = $users_database->deleteById($user_id);

        return $success;
    }
}