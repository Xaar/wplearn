<?php
namespace Eye8\FlPlaylist;

abstract class Plugin {

	private static $instance;
	
	private function __construct(){ }
	
	abstract public function bind();

    public static function getInstance(){
    	
		$class = get_called_class();
	
        if (!isset(self::$instance))
            self::$instance = array();

		if(!isset(self::$instance[$class]))
			self::$instance[$class] = new $class;
		
        return self::$instance[$class];
    }

    public function __clone(){
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }	
}

?>