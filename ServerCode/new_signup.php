<?php
    function getVerificationCode()
    {
        $result = "";
        $chars = "0123456789";
        $charArray = str_split($chars);
        
        for($i = 0; $i < 4; $i++){
            $randItem = array_rand($charArray);
            $result .= $charArray[$randItem];
        }
        
        return $result;
    }

  $args = (!empty($_REQUEST)) ? $_REQUEST:array('mode'=> 'none');
  if($args['mode'] == 'email'){
       $email = $args['email'];
       $code = getVerificationCode();
        
       $to = $email;
       $subject = "BarFliz Verification code";
       $message = "Use this BarFliz verification code.\r\n'$code'";
       $header = "From:centre@barfliz.com \r\n";
       $retval = mail ($to,$subject,$message, $header);
    
        if( $retval == true )  
        {
           die($code);
        }
        else
        {
            die("fail");
        }
  }else{
       $number = $args['number'];
       $code = getVerificationCode();
       
        require 'class-Clockwork.php';
        
       try
        {
            $API_KEY = "b769da88a43b7069835c5f44754ec22dea473955";
            // Create a Clockwork object using your API key
            $clockwork = new Clockwork( $API_KEY );
         
            // Setup and send a message
             $content = "Use this BarFliz verification code.\r\n'$code'";   
            
            $message = array( 'to' => $number, 'message' => $content );
            $result = $clockwork->send( $message );
         
            // Check if the send was successful
            if($result['success']) {
               die($code);
            } else {
                die('fail');
            }
        }
        catch (ClockworkException $e)
        {
            die('fail');
        }
  }
?>
