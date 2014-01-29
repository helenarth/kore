<?php

    global $version;

	$request 	= array();
	$mode		= initRequest($request);
	$ready 		= boot($mode, $request);
	$version    = getVersion();

	function initRequest(array &$request = array()) {
		$requestURL		= null;
		$requestType	= strtolower($_SERVER['REQUEST_METHOD']);
		$requestData	= array();
		$requestURP		= array();
		$requestAPI		= false;

		if (isset($_SERVER['REDIRECT_QUERY_STRING'])) {
			$parts 		= explode('=', $_SERVER['REDIRECT_QUERY_STRING'], 2);
			$requestURL	= (isset($parts[1])) ? $parts[1] : null;
			$requestURP = array_filter(explode('/', $requestURL));
			$requestAPI = (isset($requestURP[0]) and ($requestURP[0] == 'api'));
		}

		switch ($requestType) {

			case 'post':
				if (isset($_POST)) $requestData = $_POST;
			break;

			case 'delete':
			case 'put':
                parse_str(file_get_contents('php://input'), $requestData);
			break;

			default:
			case 'get':
			break;
			
		}

		$request = array(
			'url'		=> $requestURL,
			'data'		=> $requestData,
			'type'		=> $requestType,
			'urp'		=> $requestURP,
			'api'		=> $requestAPI
		);

		return $requestType;
	}
	
	function getVersion() {
	    $value  = null;
	    $lines  = explode("\n", `svn info`);
	    
	    foreach ($lines as $line) {
	        $parts  = explode(":", $line, 2);
	        $key    = trim($parts[0]);
	        if ($key != "Revision") continue;
	        
	        $value  = trim($parts[1]);
	        if ($value) break;
	    }
	    
	    return $value;
	}

	function boot($mode, $stdInput = array()) {
		global $workingFolder;
		global $request;

		$request 		= $stdInput;
		$workingFolder 	= getcwd();
		$bootstrap 		= include('sys/boot/init.php');

		return $bootstrap;
	}

?>