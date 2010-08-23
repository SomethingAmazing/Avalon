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

class Output
{
	private $final_output = '';
	
	public function append_output($output)
	{
		$this->final_output .= $output;
	}
	
	/**
	 * Displays the built layout.
	 *
	 * @param string $layout The layout file in which to use.
	 */
	public function display()
	{
		global $avalon;
		$this->uri = $avalon->uri;
		
		// Make helpers easily accessible.
		$helpers = (object)'helpers';
		foreach($avalon->load->helpers as $helper_name => $helper)
			$helpers->$helper_name = $helper;
		
		// Get variables set from the controller (or other places).
		foreach($avalon->vars as $var => $value)
			$$var = $value;
			
		$output = $this->final_output;
		
		if($layout == '') $layout = 'default';
		
		// Check if layout exists.
		if(!file_exists(APPPATH.'views/_layouts/'.$layout.'.php'))
			$this->error('Error loading layout: '.$layout);
		
		ob_start();
		require(APPPATH.'views/_layouts/'.$layout.'.php');
		$output = ob_get_contents();
		ob_end_clean();
		
		if(extension_loaded('zlib'))
		{
			if(isset($_SERVER['HTTP_ACCEPT_ENCODING']) and strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false)
			{
				ob_start('ob_gzhandler');
			}
		}
		
		header("X-Avalon: ".AVALONVER);
		
		$memory	 = (!function_exists('memory_get_usage')) ? '0' : round(memory_get_usage()/1024/1024, 2).'MB';
		$output = str_replace(array('{memory_useage}'),array($memory),$output);
		echo $output;
	}
}