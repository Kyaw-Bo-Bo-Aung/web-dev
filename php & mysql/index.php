<?php

require "vendor/autoload.php"; 
require "core/bootstrap.php";
// require "controllers/IndexController.php";


$router = new Router;
require "routes.php";


Router::load("routes.php")
		->direct(Request::uri(),$_SERVER['REQUEST_METHOD']);
