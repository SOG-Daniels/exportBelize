<?php

/**
* This is a Log class used for logging errors and information pertaining to the system.
*/
class iLog
{	

	private $date;
	private $folder_name;
	private $time;
	private $current_Date;
	
	function __construct()
	{	
		
		$this->folder_name = "./logs";
		
		if(!file_exists ($this->folder_name)){
			//Creating a log folder if it doesn't exist	
			mkdir($this->folder_name, 0700);

		}

   		date_default_timezone_set('America/Belize');
	}
	//Creates an informational log
	public function info($string){
		$this->current_Date = date('Ymd');
		$this->date = date('Y-m-d ');
		$this->time = date('H:i:s');
	    $log = $this->date." ".$this->time." [INFO] ".$string."\r\n";

	    $file = $this->folder_name."/log_".$this->current_Date.".log";

	    if(!file_exists($file)){

	    	error_log($log, 3, $file);
	    }
	    else{

	    	error_log($log, 3, $file);

	    }
	}
	//Creats a error log 
	public function error($string){

		$this->current_Date = date('Ymd');
		$this->date = date('Y-m-d ');
		$this->time = date('H:i:s');
	    $log = $this->date." ".$this->time." [ERROR] ".$string."\r\n";
	    
	    $file = $this->folder_name."/log_".$this->current_Date.".log";

	    if(!file_exists($file)){

	    	error_log($log, 3, $file);
	    }
	    else{

	    	error_log($log, 3, $file);

	    }

	}
	
}
		
?>
