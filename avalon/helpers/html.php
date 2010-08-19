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

class HTML
{
	/**
	 * Link
	 * Returns the HTML for a basic <a> tag.
	 *
	 * @param string $url The URL.
	 * @param string $label The text for the link.
	 */
	public function link($url,$label,$title='')
	{
		return '<a href="'.$url.'"'.($title?'':'title="'.$title.'"').'>'.$label.'</a>';
	}
	
	/**
	 * CSS
	 * Returns the code to include a CSS file.
	 *
	 * @param string $file The path to the CSS file.
	 */
	public function css($file,$media='screen')
	{
		return '<link href="'.$file.'" media="'.$media.'" rel="stylesheet" type="text/css" />';
	}
	
	/**
	 * JavaScript
	 * Returns the code to include a JavaScript file.
	 *
	 * @param string $file The path to the JavaScript file.
	 */
	public function js($file)
	{
		return '<script src="'.$file.'" type="text/javascript"></script>';
	}
}