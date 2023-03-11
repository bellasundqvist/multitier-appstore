<?php

// Kollar om konstant 'My_APP' är definierad
// och om den nuvarande php filen är samma som filen som körs
// Om något av villkoren inte är uppfyllt slutar skriptet att köras
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

// Class for connecting to database - har metoder för att hämta och radera data från databas. 

class Database
{
    private $host = "localhost";
    private $user = "root";
    private $pass = "root";
    private $db = "multitier_appstore";

    protected $conn;

    // Anslut till databasen i constructor så att alla funktioner kan använda $this->conn
    public function __construct() // Construct metoden används för att upprätta en databasanslutning med hjälp av mysqli_connect().
    {
        $this->conn = mysqli_connect($this->host, $this->user, $this->pass, $this->db);

        if (!$this->conn) {
            die("Error connection to db!");
        }
    }

    // Retrieves all rows from the specified 
    // table in the database and returns the result.
    protected function getAllRowsFromTable($table_name)
    {
        // Variables inside the query are OK when the variables are not user input.
        // Never use variables directly in queries when the variables value is user input.
        // This includes data from the database that could come from a user
        // Only use hard coded values OR white listed values directly in queries
        $query = "SELECT * FROM {$table_name}";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result;
    }

    // Retrieves one from the specified 
    // table in the database and returns the result.
    protected function getOneRowByIdFromTable($table_name, $id_name, $id)
    {
        
        $query = "SELECT * FROM {$table_name} WHERE {$id_name} = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result;
    }

    // Deletes one row from the specified 
    // table in the database.
    protected function deleteOneRowByIdFromTable($table_name, $id_name, $id)
    {
        
        $query = "DELETE FROM {$table_name} WHERE {$id_name} = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("i", $id);

        $success = $stmt->execute();

        return $success;
    }
}
