<?php

// Kollar om konstant 'My_APP' är definierad
// och om den nuvarande php filen är samma som filen som körs
// Om en fil inte är definierat, så har vi inte tillgång till den. 
//If satsen finns för att kunna ansluta sig till mappen projektet är kopplat till
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

// Använd "require_once" för att ladda de filer som behövs för klassen
// när en fil är required/included ärver koden variable scope för raden där inkluderingen sker

require_once __DIR__ . "/../data-access/Database.php";
require_once __DIR__ . "/../models/AppModel.php";  


// här har vi en klass som heter AppsDatabase som utökar en annan klass (inheritance) som heter "Database" 
// hämtar properties och metod egenskaperna från Database
// Syftet med denna klass är att använda metoder för att få åtkomst och manipulera data från appar

class AppsDatabase extends Database 
{
    private $table_name = "apps"; //table
    private $id_name = "app_id"; //id from row

    // Get one app by using the inherited function getOneRowByIdFromTable
    public function getOne($app_id) //hämtar det specifika id från raden i apps table
    {
        $result = $this->getOneRowByIdFromTable($this->table_name, $this->id_name, $app_id);

        $app = $result->fetch_object("AppModel");  // Konverteras till ett AppModel-Objekt med hjälp av fetch_object()

        return $app;
    }


    // GET all by using the inherited function getAllRowsFromTable
    public function getAll() // HTTP GET metoden
    {
        $result = $this->getAllRowsFromTable($this->table_name);

        $apps = [];

        while($app = $result->fetch_object("AppModel")){
            $apps[] = $app; //objekten är returned som en array så vi kan samla all information
        }

        return $apps;
    }

    // CREATE one by creating a query and using the inherited $this->conn 
    public function insert(AppModel $app){ // HTTP POST metod
        $query = "INSERT INTO apps (app_name, description, price) VALUES (?, ?, ?)"; //sätter in info i tabellen apps med dess kolumner

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ssi", $app->app_name, $app->description, $app->price); //använder prepared statement för att förhindra injection.

        $success = $stmt->execute();

        return $success;
    }

     // UPDATE one by creating a query and using the inherited $this->conn 
     // Raden som ska uppdateras identifieras av dess app_id-fält, som skickas som en separat parameter.
     public function updateApp(AppModel $app, $app_id){ //HTTP PUT metod 
        $query = "UPDATE apps SET app_name = ?, description = ?, price = ? WHERE app_id = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ssii", $app->app_name, $app->description, $app->price, $app_id);

        $success = $stmt->execute();

        return $success;
    }

    // DELETE one by using the inherited function deleteOneRowByIdFromTable
    public function deleteById($app_id)//HTTP DELETE metod
    {
        $success = $this->deleteOneRowByIdFromTable($this->table_name, $this->id_name, $app_id);

        return $success;
    }
}
