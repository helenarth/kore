<?php

	//error_reporting(0);
	set_error_handler("errorHandler");

	function errorHandler($errno, $errmsg, $filename, $linenum, $vars) {
	    $dt  			= date("Y-m-d H:i:s (T)");
	    $type 			= 'system';
	    $errortype 		= array (
	        E_ERROR              => 'Error',
	        E_WARNING            => 'Warning',
	        E_PARSE              => 'Parsing Error',
	        E_NOTICE             => 'Notice',
	        E_CORE_ERROR         => 'Core Error',
	        E_CORE_WARNING       => 'Core Warning',
	        E_COMPILE_ERROR      => 'Compile Error',
	        E_COMPILE_WARNING    => 'Compile Warning',
	        E_USER_ERROR         => 'User Error',
	        E_USER_WARNING       => 'User Warning',
	        E_USER_NOTICE        => 'User Notice',
	        E_STRICT             => 'Runtime Notice',
	        E_RECOVERABLE_ERROR  => 'Catchable Fatal Error'
	    );

	    $user_errors 	= array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);
	    
	    $err = "<errorentry>\n";
	    $err .= "\t<datetime>" . $dt . "</datetime>\n";
	    $err .= "\t<errornum>" . $errno . "</errornum>\n";
	    $err .= "\t<errortype>" . $errortype[$errno] . "</errortype>\n";
	    $err .= "\t<errormsg>" . $errmsg . "</errormsg>\n";
	    $err .= "\t<scriptname>" . $filename . "</scriptname>\n";
	    $err .= "\t<scriptlinenum>" . $linenum . "</scriptlinenum>\n";

	    if (in_array($errno, $user_errors)) {
	        $err .= "\t<vartrace>" . wddx_serialize_value($vars, "Variables") . "</vartrace>\n";
	        $type = 'user';
	    }

	    $err .= "</errorentry>\n\n";

	    $logger 		= new log();
	    $logger->$type 	= $err;
	}

?>