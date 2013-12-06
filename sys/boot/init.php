<?php

	global $workingFolder;



	/**
	 * Config
	 */
	$configFolder			= ("$workingFolder/sys/config");
	$config 				= loadConfiguration($configFolder);



	/**
	 * Libraries
	 */
	$libFolder				= ("$workingFolder/sys/lib");
	$libs 					= loadLibraries($libFolder);



	/** Exec **/
	$execFolder 			= ("$workingFolder/sys/exec");
	$exec 					= startPreProcessors($execFolder);



	function loadConfiguration($configFolder) {
		if (!realpath($configFolder))	return false;
		$items					= scandir($configFolder);
		$config 				= array();

		foreach ($items as $item) {
			if ($item == '.' or $item == '..') continue;

			$path = "$configFolder/$item";
			if (!realpath($path)) continue;

			if (is_dir($path)) {
				$cfgs 		= loadConfiguration($path);
				$config 	= array_merge($config, $cfgs);
			} elseif (is_file($path)) {
				$parts 		= explode('.', $item);
				$ext 		= $parts[count($parts)-1];
				$name 		= str_replace(".$ext", null, $item);

				if ($ext == 'conf' or $ext == 'cfg') {
					$config[$name]	= parse_ini_file($path, true);
				}

			}

		}

        return $config;
	}


	function loadLibraries($libFolder) {
		if (!realpath($libFolder))	return false;
		$items					= scandir($libFolder);
		$libs 					= array();

		foreach ($items as $item) {
			if ($item == '.' or $item == '..') continue;

			$path = "$libFolder/$item";
			if (!realpath($path)) continue;

			if (is_dir($path)) {
				$list 		= loadLibraries($path);
				$libs 		= array_merge($libs, $list);
			} elseif (is_file($path)) {
				$parts 		= explode('.', $item);
				$ext 		= $parts[count($parts)-1];

				if ($ext == 'php' or $ext == 'lib') {
					$libs[$item]	= include($path);
				}

			}

		}

        return $libs;
	}


	function startPreProcessors($execFolder) {
		if (!realpath($execFolder))	return false;
		$items					= scandir($execFolder);
		$exec 					= array();

		foreach ($items as $item) {
			if ($item == '.' or $item == '..') continue;

			$path = "$execFolder/$item";
			if (!realpath($path)) continue;

			if (is_dir($path)) {
				$list 		= startPreProcessors($path);
				$exec 		= array_merge($exec, $list);
			} elseif (is_file($path)) {
				$parts 		= explode('.', $item);
				$ext 		= $parts[count($parts)-1];

				if ($ext == 'php' or $ext == 'exec') {
					$exec[$item]	= include($path);
				}

			}

		}

        return $exec;
	}


?>