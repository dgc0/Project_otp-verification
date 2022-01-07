<?php
session_start();
error_reporting(E_ALL & ~ E_NOTICE);
require ('textlocal.class.php');

class Controller
{
    function __construct() {
        $this->processMobileVerification();
    }
    function processMobileVerification()
    {
     switch ($_POST["action"]) {
     case "get_otp":
                
        $mobile_number = $_POST['mobile_number'];
        $name = $_POST['name'];
        $db = mysqli_connect('localhost', 'your user name', '(your password)', 'your database');
        $user_check_query = "SELECT * FROM reg_user WHERE mobile='$mobile_number' LIMIT 1";
 		$result =  mysqli_query($db, $user_check_query);
  		if ($result === FALSE) {
 		  die(mysqli_error($connect));
		}
  		$user = mysqli_fetch_assoc($result);
  		if($user)
  		{
  		if ($user['mobile'] === $mobile_number) 
  		{
  		  echo json_encode(array("Mobile Number already Exists. Enter another."));
  		  exit;
    	}
  		}
        $apiKey = urlencode('put your api key here');
        $Textlocal = new Textlocal(false, false, $apiKey);
        $numbers = array($mobile_number);
        $sender = 'TLTEST';
        $otp = rand(100000, 999999);
        $_SESSION['session_otp'] = $otp;
        $_SESSION['mobile_number'] = $mobile_number;
        $_SESSION['name'] = $name;
        $message = "Your otp is " . $otp;
        try{
          $response = $Textlocal->sendSms($numbers, $message, $sender);
          require_once ("otp-verification-form.php");
          exit();
        }catch(Exception $e){
         die('Error: '.$e->getMessage());
        }
        break;
                
    case "verify_otp":
        $otp = $_POST['otp'];
        if ($otp == $_SESSION['session_otp']) 
        {
            unset($_SESSION['session_otp']);
            $mobile = $_SESSION['mobile_number'];
            $name = $_SESSION['name'];
           	unset($_SESSION['mobile_number']); 
           	unset($_SESSION['name']);
            $db = mysqli_connect('localhost', 'your user name', '(your password)', 'your database');
            $password = md5($otp);    
            $query =  "INSERT INTO reg_user ( fname,mobile,password) VALUES( '$fname','$mobile','$password')";
  			$result1 = mysqli_query($db, $query);
  			if ($result === FALSE) 
  			{
  			   die(mysqli_error($connect));
  			   echo json_encode(array("type"=>"error", "message"=>"Mobile number registration failed."));
  			   exit;
 			}

            $apiKey = urlencode('your api key');
            $Textlocal = new Textlocal(false, false, $apiKey);
            $numbers = array($mobile);
            $sender = 'TLTEST';
            $message = "Welcome. Your Registration is successful. Login to your Account and Update your details. Your password is " . $otp;
            try
            {
                	
              $Textlocal->sendSms($numbers, $message, $sender);
              echo json_encode(array("type"=>"success", "message"=> "Mobile number is Registered. Password has been sent to registered Mobile Number. Login to fillup the details" ));
              exit();
            }
            catch(Exception $e)
            {
              die('Error: '.$e->getMessage());
            }
            }
            else{
                 echo json_encode(array("type"=>"error", "message"=>"Mobile number verification failed"));
            }
            break;
        }
    }
}
$server = new Controller();
?>