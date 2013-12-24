<?php
	
	initPortal();

	/**
	@todo
	*/

	function initPortal() {
		$sessionType	= (isset($_SESSION['type']))
			? $_SESSION['type']
			: 'guest'
		;

		$folder 		= ($sessionType == 'guest')
			? "ui/portal"
			: "ui/sessionType"
		;

		$uiLocation		= getcwd()."/$folder";
	}

?>