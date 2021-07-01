<?php

function dd($data) {
	echo '<pre>';
	print_r($data);
	echo '</pre>';
	die();
}

function view($name, $data=[]){

	extract($data);
	return require "views/$name.view.php";
	

}
