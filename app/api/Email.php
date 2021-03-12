<?php


namespace App\Api;


use App\Models\Database\EmailDatabase;
use System\App\AppLogger;

class Email
{

    /**
     * @param $to
     * @param $subject
     * @param $body
     * @param string $cc
     * @param string $bcc
     * @return array
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public static function sendMail($to, $subject, $body, $cc = '', $bcc = '')
    {
// Instantiation and passing `true` enables exceptions
        $mail = EmailDatabase::createMailer();
//Recipients
        try {
            $mail->addAddress($to);
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $body;
            if ($cc !== '') {
                $mail->addCC($cc);
            }

            if ($bcc !== '') {
                $mail->addBCC($bcc);
            }
            AppLogger::get()->debug(EmailDatabase::getSMTPServer());

            if (\App\App\App::inDebugMode()) {
                $debugLog = [];
                $mail->Debugoutput = function ($string, $level) {
                    $debugLog[] = $level . ': ' . $string;
                };
                $mail->SMTPDebug = 1;
            }
            $mail->send();
            if (\App\App\App::inDebugMode()) {
                AppLogger::get()->debug($debugLog);
            }
            return ('Message has been sent successfully');
        } catch (Exception $e) {
            return ("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }
}