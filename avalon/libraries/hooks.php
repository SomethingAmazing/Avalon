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
 * Hook class
 * @package Avalon
 */
class Hooks
{
	private $hooks = array();
	
	public function __construct()
	{
		// Fetch hooks config
		require(APPPATH.'config/hooks.php');
		
		if(is_array($hooks)) $this->hooks = array_merge($hooks,$this->hooks);
	}
	
	/**
	 * Used to execute classes and/or functions linked to the specified hook.
	 *
	 * @param string $hook Name of the hook.
	 */
	public function hook($hook)
	{
		if(!isset($this->hooks[$hook])) return flase;
		
		if(is_array($this->hooks[$hook]) && isset($this->hooks[$hook][0]))
		{
			foreach($this->hooks[$hook] as $data)
				$this->run_hook($data);
		}
		else
		{
			$this->run_hook($this->hooks[$hook]);
		}
	}
	
	// Used to execute classes and/or functions.
	private function run_hook($data)
	{
		// Set class / function name
		$class		= false;
		$function	= false;
		$params		= '';
		
		if(isset($data['class']) and $data['class'] != '') $class = $data['class'];
		if(isset($data['function'])) $function = $data['function'];
		if(isset($data['params'])) $params = $data['params'];
		
		if(!$class and !$function) return false;
		
		// Execute the requested class and/or function
		if($class)
		{
			if(!class_exists($class)) require(APPPATH.'hooks/'.$data['file']);
			
			$hook = new $class;
			$hook->$function($params);
		}
		else
		{
			if(!function_exists($function)) require(APPPATH.'hooks/'.$data['file']);
			
			$function($params);
		}
		
		return true;
	}
}