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

class Avalon
{
	public static $instance;
	
	public function __construct()
	{
		self::$instance = $this;
		
		$this->uri	=& loadclass('uri');
		$this->view	=& loadclass('view');
	}
	
	public static function &get_instance()
	{
		return self::$instance;
	}
}