<?php

	class ui extends ui_api {
	    
	    function __construct() {
	        parent::__construct(__DIR__);
	    }

		public function get(	array $param = array()	) {
			global $sessionType;

			$settings		= new settings('system');
			$config 		= $settings->ui;

			$reqPath		= implode('/', $param).".phtml";
			$uiPath			= "./ui/$sessionType";
			$defaultPath 	= "{$config['default']}.phtml";
			$reqPath 		= realpath("$uiPath/$reqPath");

			if (!$reqPath) $reqPath	= "$uiPath/$defaultPath";
			$realPath 		= realpath($reqPath);

			if (!$realPath) {
				// Core Error
				$msg 				= "UI Missing";				
				throwError($msg, 16, array('UI Handler'=>$reqPath));
				exit();
			}

			$buffer 		= null;
			$included		= $this->bufferRequest($realPath, $buffer);
			$this->buildHtml($buffer);

			if (!$included) {
				// Runtime Error
				$msg 				= "Request failed to be included";
				throwError($msg, 2048, array('UI Handler'=>$reqPath));
				exit();	
			}

			if (!$buffer) {
				// Runtime Error
				$msg 				= "Request returned an emtpy file";
				throwError($msg, 2048, array('UI Handler'=>$reqPath));
				exit();		
			}

			return $buffer;
		}

		private function bufferRequest($reqPath, &$buffer) {
			ob_start();
			$included 	= include($reqPath);
			$buffer 	= ob_get_clean();
			return $included;
		}

		private function buildHtml(&$body) {
		    $version    = getVersion();
			$headers 	= str_replace("\n", "\n\t\t", $this->htmlHeaders());
			$body 	    = str_replace("\n", "\n\t\t", $body);
			$buffer     = $this->getPage(array('frame'), dirname(__FILE__), true);
			$body       = str_replace(
			    array('%header%', '%body%', '%name%', '%author%', '%version%'),
			    array($headers, $body, 'Kore', 'VisionMise', $version),
			    $buffer
		    );
			
		}

		private function htmlHeaders() {
            $settings		= new settings('system');
			$config 		= $settings->assets;
			$types          = $config['types'];
			$buffer         = null;
			
			foreach ($types as $type) {
			    
			    $files = $config[$type];
			    foreach ($files as $index => $source) {
			        $str    = null;
			        if (strpos($source, '//') === false) $source = "assets/$type/$source";
			        
			        switch ($type) {
			             case 'css':
			                 $str       = "<link rel=\"stylesheet\" href=\"$source\"/>";
		                 break;
			             
			             case 'js':
			                 $str       = "<script src=\"$source\"></script>";
			             break;
			        }
			        
			        if (!$source or !$str) continue;
			        
			        $buffer .= "$str\n";
			    }
			}
			
			return trim($buffer);
		}

	}

?>