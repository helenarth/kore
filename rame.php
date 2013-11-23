<pre><?php



	$request 	= array();
	$mode		= initRequest($request);
	$ready 		= boot($mode);

	print $ready;


	function initRequest(array &$request = array()) {
		$requestURL		= null;
		$requestType	= strtolower($_SERVER['REQUEST_METHOD']);
		$requestData	= array();
		$requestURP		= array();
		$requestAPI		= false;

		if (isset($_SERVER['REDIRECT_QUERY_STRING'])) {
			$parts 		= explode('=', $_SERVER['REDIRECT_QUERY_STRING'], 2);
			$requestURL	= (isset($parts[1])) ? $parts[1] : null;
			$requestURP = explode('/', $requestURL);
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



	function boot($mode) {
		global $workingFolder;

		$workingFolder 	= getcwd();
		$bootstrap 		= include('sys/boot/init.php');
		return $bootstrap;
	}


	function processRequest() {

	}



?>