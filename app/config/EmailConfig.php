<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\config;

/**
 * Description of EmailConfig
 *
 * @author cjacobsen
 */
use system\app\CoreConfig;

class EmailConfig extends CoreConfig {

    //put your code here
    private $fromAddress;
    private $fromName;
    private $admins;
    private $welcomeBCC;
    private $welcomeEmail;
    private $smtpServer;
    private $smtpPort;
    private $useSMTPAuth;
    private $useSMTPSSL;
    private $smtpUsername;
    private $smtpPassword;
    private $replyToAddress;
    private $replyToName;

}
