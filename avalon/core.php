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
require(BASEPATH.'avalon/libraries/router.php');

// Router
$router = new Router;
$controller = $router->controller;
$method = $router->method;

// Database
require(APPPATH.'config/database.php');
if($database['enable'])
{
	require(BASEPATH.'avalon/database/'.$database['driver'].'.php');
	$db = new $database['driver']($database);
}

// Load the controller
if(file_exists(APPPATH.'controllers/'.$controller.'.php'))
{
	include(APPPATH.'controllers/'.$controller.'.php');
	
	// Check if the method exists..
	if(method_exists($controller,$method))
	{
		$avalon = new $controller();
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
	include(APPPATH.'controllers/errors.php');
	$avalon = new Errors();
	$avalon->notFound();
	exit;
}