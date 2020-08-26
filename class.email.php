<?php
	
	require_once("./external/swiftMailer/autoload.php");

/**
* Email class allows for the email to a specified email.
*/
class Email 
{

	private $sender_Email;

	private	$sender_Name;
	private $sender_Password;
	//private $site_Link;
	private $subject;
	//private $body;
	
	function __construct()//link to a site of your choice
	{
		$this->set_Sender_Name("ExportBelize");
		$this->set_Sender_Email("beltraide.export.belize@gmail.com");
		$this->set_Sender_Password("Enter123*");
		//$this->set_Link($link);

	}

	function set_Sender_Email($sender){

		$this->sender_Email = $sender;
	}
	function get_Sender_Email(){

		return $this->sender_Email;
	}

	public function set_Sender_Name($name){

		$this->sender_Name = $name;

	}
	public function get_Sender_Name(){

		return $this->sender_Name;

	}
	public function set_Sender_Password($pass){

		$this->sender_Password = $pass;

	}
	public function get_Sender_Password(){

		return $this->sender_Password;

	}
	// public function set_Link($link){

	// 	$this->site_Link;

	// }
	// public function get_Link(){

	// 	return $this->site_Link;
	// }
	public function set_Subject($text){

		$this->subject = $text;
	}
	public function get_Subject(){

		return $this->subject;
	}
	// public function set_Html_Body($text){

	// 	$this->body = $text;
	// }
	// public function get_Html_Body(){

	// 	return $this->body;
	// }

	//takes two parameters the recievers name and the recievers email adress
	function send($recipient, $message){

			
		    $transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
			  ->setUsername ($this->sender_Email)
			  ->setPassword ($this->sender_Password)
			  ->setStreamOptions($arrayName = array('ssl' => array('allow_self_signed' => true , 'verify_peer'=>false )));

			$mailer = new Swift_Mailer($transport);

			$message = (new Swift_Message('Leave Application'))
			   ->setFrom (array( $this->sender_Email => $this->sender_Name))
			   ->setTo (array($recipient => 'To Whom it may concern'))
			   ->setSubject ($this->subject)
			   ->setBody ($message, 'text/html');

			$result = $mailer->send($message);

			return $result;
	}




}
?>
