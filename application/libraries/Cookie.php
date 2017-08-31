<?php
class Cookie{
	function set($name, $value, $expire=false){
		
		if($expire === false){
			$expire = time()+365*24*3600;	
		}		
		setcookie($name, $value, $expire, "/");
	}
	
	function get($name) {
		return isset($_COOKIE[$name])&&!empty($_COOKIE[$name])?$_COOKIE[$name]:"";
	}
	
	function remove($name)
	{
		setcookie($name, "", (time()-3600));
	}
}
?>