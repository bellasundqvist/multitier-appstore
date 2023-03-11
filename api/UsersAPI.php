<?php
// Kollar om konstant 'My_APP' är definierad
// och om den nuvarande php filen är samma som filen som körs
// Om en fil inte är definierat, så har vi inte tillgång till den. 
//If satsen finns för att kunna ansluta sig till mappen projektet är kopplat till
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

// The require_once will check if the file has already been included, and if so, not include (require) it again.
//When you use the __DIR__ inside an include/require, the __DIR__ returns the directory of the included file.
require_once __DIR__ . "/RestAPI.php";
require_once __DIR__ . "/../business-logic/UsersService.php";

// Class for handling requests to "api/users"
// här har vi en class som heter UsersAPI med en inheritance som är en ny klass som hämtar properties och metod egenskaperna från RestApi 

class UsersAPI extends RestAPI
{
    //Hanterar inkommande HTTP-request genom att kalla på lämplig funktion beroende på vad vi vill göra
    public function handleRequest()
    {

        
        // If theres 2 parts in the path and the request method is GET 
        // it means that the client is requesting "api/users" and
        // we should respond by returning a list of all users
        if ($this->method == "GET" && $this->path_count == 2) { // $this variablen refererar till nuvarande objekt från klassen
            $this->getAll();
        } 

        // If there's 2 parts in the path and the request method is GET
        // it means that the client is requesting "api/users/{id}".
        // responds with the user of that ID
        else if ($this->path_count == 3 && $this->method == "GET") {
            $this->getById($this->path_parts[2]); // hämtar specifika ID't från table
        }

        // If theres 2 parts in the path and the request method is POST 
        // it means that the client is requesting "api/users" and we
        // should get ths contents of the body and CREATE a user. 
        else if ($this->path_count == 2 && $this->method == "POST") { //skapar ny rad i tabellen
            $this->postOne();
        } 

        // If there's 3 parts in the path and the request method is PUT
        // it means that the client is requesting "api/users/{id}".
        // responds with the user of that ID so we can update it
        else if ($this->path_count == 3 && $this->method == "PUT") { //uppdaterar raden i table
            $this->putOne($this->path_parts[2]);
        }

         // We use the delete method to delete a whole row from the database table
         else if ($this->path_count == 3 && $this->method == "DELETE") { //tar bort hela raden
            $this->deleteOne($this->path_parts[2]);
        }
        
        // If none of our ifs are true, we should respond with "not found"
        else {
            $this->notFound();
        }
    }

    // Gets ALL users and sends them to the client as JSON so we see them in postman
    // Vi har satt dessa funktioner på privat så att man bara kan få tillgång till den i denna klass
    private function getAll()
    {
        $user = UsersService::getAllUsers(); //vi kallar på statiska metoden getAllUsers med dubbelkolon ::

        $this->sendJson($user);
    }

    // Gets ONE and sends it to the client as JSON
    private function getById($id)
    {
        $user = UsersService::getUserById($id);

        if ($user) {
            $this->sendJson($user);
        } else {
            $this->notFound();
        }
    }

    // Gets the contents of the body and saves it as a user by 
    // inserting it in the database.
    private function postOne()
    {
        $user = new UserModel(); //skapar ett nytt object från klass 

        $user->first_name = $this->body["first_name"];
        $user->last_name = $this->body["last_name"];

        $success = UsersService::saveUser($user);

        if($success){
            $this->created();
        }
        else{
            $this->error();
        }
    }

    // Gets the contents of the body and UPDATE it as a user by 
    // updating it in the database.
    private function putOne($id) // update and replace row in database
    {
        $user = new UserModel(); //skapar ett nytt object från klass 

        $user->first_name = $this->body["first_name"];
        $user->last_name = $this->body["last_name"];

        $success = UsersService::updateUser($user, $id);

        if($success){
            $this->created();
        }
        else{
            $this->error();
        }
    }

    // Deletes the app with the specified ID in the DB
    private function deleteOne($id)
    {
        $user = UsersService::getUserById($id);

        if($user == null){
            $this->notFound();
        }

        $success = UsersService::deleteUserById($id);

        if($success){
            $this->noContent();
        }
        else{
            $this->error();
        }
    }



}