<?php

    class authority implements api {
        
        private $json = false;

		public function isJSON() 	{return $this->json;}
		public function exitError()	{return false;}
		public function result()	{return array();}
		public function errors()	{return array();}
		
		private function permissionList() {
		    return array(
		        'permission',
		        'get',
		        'post',
		        'signin'
		    );
		}
		
		public function permission(array $param = array()) {
		    
		    if ($param) {
		        $result     = (in_array($param[0], $this->permissionList()));
		    } else {
		        $result     = $this->permissionList();
		    }
		    
		    return $result;
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
			header("Location: ./");
			return true;
		}

		public function put(	array $param = array()	) {
			return null;
		}

		public function delete(	array $param = array()	) {
			return null;
		}
		
		public function signin() {
		    return 
		        "
		        <form role=\"form\" method=\"post\" action=\"./\">
		            <input type=\"email\" name=\"email\" required placeholder=\"Email Address\"/>
		            <br/>
		            <input type=\"password\" name=\"password\" required placeholder=\"Password\"/>
		            <br/>
		            <button type=\"submit\">Sign In</button>
		        </form>
		        "
		    ;
		}

	}
	
?>
