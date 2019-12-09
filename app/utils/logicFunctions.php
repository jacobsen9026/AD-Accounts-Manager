<?php

function isStudent($username){
    debug ($username);
    if (intval(substr($username,0,2))<intval(01)){
        debug ("false");
        return false;
    }else{
        debug ("true");
        return true;
    }


}

function getYOGFromGrade($grade){
    global $appConfig;
    switch ($grade){
        case "8":
            return $appConfig["gradeMappings"]["8"];

        case "7":
            return $appConfig["gradeMappings"]["7"];

        case "6":
            return $appConfig["gradeMappings"]["6"];

        case "5":
            return $appConfig["gradeMappings"]["5"];

        case "4":
            return $appConfig["gradeMappings"]["4"];

        case "3":
            return $appConfig["gradeMappings"]["3"];

        case "2":
            return $appConfig["gradeMappings"]["2"];

        case "1":
            return $appConfig["gradeMappings"]["1"];

        case "K":
            return $appConfig["gradeMappings"]["K"];

        case "PK4":
            return $appConfig["gradeMappings"]["PK4"];

        case "PK3":
            return $appConfig["gradeMappings"]["PK3"];

        default:


    }

}


function getGradeFromYOG($yog){
    global $appConfig;
    include("./config/siteVariables.php");
    switch ($yog){
        case $appConfig["gradeMappings"]["8"]:
            return "8";

        case $appConfig["gradeMappings"]["7"]:
            return "7";

        case $appConfig["gradeMappings"]["6"]:
            return "6";

        case $appConfig["gradeMappings"]["5"]:
            return "5";

        case $appConfig["gradeMappings"]["4"]:
            return "4";

        case $appConfig["gradeMappings"]["3"]:
            return "3";

        case $appConfig["gradeMappings"]["2"]:
            return "2";

        case $appConfig["gradeMappings"]["1"]:
            return "1";

        case $appConfig["gradeMappings"]["K"]:
            return "K";

        case $appConfig["gradeMappings"]["PK4"]:
            return "PK4";

        case $appConfig["gradeMappings"]["PK3"]:
            return "PK3";

        default:


    }

}



function generateNewStudentPassword($grade)
{
    $randomNumber1 = rand (0 ,9 );
    $randomNumber2 = rand (0 ,9 );
    $randomNumber3 = rand (0 ,9 );
    $randomNumber4 = rand (0 ,9 );
    $randomNumber5 = rand (0 ,9 );
    $randomNumber6 = rand (0 ,9 );
    $randomLetter1 = randLetter();
    $randomLetter2 = randLetter();
    $randomLetters = $randomLetter1.$randomLetter2;


    switch($grade){
        case "8":
        case "7":
        case "6":
        case "5":
        case "4":
            return $randomLetters.$randomNumber1.$randomNumber2.$randomNumber3.$randomNumber4.$randomNumber5.$randomNumber6;
        case "3":
        case "2":
        case "1":
        case "K":
        case "PK4":
        case "PK3":
            return $randomLetters.$randomNumber1.$randomNumber2."1234";

        default:
            break;
    }
}



function randLetter()
{
    $int = rand(0,25);
    $a_z = "abcdefghijklmnopqrstuvwxyz";
    $rand_letter = $a_z[$int];
    return $rand_letter;
}


function notProtectedUsername($username){
    global $appConfig;
    foreach($appConfig["adminUsernames"] as $admin){
        //echo $admin;
        if(trim($admin)==trim($username)){
            return false;
        }
    }
    return true;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use PHPMailer\PHPMailer\POP;
use PHPMailer\PHPMailer\OAuth;
use PHPMailer\PHPMailer\SMTP;

require './lib/PHPMailer/src/Exception.php';
require './lib/PHPMailer/src/PHPMailer.php';
require './lib/PHPMailer/src/SMTP.php';
require './lib/PHPMailer/src/POP3.php';
require './lib/PHPMailer/src/OAuth.php';
	

function sendEmail2 ($to,$subject,$message){
	global $appConfig;
	$mail = new PHPMailer(true);

	try {
		//Server settings
		//$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
		$mail->isSMTP();                                            // Send using SMTP
		$mail->Host       = $appConfig["emailServerFQDN"];                    // Set the SMTP server to send through
		if(isset($appConfig["emailUseSMTPAuth"]) and $appConfig["emailUseSMTPAuth"]){
			debug("Using SMTP Auth");
			$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
			$mail->Username   = $appConfig["emailAuthUsername"];                     // SMTP username
			$mail->Password   = $appConfig["emailAuthPassword"];                               // SMTP password
		}else{
			debug("Not using SMTP Auth");
			$mail->SMTPAuth   = false;                                   // Disable SMTP authentication
		}
		
		if(isset($appConfig["emailUseSSL"]) and $appConfig["emailUseSSL"]!=""){
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
		}
		$mail->Port       = $appConfig["emailServerPort"];                                    // TCP port to connect to

		//Recipients
		debug ("From Address: ".$appConfig["emailFromAddress"]);
		$mail->setFrom($appConfig["emailFromAddress"], $appConfig["emailFromName"]);
		$mail->addAddress($to);     // Add a recipient
		//$mail->addAddress('ellen@example.com');               // Name is optional
		if(isset($appConfig["emailReplyToAddress"]) and $appConfig["emailReplyToAddress"]!=""){
			$mail->addReplyTo($appConfig["emailReplyToAddress"], $appConfig["emailReplyToName"]);
		}
		//$mail->addCC('cc@example.com');
		//$mail->addBCC('bcc@example.com');

		// Attachments
		//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

		// Content
		$mail->isHTML(true);                                  // Set email format to HTML
		$mail->Subject = $subject;
		$mail->Body    = $message;
		//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		$mail->send();
		Return 'Message has been sent';
	} catch (Exception $e) {
		Return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}

	
}



function sendEmail($to,$subject,$message){
    global $appConfig;
    $clientHostname=gethostbyaddr($_SERVER['REMOTE_ADDR']);
    $message=$message." <br/><br/> Client Hostname: ".$clientHostname." <br/> User: ".$_SESSION["userFirstName"]." ".$_SESSION["userLastName"];
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';

    // Additional headers
    $headers[] = 'From: '.$appConfig["emailFromName"].' <'.$appConfig["emailFromAddress"].'>';
    // Mail it
    debug($to);
    debug($subject);
    debug($message);
    debug($headers);
    $result = mail($to, $subject, $message, implode("\r\n", $headers));
    debug($result);
}

function sendNotificationEmail($subject,$message){
    global $appConfig;
    if(!appConfig["debugMode"]){
        $to='';
        //var_export $appConfig["adminEmails"];
        foreach($appConfig["adminEmails"] as $email){
            $to=$to.$email.",";
        }
        $to=substr($to,0,strlen($to)-1);
        //echo $to;
        $clientHostname=gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $message=$message." <br/><br/> Client Hostname: ".$clientHostname." <br/> User: ".$_SESSION["userFirstName"]." ".$_SESSION["userLastName"];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';

        // Additional headers
        $headers[] = 'From: '.$appConfig["emailFromName"].' <'.$appConfig["emailFromAddress"].'>';
        // Mail it
        $result = mail($to, $subject, $message, implode("\r\n", $headers));
    }
}


function sendWelcomeEmail($to){
    global $appConfig;
    if(strpos($to,"@".$appConfig["domainName"])==false){
        $to=$to."@".$appConfig["domainName"];
    }
    $bcc='Bcc: ';
    foreach($appConfig["adminEmails"] as $email){
        $bcc=$bcc.$email.",";
    }
    $bcc=substr($to,0,strlen($to)-1);
    foreach($appConfig["welcomeEmailReceivers"] as $email){
        $bcc=$bcc.$email.",";
    }
    $bcc=substr($to,0,strlen($to)-1);


    // Subject
    $subject = 'Welcome to $appConfig["domainNetBIOS"]';

    // Message
    $message=file_get_contents("./config/staffemail.html");

    // To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';

    // Additional headers
    $headers[] = 'From: '.$appConfig["emailFromName"].' <'.$appConfig["emailFromAddress"].'>';
    $headers[] = $bcc;

    // Mail it
    echo mail($to, $subject, $message, implode("\r\n", $headers));

}

function hostReachable ($hostname){
    $cmd = "ping $hostname -w 5 -n 1";
    $hostIP = gethostbyname ($hostname);
    $result=shell_exec($cmd);
    debug($result);
    //echo $result;
    if(strpos($result,"Response timed out")!=false){
        //echo "false";
        return false;
    }

    if(strpos($result,"could not find host")!=false){
        //echo "false";
        return false;
    }
    //echo "true";
    return true;



}




?>