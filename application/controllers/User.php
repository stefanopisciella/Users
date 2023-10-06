<?php
namespace controllers;

require "application/controllers/AbstractController.php";
use models\Users as ModelUser;

use function PHPSTORM_META\sql_injection_subst;

class User extends AbastractController{
    public static function index() {
        echo "CIAOOOOO";
    
    }




}