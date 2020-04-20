<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system\app;

/**
 * Description of Email
 *
 * @author cjacobsen
 */
use app\models\Email as EmailConfig;

class Email extends \system\Parser {

    /**
     *
     * @return \PHPMailer\PHPMailer\PHPMailer
     */
    private static function createMailer() {
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
//var_dump(EmailConfig::getSMTPServer());
//Server settings
        $mail->SMTPDebug = true;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host = EmailConfig::getSMTPServer();                    // Set the SMTP server to send through
        $mail->SMTPAuth = EmailConfig::getSMTPAuth();                                   // Enable SMTP authentication
        $mail->Username = EmailConfig::getSMTPUsername();                     // SMTP username
        $mail->Password = EmailConfig::getSMTPPassword();                               // SMTP password
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port = EmailConfig::getSMTPPort();                                  // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        $mail->setFrom(EmailConfig::getFromAddress());
        return $mail;
    }

    public static function sendTest($to = null) {
        if ($to == null) {
            $to = 'cjacobsen@branchburg.k12.nj.us';
        }
// Instantiation and passing `true` enables exceptions
        $mail = self::createMailer();
//Recipients
        try {

            $mail->addAddress($to);     // Add a recipient
//$mail->addAddress('ellen@example.com');               // Name is optional
//$mail->addReplyTo('info@example.com', 'Information');
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');
// Attachments
//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
// Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'SAM Email Test';
            $mail->Body = 'Your email settings are correct!';
//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            AppLogger::get()->debug(EmailConfig::getSMTPServer());
//AppLogger::get()->debug($mail->smtpConnect());


            $mail->send();
            echo 'Message has been sent successfully';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public function send($to, $cc, $bcc, $subject, $body, $replyTo) {

    }

    public function testConnection() {
        return false;
    }

}
