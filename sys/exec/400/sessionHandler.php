<?php

	setSession();

	function setSession() {
		global $sessionId;
		global $sessionType;

		$sessionId 			= session_id('member');
		$sessionType		= 'member';

		if (!$sessionId) {
			session_start('private');
			$sessionType			= 'guest';
			$_SESSION['created']	= time();
		}

		$_SESSION['type'] 	= $sessionType;		
	}


?>