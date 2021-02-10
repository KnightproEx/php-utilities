<?php

// if ($_SERVER['REQUEST_METHOD'] != 'POST') {
// 	header('HTTP/1.0 404 Not Found');
// 	exit();
// }

abstract class Database {
	private const host = '';
	private const username = '';
	private const passwd = '';
	private const db = '';

	private $con;
	private $stmt;
	private $result;
	private $row;
	private $error;

	//constructor and destructor
	protected function __construct() {
		@$this->con = new mysqli(self::host, self::username, self::passwd, self::db);
		$this->error = '';

		if ($this->con->connect_errno) {
			exit('Database connection error!');
		}
	}

	public function __destruct() {
		if (is_resource($this->con) && get_resource_type($this->con) == 'mysql link') {
			$this->con->close();
		}
	}

	//execute mysql query, return false upon error
	protected final function query($query, array $paramArray) {
		if (@!$this->stmt = $this->con->prepare($query)) {
			$this->error .= $this->con->error;
			return FALSE;
		}

		if (@!call_user_func_array(array($this->stmt, 'bind_param'), $paramArray)) {
			$this->error .= $this->stmt->error;
			return FALSE;
		}

		if (@!$this->stmt->execute()) {
			$this->error .= $this->stmt->error;
			return FALSE;
		}

		$this->result = $this->stmt->get_result();
		$this->row = is_object($this->result) ? $this->result->num_rows : 0;
		$this->stmt->close();

		return TRUE;
	}

	// auto hexadecimal id generator
	protected final function fetch_next_id($table, $key, $prefix, $num_count, $increment = 1) {
		$con = new mysqli(self::host, self::username, self::passwd, self::db);
		$result = $con->query("SELECT $key FROM $table ORDER BY $key DESC LIMIT 1");

		if ($result->num_rows > 0) {
			$current = $result->fetch_assoc()[$key];
			$next_count = intval(substr($current, strlen($prefix))) + $increment;

			return $prefix . sprintf("%0${num_count}d", $next_count);
		}

		return $prefix . sprintf("%0{$num_count}d", 1);
	}

	//getter
	public function getResult() {
		return $this->result;
	}
	public function getRow() {
		return $this->row;
	}
	public function getError() {
		return $this->error;
	}
}
