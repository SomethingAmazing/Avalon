<?php
/**
 * Avalon
 * Copyright (C) 2010 Jack Polgar
 *
 * This file is part of Avalon.
 * 
 * Avalon is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 3 only.
 * 
 * Avalon is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Avalon. If not, see <http://www.gnu.org/licenses/>.
 */

require(BASEPATH.'avalon/version.php');

// Load core files
require(BASEPATH.'avalon/common.php');
require(BASEPATH.'avalon/libraries/controller.php');

// Hooks
$hooks =& loadclass('hooks');
$hooks->hook('pre_system');

// Output
$output =& loadclass('output');

// Database
require(APPPATH.'config/database.php');
if($database['enable'])
{
	require(BASEPATH.'avalon/database/'.$database['driver'].'.php');
	$db = new $database['driver']($database);
}

// Router
$router = loadclass('router');
$controller = $router->controller;
$controller_file = $router->controller.'_controller';
$controller_class = str_replace('_','',$router->controller).'Controller';
$method = $router->method;

$hooks->hook('pre_controller');

// Check if an app controller exists
if(file_exists(APPPATH.'appcontroller.php'))
	require(APPPATH.'appcontroller.php');
// There isnt..
else
	require(BASEPATH.'avalon/appcontroller.php');

// Load the controller
$hooks->hook('pre_controller');
if(file_exists(APPPATH.'controllers/'.$controller_file.'.php'))
{
	include(APPPATH.'controllers/'.$controller_file.'.php');
	
	// Check if the method exists..
	if(method_exists($controller_class,$method))
	{
		$avalon = new $controller_class();
		$avalon->$method();
	}
	// The method doesn't exist, output the error.
	else
	{
		error("Error","The method '".$method."' for controller '".$controller."' doesn't exist.");
	}
}
// Controller doesn't exist, load error controller
else
{
	include(APPPATH.'controllers/errors_controller.php');
	$avalon = new ErrorsController();
	$avalon->notFound();
}
$hooks->hook('post_controller');

$hooks->hook('post_system');

$output->display();