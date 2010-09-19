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

/**
 * URL Router
 * @package Avalon
 */
class Router
{
	private $routes = array();
	
	// Construct
	public function __construct()
	{
		// Fetch router config
		require(APPPATH.'config/routes.php');
		$this->routes = array_merge($this->routes,$routes);
		
		// Get URI segments
		if(!isset($_SERVER['ORIG_PATH_INFO'])) $_SERVER['ORIG_PATH_INFO'] = '';
		$request = trim((isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['ORIG_PATH_INFO']),'/');
		
		// Check if we only have one route
		if(count($this->routes) == 1)
		{
			$this->_set_request($request);
			return;
		}
		
		// Loop through the route array looking for wild-cards
		foreach($this->routes as $key => $val)
		{						
			// Convert wild-cards to RegEx
			$key = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $key));
			
			// Do we have a back-reference?
			if(strpos($val, '$') !== FALSE AND strpos($key, '(') !== FALSE)
				$val = preg_replace('#^'.$key.'$#', $val, $request);
			
			// Check if theres a RegEx match
			if(preg_match('#^'.$key.'$#', $request))
			{
				$this->_set_request($val);
				return;
			}
		}

		// No matches found, give it the current uri
		$this->_set_request($request);
	}
	
	// Private function used to set the request controller and method.
	private function _set_request($uri)
	{
		$segs = explode('/',$uri);
		
		if($segs[0] == '')
			$segs = explode('/',$this->routes['default']);
		
		$this->controller = $segs[0];
		if(!isset($segs[1]))
			$this->method = 'index';
		else
			$this->method = $segs[1];
	}
}