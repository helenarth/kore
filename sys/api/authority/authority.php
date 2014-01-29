<?php

    class authority extends ui_api {
        
        public function __construct() {
            parent::__construct(__DIR__);
        }

		public function get(	array $param = array()	) {
		    if ($param) {
		        $method         = $param[0];
		        
		        if (method_exists($this, $method)) {
		            unset($param[0]);
		            $result     = $this->$method(array_values($param));
		            $this->json = true;
		        } else {
		            $result     = false;
		        }
		    } else {
		        $result         = (isset($_SESSION['type'])) ? $_SESSION['type'] : false;
		    }
		    
			return $result;
		}

		public function post(	array $param = array()	) {
		    $_SESSION['type'] = 'member';
			return array('reload'=>$_SERVER['HTTP_REFERER']);
		}

		public function put(	array $param = array()	) {
			return null;
		}

		public function delete(	array $param = array()	) {
			$_SESSION['type']   = 'guest';
			return array('reload'=>$_SERVER['HTTP_REFERER']);
		}
		
		public function signin() {
		    return $this->getPage(array('signin'), dirname(__FILE__));
		}
		
		public function register() {
		    return $this->getPage(array('register'), dirname(__FILE__));
		}

	}
	
?>
