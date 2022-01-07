<?php

session_start();
error_reporting(E_ALL & ~ E_NOTICE);

class Controller
{
    function __construct() {
        $this->processEmailVerification();
    }
    function processEmailVerification()
    {
        switch ($_POST["action"]) {
            
            case "get_otp":
                $email = $_POST['email'];
                $otp = rand(100000, 999999);
                $_SESSION['session_otp'] = $otp;
                $message = "Your one time email verification code is" . $otp;
                $sub = "Email verification from Deepa;
                $headers = "From : " . "deepa.com";
                try{
                    $retval = mail($email,$sub,$message);
                    if($retval)
                    {
                        require_once('otp-verification.php');
                    }
                }
                
                catch(Exception $e)
                {
                    die('Error: '.$e->getMessage());
                }
 
                break;
                
            case "verify_otp":
                $otp = $_POST['otp'];
                
                if ($otp == $_SESSION['session_otp']) 
                {
                   unset($_SESSION['session_otp']);
                   echo json_encode(array("type"=>"success", "message"=>"Your Email is verified!"));
                } 
                else {
                    echo json_encode(array("type"=>"error", "message"=>"Mobile Email verification failed"));
                }
                break;
        }
    }
}
$controller = new Controller();
?>