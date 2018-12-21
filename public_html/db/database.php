<?php
/*
* Mysql database class - only one connection alowed
*/
class Database {
	private $_connection;
	private static $_instance; //The single instance
    
	/*
	Get an instance of the Database
	@return Instance
	*/
	public static function getInstance() {
		if(!self::$_instance) { // If no instance then make one
			self::$_instance = new self();
		}
		return self::$_instance;
    }
    
	// Constructor
	private function __construct() {
        $this->conn = null;

        try{
            $raw = file_get_contents(__DIR__ . "/.credentials");
            $json = json_decode($raw, true);
        }catch(Exception $e){
            trigger_error("Credentials error: " . $e->getMessage());
            return;
        }

        try {
            $this->_connection = new PDO("mysql:host=$json->host;port=3306;dbname=$json->database", $json->username, $json->password);
        }catch(PDOException $e){
            trigger_error("Connection error: " . $e->getMessage());
        }
    }

    //Destructor
    function __destruct(){
        if(isset($this->_connection)){
            $this->_connection = null;
        }
    }
    
	// Magic method clone is empty to prevent duplication of connection
    private function __clone() { }
    
	// Get mysqli connection
	public function getConnection() {
		return $this->_connection;
	}
}
?>