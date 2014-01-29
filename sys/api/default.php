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
	class null extends ui_api {

		public function isJSON() 	{return false;}
		public function exitError()	{return false;}
		public function result()	{return array();}
		public function errors()	{return array();}
		public function getPage(array $param = array())   {return null;}
		
		public function permission(array $param = array()) {
		    return true;
		}

		public function get(	array $param = array()	) {
			return null;
		}

		public function post(	array $param = array()	) {
			return null;
		}

		public function put(	array $param = array()	) {
			return null;
		}

		public function delete(	array $param = array()	) {
			return null;
		}

	}

?>