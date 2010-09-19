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
 * MySQL driver
 * @package Avalon
 */
class MySQL
{
	private $link;
	public $queries = 0;
	private $results = array();
	
	public function __construct($config)
	{
		$this->connect($config['server'],$config['username'],$config['password']);
		$this->select_db($config['database']);
		$this->prefix = $config['prefix'];
	}
	
	/**
	 * Connect to the database.
	 *
	 * @param string $server Server address
	 * @param string $username Username on the server
	 * @param string $password Password for the user
	 */
	public function connect($server,$username,$password)
	{
		$this->link = mysql_connect($server,$username,$password) or $this->halt();
	}
	
	/**
	 * Select what database to use.
	 *
	 * @param string $database The name of the database
	 */
	public function select_db($databse)
	{
		return mysql_select_db($databse,$this->link);
	}
	
	/**
	 * Query the selected Database.
	 *
	 * @param string $query The query to run.
	 */
	public function query($query)
	{
		$result = mysql_query($query,$this->link) or $this->halt($query);
		$this->queries++;
		return $result;
	}
	
	/**
	 * Query and fetch the array of the first row returned.
	 *
	 * @param string $query The query.
	 */
	public function query_first($query)
	{
		return $this->fetch_array($this->query($query));
	}
	
	/**
	 * Escapes a string for use in a query.
	 *
	 * @param string $string String to escape.
	 * @return string
	 */
	public function escape_string($string)
	{
		return mysql_escape_string($string);
	}
	
	/**
	 * Shortcut for MySQL::escape_string
	 *
	 * @param string $string String to escape.
	 * @return string
	 */
	public function es($string)
	{
		return $this->escape_string($string);
	}
	
	/**
	 * Escapes special characters from a string for use in a query.
	 *
	 * @param string $string String to escape.
	 * @return string
	 */
	public function real_escape_string($string)
	{
		return mysql_real_escape_string($string);
	}
	
	/**
	 * Shortcut for MySQL::real_escape_string
	 *
	 * @param string $string String to escape.
	 * @return string
	 */
	public function res($string)
	{
		return $this->real_escape_string($string);
	}
	
	/**
	 * Returns an array that corresponds to the fetched row.
	 *
	 * @param mixed $result The query result.
	 */
	public function fetch_array($result)
	{
		return mysql_fetch_array($result);
	}
	
	/**
	 * Get number of rows in result.
	 *
	 * @param mixed $result The query result.
	 */
	public function num_rows($result)
	{
		return mysql_num_rows($result);
	}
	
	/**
	 * Easy SELECT query builder.
	 *
	 * @param string $table Table name to query
	 * @param array $args Arguments for the query
	 * @return array
	 */
	public function select($table,$args=array())
	{
		$query = 'SELECT * FROM '.$this->prefix.$table.' ';
		
		$orderby = (isset($args['orderby']) ? " ORDER BY ".$args['orderby'] : NULL);
		unset($args['orderby']);
		
		$limit = (isset($args['limit']) ? ' LIMIT '.$args['limit'] : NULL);
		unset($args['limit']);
		
		if(isset($args['where']) && is_array($args['where'])) {
			$fields = array();
			foreach($args['where'] as $field => $value)
			{
				$fields[] = $field."='".$value."'";
			}
			$fields = ' WHERE '.implode(' AND ',$fields);
		} else {
			$fields = (isset($args['where']) ? $args['where'] : '');
		}
		
		$query .= $fields;
		$query .= $orderby;
		$query .= $limit;
		
		if(isset($this->results[md5($query)])) return $this->results[md5($query)];
		
		$rows = array();
		$result = $this->query($query);
		while($row = $this->fetch_array($result))
			$rows[] = $row;
		
		$this->results[md5($query)] = $rows;
		
		return $rows;
	}
	
	/**
	 * Easy INSERT query builder.
	 *
	 * @param string $table Table name to insert into
	 * @param array $args Arguments for the query
	 */
	public function insert($table,$data=array())
	{
		$fields = array();
		$values = array();
		
		// Split the field name and value into the arrays.
		foreach($data as $field => $value)
		{
			$fields[] = $field;
			$values[] = $value;
		}
		
		// Run the query.
		$this->query("INSERT INTO ".$this->prefix.$this->prefix.$table." (".implode(', ',$fields).") VALUES(".implode(', ',$values).")");
	}
	
	/**
	 * Easy DELETE query builder.
	 *
	 * @param string $table Table name to delete from
	 * @param array $args Arguments for the query
	 */
	public function delete($table,$data=array())
	{
		$query = 'DELETE FROM '.$this->prefix.$table.' ';
		
		$limit = (isset($args['limit']) ? ' LIMIT '.$args['limit'] : NULL);
		unset($args['limit']);
		
		if(is_array($args['where'])) {
			$fields = array();
			foreach($args['where'] as $field => $value)
			{
				$fields[] = $field."='".$value."'";
			}
			$fields = ' WHERE '.implode(' AND ',$fields);
		} else {
			$fields = ' WHERE '.$args['where'];
		}
		
		$query .= $fields;
		$query .= $limit;
		
		$this->query($query);
	}
	
	/**
	 * Returns the ID of the last inserted row.
	 * @return integer
	 */
	public function insert_id()
	{
		return mysql_insert_id($this->link);
	}
	
	/**
	 * Closes the connection to the database.
	 */
	public function close()
	{
		mysql_close($this->link);
	}
	
	// MySQL Error Number
	private function errno()
	{
		return mysql_errno($this->link);
	}
	
	// MySQL Error
	private function error()
	{
		return mysql_error($this->link);
	}
	
	// The halt function. used to display errors..
	private function halt()
	{
		error('Database Error', '#'.$this->errno().': '.$this->error());
	}
	
	public function __destruct()
	{
		$this->close();
	}
}