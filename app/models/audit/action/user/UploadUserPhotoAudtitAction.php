<?php


namespace App\Models\Audit\Action\User;

use App\Models\Audit\Action\AuditAction;

class UploadUserPhotoAudtitAction extends AuditAction
{

    /**
     * UploadUserPhotoAuditAction constructor.
     *
     * @param string $usernameSearchedFor
     */
    public function __construct(string $usernameSearchedFor)
    {

        $this->setType('User_Photo_Upload');
        $this->setDescription('Uploaded a photo for: ' . $usernameSearchedFor);

    }
}