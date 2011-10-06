<?php

class DB {
	public $num_rows;
	public $last_id;
	private $conn;
	private $result;

	/**
	*
	* @param string $host The hostname of the database to connect to
	* @param string $user The username of the database to conncet to
	* @param string $pass The password of the database to connect to
	* @param string $db The name of the database to connect to
	*
	*/
	public function __construct($host, $user, $pass, $db)
	{
		$this->conn = new mysqli($host, $user, $pass, $db) or die('Connection error ('.$this->conn->connect_errno.') '.$this->conn->connect_error);
		
	}

	/**
	* Close the database connection
	*/
	public function __destruct() { $this->close(); }
	public function close() { $this->conn->close(); }

	/**
	*
	* @param string $sql The SQL query to perform
	* @param array $params The parameters passed for a prepared statement (optional)
	*
	*/
	public function query($sql, $params = array())
	{	  
		$sql = filter_var($sql, FILTER_SANITIZE_STRING);
		$this->result = $this->prepare($sql, $params);

		if($this->result) return $this;
		else die("SQL Error: ".$this->conn->error);
	}

	/**
	* Manually executes the mysqli_stmt object. Used for INSERT/UPDATE queries.
	*/
	public function execute()
	{
		if($this->result->execute()) {
			$this->last_id = $this->result->insert_id;
			return TRUE;
		}
	}

	/**
	*
	* @param int $row_num The row number to fetch, if given only return that single row (optional)
	* @return mixed Contains an array of the returned results from the database, or a single value if only one column was selected.
	*
	*/	
	public function fetch($row_num = FALSE)
	{
		$this->result->execute();
		$this->result->store_result();		
		$this->num_rows = $this->result->num_rows;

		$meta = $this->result->result_metadata();		
		while($field = $meta->fetch_field())
		{
			$columns[] = $field->name;
			$fields[] = &${$field->name};
		}

		call_user_func_array(array($this->result, 'bind_result'), $fields);
        
		if(!$row_num)
		{
			$rows = array();
			while($this->result->fetch()) {
				$count = 0;
				$values = array();

				foreach($fields as $field) {
					$values[$columns[$count]] = $field;
					$count++;
				}

				$rows[] = $values;
			}
		} 
		else 
		{
			if($row_num > $this->num_rows-1)
				return FALSE;

			$this->result->data_seek($row_num);
			$this->result->fetch();

			$count = 0;
			$rows = array();

			foreach($fields as $field) {
				$rows[$columns[$count]] = $field;
				$count++;
			}
		}

		$this->result->close();

		if(count($rows) < 1)   return array();
		if($row_num !== FALSE) return (count($rows[$row_num]) == 1) ? array_pop($rows[$row_num]) : $rows[$row_num];
		return $rows;
	}

	/**
	*
	* Returns a single record, defaulting to the first.
	* This just uses the fetch method internally, passing zero as the first parameter.
	*
	* @param int $row_num The row number to fetch, if given only return that single row (optional)
	* @return mixed Contains an array of the returned results from the database, or a single value if only one column was selected.
	*/	
	public function result($row_num = 0) { return $this->fetch($row_num); }

	/**
	*
	* @return int Contains the number of rows returned from the last query
	*
	*/	
	public function num_rows() { return $this->num_rows; }

	/**
	*
	* @return int Contains the ID of the last row inserted into the database
	*
	*/
	public function last_id()  { return $this->last_id; }

	/**
	*
	* Abstraction method to prepare the SQL statement. This is not called directly by the user, but by the query method above.
	* The SQL will be modified if it contains an IN clause, so the user can simply pass a single ? in the IN clause, rather than one per IN item
	* 	Example:    SELECT * FROM `table` WHERE `id` IN(?)
	* 	Instead of: SELECT * FROM `table` WHERE `id` IN(?, ?, ?, ?)
	* The method also dynamically binds the results and params as needed, it can handle a variable number automatically.
	*
	* @param string $sql The SQL query to prepare
	* @param array $params The parameters passed for the prepared statement
	* @return mysqli_stmt $stmt Contains a mysqli_stmt object which is then used by fetch or execute.
	*
	*/		
	private function prepare($sql, $params) 
	{
	  $args = array();
		$sql = $this->parse_sql_in_clause($sql);
		if(!is_array($params)) $params = array($params);

		foreach($params as $key => $param) {
			if(is_array($param))
				foreach($param as $val)
					$args[] = &$val;
			else
        $args[] = &$params[$key];
		}

		$stmt = $this->conn->prepare($sql);
		if($stmt && !empty($params) && strstr($sql, '?')) // only bind params if there are prepared stmt placeholders (e.g. ?) in the sql
		{
			$bind_types = "";
			foreach($params as $val)
			{		
				switch($val)
				{
					case is_string($val):
						$bind_types .= 's';
						break;
					case is_integer($val):
						$bind_types .= 'i';
						break;
					case is_double($val):
						$bind_types .= 'd';
						break;
					default:
						$bind_types .= 's';
				}		
			}

			array_unshift($args, $bind_types);
			call_user_func_array(array($stmt, 'bind_param'), $args);
		}

		return $stmt;
	}

	/**
	*
	* @param string $sql The SQL query to parse for an IN clause
	* @return string Contains the SQL query parsed propery if it contains an IN clause
	*
	*/
	private function parse_sql_in_clause($sql)
	{
		if(preg_match('/IN \(/s', $sql)) {
			$total_to_prep = substr_count($sql, '?');
			$arg_number = -1;
			$curpos = -1;
			$i = 0;

			while($i <= $total_to_prep) {
				$pos = strpos($sql, '?', $curpos+1);
				$curpos = $pos;

				if(substr($sql, $pos-4, 2) == 'IN') {
					$arg_number = $i;
				}

				$i++;
			}

			if($arg_number != -1) {
				$total_arg_vals = count($params[$arg_number]);

				if($total_arg_vals > 0) {
					$placeholders = array_fill(0, $total_arg_vals, '?');
					$sql = preg_replace('/IN \(\?\)/', 'IN ('.implode(',', $placeholders).')', $sql);
				}
			}
		}

		return $sql;
	}
}

?>
