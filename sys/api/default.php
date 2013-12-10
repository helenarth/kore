<?php


	/**
	 * Null Handler
	 */
	function nullRequest() {
		return array(
			'folder'	=> null,
			'class'		=> 'null',
			'method'	=> 'get'
		);
	}


	/**
	 * Null Class
	 */
	class null {

		public function get() {
			return true;
		}

	}

?>