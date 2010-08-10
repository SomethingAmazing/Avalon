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

class View
{
	private $final_output = '';
	private $ob_level;	
	
	// Constructor
	public function __construct()
	{
		$this->ob_level = ob_get_level();
		$this->keys();
	}
	
	/**
	 * Load View
	 * @param string $file Name of the file.
	 * @param array $args Arguments to be passed to the view.
	 * @param bool $return Return the view code or not.
	 */
	public function load($view,$args=array(),$return=false)
	{
		$this->keys();
		
		$this->view =& $this;
		
		if(is_array($args))
			foreach($args as $arg => $value)
				$$arg = $value;
		
		if(!file_exists(APPPATH.'views/'.$view.'.php'))
		{
			ob_end_clean();
			$this->error('Error loading view: '.$view);
		}
		
		ob_start();
		include(APPPATH.'views/'.$view.'.php');
		
		// Return the file data if requested
		if($return)
		{		
			$buffer = ob_get_contents();
			@ob_end_clean();
			return $buffer;
		}
		
		if(ob_get_level() > $this->ob_level + 1)
		{
			ob_end_flush();
		}
		else
		{
			$this->append_output(ob_get_contents());
			@ob_end_clean();
		}
	}
	
	/**
	 * Display
	 * Displays the built layout.
	 * @param string $layout The layout file in which to use.
	 */
	public function display($layout='default')
	{
		$output = $this->final_output;
		
		if($layout == '')
			$layout = 'default';
		
		// Check if layout exists.
		if(!file_exists(APPPATH.'views/_layouts/'.$layout.'.php'))
			$this->error('Error loading layout: '.$layout);
		
		require(APPPATH.'views/_layouts/'.$layout.'.php');
	}
	
	// Internal function to append output to the final_output string.
	private function append_output($output)
	{
		$this->final_output .= $output;
	}
	
	// Internal function to display an error.
	private function error($message)
	{
		print('<blockquote><div style="border:1px solid darkred;padding:10px;background:#fff;color:000;">'.$message.'</div></blockquote>');
		exit;
	}
	
	private function keys()
	{
		$avalon = getAvalon();
		
		foreach(array_keys(get_object_vars($avalon)) as $key)
		{
			if(!isset($this->$key))
				$this->$key = $avalon->$key;
		}
	}
}