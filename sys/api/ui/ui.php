<?php

	class ui implements api {

		public function isJSON() 	{return false;}
		public function exitError()	{return false;}
		public function result()	{return array();}
		public function errors()	{return array();}

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

		private function buildHtml(&$buffer) {
			$headers 	= str_replace("\n", "\n\t\t", $this->htmlHeaders());
			$buffer 	= str_replace("\n", "\n\t\t", $buffer);
			$buffer		= 
				"<!DOCTYPE html>\n".
				"<html lang=\"EN-US\">\n".
				"\t<head>\n".
				"\t\t$headers\n".
				"\t</head>\n".
				"\t<body>\n".
				"\t\t$buffer\n".
				"\t</body>\n".
				"</html>"
			;
		}

		private function htmlHeaders() {
            $settings		= new settings('system');
			$config 		= $settings->assets;
			$types          = $config['types'];
			$buffer         = null;
			
			foreach ($types as $type) {
			    
			    $files = $config[$type];
			    foreach ($files as $index => $source) {
			        if (strpos($source, '//') == false) $source = "assets/$type/$source";
			        
			        switch ($type) {
			             case 'css':
			                 $str       = "<link rel=\"stylesheet\" href=\"$source\"/>";
		                 break;
			             
			             case 'script':
			                 $str       = "<script src=\"$source\"></script>";
			             break;
			        }
			        
			        $buffer .= "$str\n";
			    }
			}
			
			return trim($buffer);
		}

		public function post(	array $param = array()	) {
			return true;
		}

		public function put(	array $param = array()	) {
			return true;
		}

		public function delete(	array $param = array()	) {
			return true;
		}

	}

?>