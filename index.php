<?php
// Index page is the root of our entire web app
// Directs requests to the appropriate controller based on URL path

// Define global constant to prevent direct script loading 
define('MY_APP', true);

// Load the router responsible for handling API requests
require_once __DIR__ . "/api/APIRouter.php";

// Get URL path - how to get from where you are to a specific file
$path = $_GET["path"];
$path_parts = explode("/", $path); // explode is a way for us to split the path into an array based on the / separator. 
$base_path = strtolower($path_parts[0]); // first part of path assigned to the variable $base_path

// If the URL path is equal to "api", load the API (if it's more than 1 path)
if($base_path == "api" && count($path_parts) > 1){
    $query_params = $_GET;

    // Handle requests using the API router
    $api = new APIRouter($path_parts, $query_params);
    $api->handleRequest(); // Genererar en lämplig respons
}
else{ // If URL path is not API, respond with "not found"
    http_response_code(404);
    die("Page not found");
}