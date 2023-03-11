<?php

// Kollar om konstant 'My_APP' är definierad
// och om den nuvarande php filen är samma som filen som körs
// Om en fil inte är definierat, så har vi inte tillgång till den. 
//If satsen finns för att kunna ansluta sig till mappen projektet är kopplat till
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)){
    die('This file cannot be accessed directly.');
} 

// Använd "require_once" för att ladda de filer som behövs för klassen
// när en fil är required/included ärver koden variable scope för raden där inkluderingen sker
require_once __DIR__ . "/Database.php";
require_once __DIR__ . "/../models/UserModel.php";

// här har vi en klass som heter UsersDatabase som utökar en annan klass (inheritance) som heter "Database" 
// hämtar properties och metod egenskaperna från Database
// Syftet med denna klass är att använda metoder för att få åtkomst och manipulera data från appar

class UsersDatabase extends Database
{
    private $table_name = "users"; //table
    private $id_name = "user_id"; //id from row

    // get one user by using the inherited function getOneRowByIdFromTable
    public function getOne($user_id) //hämtar det specifika id från raden i users table
    {
        $result = $this->getOneRowByIdFromTable($this->table_name, $this->id_name, $user_id);

        $user = $result->fetch_object('UserModel'); // Konverteras till ett UserModel-Objekt med hjälp av fetch_object()

        return $user;
    }

    // GET all by using inherited function getAllRowsFromTable
    public function getAll() // HTTP GET metoden
    {
        $result = $this->getAllRowsFromTable($this->table_name);

        $users = [];

        while($user = $result->fetch_object('UserModel')){
            $users[] = $user; //objekten är returned som en array så vi kan samla all information
        }
        return $users;
    }

    // CREATE one by creating a query and using the inherited $this->conn 
    public function insert(UserModel $user){ // HTTP POST metod
        $query = "INSERT INTO users (first_name, last_name) VALUES (?, ?)";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ss", $user->first_name, $user->last_name);

        $success = $stmt->execute();

        return $success;
    }

     // UPDATE one by creating a query and using the inherited $this->conn 
     // Raden som ska uppdateras identifieras av dess user_id-fält, som skickas som en separat parameter.
     public function updateUser(UserModel $user, $user_id){ //HTTP PUT metod 
        $query = "UPDATE users SET first_name = ?, last_name = ? WHERE user_id = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ssi", $user->first_name, $user->last_name, $user_id);

        $success = $stmt->execute();

        return $success;
    }

    // DELETE one by using the inherited function deleteOneRowByIdFromTable
    public function deleteById($user_id) //HTTP DELETE metod
    {
        $success = $this->deleteOneRowByIdFromTable($this->table_name, $this->id_name, $user_id);

        return $success;
    }

}

