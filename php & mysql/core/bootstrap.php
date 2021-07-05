<?php
use core\App;

require "functions.php";

// require "Router.php";
// require "Request.php";
// require "database/Connections.php";
// require "database/QueryBuilder.php";

// $config = require "config.php";

// $database = new QueryBuilder(
// 	Connection::make($config['database'])
// );

App::bind("config", require "config.php");
App::bind("database", new QueryBuilder( Connection::make(App::get('config')['database']) ));
// dd(App::get('database'));