<?php

	setSession();

	function setSession() {
		global $sessionType;

		session_start();
		if (isset($_SESSION['type'])) {
		    $type                   = $_SESSION['type'];
		    $sessionType            = ($type == 'member') ? $type : 'guest';
		} else {
		    $_SESSION['created']    = time();
		    $sessionType            = 'guest';
		}
		
		$_SESSION['type'] 	        = $sessionType;		
	}


?>