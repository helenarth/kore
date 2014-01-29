<?php

	global $apiResult;
	global $apiErrors;
	global $apiType;
	global $apiExit;


	/** Handle User-level API Errors **/
	if ($apiExit or $apiErrors) {
		throwError("Cannot Execute API Request", 256, array('errors'=>$apiErrors));
		if ($apiExit) exit();
	}


	/** Handle API Type **/
	if (!$apiType) $apiType = 'html/text';
	switch ($apiType) {
		case 'json':		processJSON($apiResult);	break;
		case 'stream':		processStream($apiResult);	break;
		case 'html/text':	processText($apiResult);	break;
	}


	function processJSON($result) {
		print json_encode($result);
		exit();
	}

	function processStream($result) {
		exit();

		/**
		@todo print headers and return stream
		*/
	}

	function processText($result) {
		/** 
		 * Processing will continue
		 * result is ignored 
		 */
		if (isset($result['reload'])) {
		    header("Location: {$result['reload']}");
		    exit();
		}

		print $result;
		exit();
	}

?>