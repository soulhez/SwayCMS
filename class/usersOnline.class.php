<?php
if(!defined('SCMS')) die('Hacking attempt!');

class usersOnline {

	var $timeout = 600;
	var $count = 0;

	function usersOnline () {
		$this->timestamp = time();
		$this->ip = $this->ipCheck();
		$this->new_user();
		$this->delete_user();
		$this->count_users();
	}

	function ipCheck() {
		if (getenv('HTTP_CLIENT_IP')) {
			$ip = getenv('HTTP_CLIENT_IP');
		}
		elseif (getenv('HTTP_X_FORWARDED_FOR')) {
			$ip = getenv('HTTP_X_FORWARDED_FOR');
		}
		elseif (getenv('HTTP_X_FORWARDED')) {
			$ip = getenv('HTTP_X_FORWARDED');
		}
		elseif (getenv('HTTP_FORWARDED_FOR')) {
			$ip = getenv('HTTP_FORWARDED_FOR');
		}
		elseif (getenv('HTTP_FORWARDED')) {
			$ip = getenv('HTTP_FORWARDED');
		}
		else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

	function new_user() {
		$sql = new sql;
		$sql->wconnect();
		$insert = mysql_query ("INSERT INTO user_online(timestamp, ip) VALUES ('$this->timestamp', '$this->ip')");
	}

	function delete_user() {
		$sql = new sql;
		$sql->wconnect();
		$delete = mysql_query ("DELETE FROM user_online WHERE timestamp < ($this->timestamp - $this->timeout)");
	}

	function count_users() {
		$sql = new sql;
		$sql->wconnect();
		$count = mysql_num_rows ( mysql_query("SELECT DISTINCT ip FROM user_online"));
		return $count;
	}

}
?>