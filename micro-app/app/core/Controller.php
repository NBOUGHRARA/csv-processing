<?php

/**
 * 
 */
class Controller
{
	
	public function model($model)
	{
		require_once '../app/models/' . $model . '.php';
		return new $model(); 
	}

	public function view($view, $data = [])
	{
		require_once '../app/views/' . $view . '.php';

	}

	public function service($service)
	{
		require_once '../app/services/' . $service . '.php';
		return new $service();
	}
}