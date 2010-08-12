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

function &getAvalon()
{
	return Avalon::get_instance();
}

function &loadclass($class)
{
	static $classes = array();
	
	if(isset($classes[$class])) return $classes[$class];
	
	include(BASEPATH.'avalon/libraries/'.$class.'.php');
	$classes[$class] = new $class();
	
	return $classes[$class];
}

function error($title,$message)
{
	print('<blockquote style="width: 800px; margin: 0 auto; font-family: arial; font-size: 14px;">');
	print('<h1 style="margin:0;">'.$title.'</h1>');
	print('<div style="padding: 5px; border: 2px solid darkred; background: #f9f9f9;">'.$message.'</div>');
	print('<small>Avalon '.AVALONVER);
	print('</blockquote>');
	exit;
}