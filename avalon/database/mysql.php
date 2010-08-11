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

class MySQL
{
	private $link;
	private $queries = 0;
	
	public function __construct($config)
	{
		$this->connect($config['server'],$config['username'],$config['password']);
		$this->select_db($config['database']);
	}
	
	/**
	 * Connect
	 * Connect to the database.
	 * @param string $server Server address
	 * @param string $username Username on the server
	 * @param string $password Password for the user
	 */
	public function connect($server,$username,$password)
	{
		$this->link = mysql_connect($server,$username,$password) or $this->halt();
	}
	
	/**
	 * Select Database
	 * Select what database to use.
	 * @param string $database The name of the database
	 */
	public function select_db($databse)
	{
		return mysql_select_db($databse,$this->link);
	}
	
	/**
	 * Query
	 * Query the selected Database.
	 * @param string $query The query to run.
	 */
	public function query($query)
	{
		$result = mysql_query($query,$this->link) or $this->halt($query);
		$this->queries++;
		return $result;
	}
	
	/**
	 * Fetch Array
	 * Returns an array that corresponds to the fetched row.
	 */
	public function fetch_array($result)
	{
		return mysql_fetch_array($result);
	}
	
	/**
	 * Num Rows
	 * Get number of rows in result.
	 */
	public function numrows($result)
	{
		return mysql_num_rows($result);
	}
	
	/**
	 * Select
	 * Easy SELECT query builder.
	 * @param string $table Table name to query
	 * @param array $args Arguments for the query
	 */
	public function select($table,$args=array())
	{
		$query = 'SELECT * FROM '.$table.' ';
		
		$orderby = (isset($args['orderby']) ? " ORDER BY ".$args['orderby'] : NULL);
		unset($args['orderby']);
		
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
			$fields = $args['where'];
		}
		
		$query .= $fields;
		$query .= $orderby;
		$query .= $limit;
		
		return $this->query($query);
	}
	
	/**
	 * Insert
	 * Easy INSERT query builder.
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
		$this->query("INSERT INTO ".$this->prefix.$table." (".implode(', ',$fields).") VALUES(".implode(', ',$values).")");
	}
	
	/**
	 * Delete
	 * Easy DELETE query builder.
	 * @param string $table Table name to delete from
	 * @param array $args Arguments for the query
	 */
	public function delete($table,$data=array())
	{
	
	}
	
	/**
	 * Insert ID
	 * Returns the ID of the last inserted row.
	 */
	public function insert_id()
	{
		return mysql_insert_id($this->link);
	}
	
	public function close()
	{
		mysql_close($this->link);
	}
	
	public function __destruct()
	{
		$this->close();
	}
}