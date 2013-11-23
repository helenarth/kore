<?php

	global $workingFolder;
	global $config;


	/**
	 * Config
	 */
	$configFolder			= ("$workingFolder/sys/config");
	$config 				= loadConfiguration($configFolder);



	function loadConfiguration($configFolder) {
		if (!realpath($configFolder))	return false;
		$items					= scandir($configFolder);
		$config 				= array();

		foreach ($items as $item) {
			if ($item = '.' or $item = '..') continue;

			$path = "$configFolder/$item";
			if (!realpath($path)) continue;

			if (is_dir($path)) {
				$cfgs 		= loadConfiguration($path);
				$config 	= array_merge($config, $cfgs);
			} elseif (is_file($path)) {
				$parts 		= explode('.', $item);
				$ext 		= $parts[count($parts)-1];

				if ($ext == 'conf' or $ext == 'cfg') {
					$config[]	= parse_ini_file($path, true);
				}

			}

		}

        return $config;
	}



	function loadLibraries() {

	}

	function startPreProcessors() {

	}

?>