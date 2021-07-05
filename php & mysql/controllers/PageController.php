<?php

namespace controllers;
use core\App;

class PageController
{
	public function index()
	{
		$tasks = App::get('database')->selectAll('tasks');
		$users = App::get('database')->selectAll('user');

		// require "views/index.view.php";
		view('index',[
			'users'=>$users,
			'tasks'=>$tasks
		]);
	}

	public function about()
	{
		view('about');
	}

	public function contact()
	{
		view('contact');
	}

	public function products()
	{
		view('product');
	}

	public function create()
	{
		$users = App::get('database')->insert([
			'name' => request('name')
		],'user');

		redirect("/");
	}

	public function destroy()
	{
		$del_user = App::get('database')->destroy(request('id'),'user');
		redirect("/");
	}
}