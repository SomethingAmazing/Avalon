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
 * URI class
 * @package Avalon
 */
class URI
{
	public $seg;
	
	public function __construct()
	{
		if(!isset($_SERVER['ORIG_PATH_INFO'])) $_SERVER['ORIG_PATH_INFO'] = '';
		$this->seg = explode('/',trim((isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['ORIG_PATH_INFO']),'/'));
	}
	
	public function seg($segment)
	{
		return $this->seg[$segment];
	}
	
	/**
	 * Creates a URI anchored to the Avalon path.
	 *
	 * @param mixed $segments URI Segements
	 * @return string
	 */
	public function anchor($segments)
	{
		if(!is_array($segments)) {
			$segments = func_get_args();
		}
		$path = str_replace('index.php','',$_SERVER['SCRIPT_NAME']);
		return $path.$this->array_to_uri($segments);
	}
	
	public function geturi()
	{
	
	}
	
	// Used to convert the array passed to it into a URI
	private function array_to_uri($segments = array()) {
		$segs = array();
		if(count($segments) < 1 or !is_array($segments)) return;
		
		foreach($segments as $key => $val)
			$segs[] = $val;
		
		return implode('/',$segs);
	}
}