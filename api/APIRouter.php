<?php

// Kollar om konstant 'My_APP' är definierad
// och om den nuvarande php filen är samma som filen som körs
// Om en fil inte är definierat, så har vi inte tillgång till den. 
// If satsen finns för att kunna ansluta sig till mappen projektet är kopplat till
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

// The require_once will check if the file has already been included, and if so, not include (require) it again.
//When you use the __DIR__ inside an include/require, the __DIR__ returns the directory of the included file.
require_once __DIR__ . "/AppsAPI.php";
require_once __DIR__ . "/UsersAPI.php";


// Class for routing all our API requests 

class APIRouter{

    private $path_parts, $query_params;
    private $routes = [];

    public function __construct($path_parts, $query_params)
    {
        // Available routes
        // Add to this if you need to add any route to the API
        $this->routes = [
            // Whenever someone calls "api/Apps" we 
            // will load the AppsAPI class
            "apps" => "AppsAPI",
            "users" => "UsersAPI"
        ];

        $this->path_parts = $path_parts;
        $this->query_params = $query_params;
    }

    public function handleRequest(){ //routar till rätt table

        // Get the requested resource from the URL such as "Apps" or "Users"
        $resource = strtolower($this->path_parts[1]); //lowercases our string

        // Cet the class specified in the routes
        $route_class = $this->routes[$resource];

        // Create a new object from the resource class
        $route_object = new $route_class($this->path_parts, $this->query_params);

        // Handle the request
        $route_object->handleRequest();
    }
}