<?php


namespace App\Controllers;


use App\Models\Database\AppDatabase;
use App\Models\Domain\DomainUser;
use System\App\AppException;
use System\App\Picture;
use system\Header;

class Img extends FileController
{
    public function index()
    {
        return $this->favicon();
    }

    public function favicon($requestedFavicon = null)
    {
        Header::sendFile(Picture::resize(AppDatabase::getAppIcon(), 64, 64), Header::IMAGE_MIXED);
    }

    public function logo($request)
    {
        $fileType = explode(".", $request)[1];
        $resolution = explode("x", explode(".", $request)[0]);
        $logo = Picture::resize(AppDatabase::getAppIcon(), $resolution[0], $resolution[1]);
        Header::sendFile($logo, Header::IMAGE_MIXED);

    }

    public function user($username)
    {
        try {
            $user = new DomainUser($username);
        } catch (AppException $ex) {
            Header::fileNotFound();
        }
        Header::sendFile($user->activeDirectory->getThumbnail(), Header::IMAGE_MIXED, true, $username . '.jpg');
    }
}