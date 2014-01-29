<?php

	/**
	 * Import Global Request Stack
	 */
	global $request;
	


	/**
	 * Get Std Input
	 */
	$stdParam		= (isset($request['urp']))	? $request['urp'] 	: array();
	$stdType		= (isset($request['type']))	? $request['type']	: 'get';



	/**
	 * Parse API Request
	 */
	$apiFolder		= (isset($stdParam[0])) ? $stdParam[0] : null;
	$apiClass		= (isset($stdParam[1])) ? $stdParam[1] : null;
	$apiMethod      = (isset($stdParam[2])) ? $stdParam[2] : $stdType;
	$nullHandler	= false;



	/**
	 * Default to a Page Request
	 */
	if ($apiFolder != 'api') {
		$apiFolder	= null;
		$apiClass 	= 'ui';
		$apiMethod	= $stdType;
	}



	/**
	 * API Method Param
	 */
	if ($apiClass == 'ui') {
		$apiParam		= array_values($stdParam);
		$strParam 		= implode(', ', $apiParam);
	} elseif ($stdType == 'post') {
	    $apiParam       = $_POST;
	    $strParam       = implode(', ', $apiParam);
	} else {
		if (isset($stdParam[0])) unset($stdParam[0]);
		if (isset($stdParam[1])) unset($stdParam[1]);
		if (isset($stdParam[2])) unset($stdParam[2]);
		$apiParam		= array_values($stdParam);
		$strParam 		= implode(', ', $apiParam);
	}



	/**
	 * Check requested filepath
	 * If folder/file not valid, default will be used.
	 */
	$fileList 		= array("$apiClass.php", "$apiClass.lib", "init.php", "init");
	$loadFolder		= "./sys/api/$apiClass";
	$loadFile 		= null;

	foreach ($fileList as $file) {
		$path 		= "$loadFolder/$file";

		if (realpath($path)) $loadFile = realpath($path);
		if ($loadFile) break;
	}


	if (!$loadFile) {
		$loadFile 	= realpath("./sys/api/default.php");

		if (!$loadFile) {
			/** Runtime Error (2048) **/
			throwError("API missing. Could not load Default API Handler", 2048, array('API Handler'=>$loadFile));
			exit();
		} else {
			/** Use Default 'nullHandler **/
			$nullHandler = true;
		}
	}



	/**
	 * Include the filepath
	 */
	$loaded 	= include($loadFile);
	if (!$loaded) {
		/** Runtime Error (2048) **/
		throwError("API could not be loaded from $apiFolder", 2048, array('API Handler'=>$loadFile));
		exit();
	}



	/** 
	 * Null Handler
	 * If using default API request, set the
	 * default API handler to nullRequest
	 */
	if ($nullHandler) {
		$nullReq 		= nullRequest();
		$apiFolder		= $nullReq['folder'];
		$apiClass 		= $nullReq['class'];
		$apiMethod 		= $nullReq['method'];
	}



	/**
	 * Validate the class exists
	 */
	if (!class_exists($apiClass)) {

		if ($nullHandler) {
			/** Runtime Error (2048) **/
			throwError("Cannot invoke class $apiClass. nullHandler missing", 2048, array('API Class'=>$apiClass));
			exit();
		} else {
			/** User Request Error (256) **/
			throwError("Cannot invoke class $apiClass. Bad Request from user", 256, array('API Class'=>$apiClass));
			exit();
		}

	}



	/**
	 * Attempt to instantiate the API class object
	 */
	$loadObj 	= new $apiClass();
	$hasMethod 	= method_exists($loadObj, $apiMethod);
	if (!$hasMethod) {
		/** User Request Error (2048) **/
		throwError("Malformed API Class Object cannot except $apiMethod type request.", 2048, array('API Class'=>$apiClass, 'API Method'=>$apiMethod));
		exit();
	}
	
	
	
	/**
	 * Check Permissions
	 */
	$hasPermission = $loadObj->permission(array($apiMethod));
	if (!$hasPermission) {
	    /** User Request Error (2048) **/
	    throwError("Access is denied", 2048, array('API Class'=>$apiClass, 'API Method'=>$apiMethod));
	    exit();
	}



	/**
	 * Call API Method
	 */
	try {
		$apiResult 	= $loadObj->$apiMethod($apiParam);
	} catch (Exception $e) {
		/** Runtime Error (2048) **/
		throwError("Cannot call API Method: $apiClass->$apiMethod($strParam). ".$e->getMessage(), 2048, array('request'=>$stdParam, 'exception'=>$e));
	}



	/**
	 * Store API Result
	 */
	storeResult($apiResult, $loadObj);

	function storeResult($result, &$object) {
		global $apiResult;
		global $apiErrors;
		global $apiType;
		global $apiExit;

		$apiResult	= $result;
		$apiErrors 	= $object->errors();
		$apiType 	= ($object->isJSON()) ? 'json' : 'html/text';
		$apiExit	= $object->exitError();
	}
	

?>