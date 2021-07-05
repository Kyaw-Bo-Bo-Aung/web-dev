<?php

use controllers\PageController;
// $router->register(
// 	[
// 		"" => "controllers/IndexController.php",
// 		"about" => "controllers/AboutController.php",
// 		"contact" => "controllers/ContactController.php",
// 		"products" => "controllers/ProductController.php",
// 		"names" => "controllers/add-name.php"
// 	]
// );

// $router->get("" ,"controllers/IndexController.php");
// $router->get("about", "controllers/AboutController.php");
// $router->get("contact", "controllers/ContactController.php");
// $router->get("products", "controllers/ProductController.php");

// $router->post("names", "controllers/add-name.php");
// $router->post("destroy", "controllers/destroy-name.php");


// $router->get("", "PageController@index");
// $router->get("about", "PageController@about");
// $router->get("contact", "PageController@contact");
// $router->get("products", "PageController@product");

// $router->post("names", "PageController@create");
// $router->post("destroy", "PageController@destroy");


$router->get("", [PageController::class, 'index']);
$router->get("about", [PageController::class, 'about']);
$router->get("contact", [PageController::class, 'contact']);
$router->get("products", [PageController::class, 'products']);

$router->post("names", [PageController::class, 'create']);
$router->post("destroy", [PageController::class, 'destroy']);