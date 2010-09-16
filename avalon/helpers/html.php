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

function anchor($url,$label)
{
	global $avalon;
	return alink($avalon->uri->anchor($url),$label);
}

/**
 * Returns the HTML for a basic <a> tag.
 *
 * @param string $url The URL.
 * @param string $label The text for the link.
 */
function alink($url,$label,$opts=array())
{
	return '<a href="'.$url.'"'.($opts['title'] ? ' title="'.$opts['title'].'"' : '').($opts['class'] ? ' class="'.$opts['class'].'"' : '').'>'.$label.'</a>';
}

/**
 * Returns the code to include a CSS file.
 *
 * @param string $file The path to the CSS file.
 */
function css_include_tag($file,$media='screen')
{
	return '<link href="'.$file.'" media="'.$media.'" rel="stylesheet" type="text/css" />';
}

/**
 * Returns the code to include a JavaScript file.
 *
 * @param string $file The path to the JavaScript file.
 */
function js_include_tag($file)
{
	return '<script src="'.$file.'" type="text/javascript"></script>';
}