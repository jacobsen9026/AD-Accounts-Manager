<?php

/*
 * The MIT License
 *
 * Copyright 2020 cjacobsen.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace System\App;

/**
 * Description of Email
 *
 * @author cjacobsen
 */

use App\Models\Database\DatabaseModel;
use App\Models\Database\EmailDatabase as EmailConfig;

class Email extends DatabaseModel
{

    /**
     *
     * @return \PHPMailer\PHPMailer\PHPMailer
     */
    private static function createMailer()
    {
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
//var_dump(EmailConfig::getSMTPServer());
//Server settings
        $mail->SMTPDebug = true;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host = EmailConfig::getSMTPServer();                    // Set the SMTP server to send through
        $mail->SMTPAuth = EmailConfig::getUseSMTPAuth();                                   // Enable SMTP authentication
        $mail->Username = EmailConfig::getSMTPUsername();                     // SMTP username
        $mail->Password = EmailConfig::getSMTPPassword();                               // SMTP password
        $mail->isHTML(true);
        //$mail->Sender = EmailConfig::getSMTPUsername();
        //$mail->AuthType = 'LOGIN';

        if (EmailConfig::getUseSMTPSSL()) {
            $mail->SMTPAutoTLS = true;
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        }
        $mail->Port = EmailConfig::getSMTPPort();                                  // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        $mail->setFrom(EmailConfig::getFromAddress());

        return $mail;
    }

    public static function sendTest($to = null)
    {
// Instantiation and passing `true` enables exceptions
        $mail = self::createMailer();
//Recipients
        try {

            $mail->addAddress($to);
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'SAM Email Test';
            $mail->Body = 'Your email settings are correct!';

            AppLogger::get()->debug(EmailConfig::getSMTPServer());

            $mail->send();
            return 'Message has been sent successfully';
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public function send($to, $cc, $bcc, $subject, $body, $replyTo)
    {

    }

    public function testConnection()
    {
        return false;
    }

    public static function saveSettings(array $postData)
    {
        // TODO: Implement saveSettings() method.
    }
}
