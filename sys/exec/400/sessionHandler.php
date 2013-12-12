<?php

	$sessionId 			= session_id('member');

	if (!$sessionId) {
		session_start('private');
		$sessionType	= 'guest';
	} else {
		$sessionType	= 'member';
	}

	$_SESSION['type']	= $sessionType;

?>