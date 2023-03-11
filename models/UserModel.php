<?php

// Kollar om konstant 'My_APP' är definierad
// och om den nuvarande php filen är samma som filen som körs
// Om något av villkoren inte är uppfyllt slutar skriptet att köras
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

// Model class for user-table in database

class UserModel{
    public $user_id;
    public $first_name;
    public $last_name;
}