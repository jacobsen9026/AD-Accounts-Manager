<?php


namespace system;


class Header
{
    const APPLICATION_JSON = 'application/json';
    const IMAGE_MIXED = 'image/png|jpeg';
    const IMAGE_PNG = 'image/png';
    const APPLICATION_JAVASCRIPT = 'text/javascript';
    const APPLICATION_CSV = 'application/csv';

    public static function sendFile($rawString, $contentType, $cache = true, $attachedFilename = null)
    {

        header('content-type: ' . $contentType);

        if (!Core::inDebugMode()) {
            if ($attachedFilename !== null) {
                header('Content-Disposition: attachment; filename=' . $attachedFilename);
            }
            if ($cache !== true) {
                header('Pragma: no-cache');
                header('Cache-Control: no-store, no-cache');
            } else {
                header_remove('Pragma');
                header('Cache-Control: max-age=9999999');
                header_remove('Expires');
                header_remove('Connection');
            }
        }

        echo $rawString;

        self::exit();

    }

    private static function exit()
    {
        if (!Core::inDebugMode()) {

            exit();
        } else {
            echo "Would have exited execution.";
            exit();
        }
    }

    public static function allowServiceWorker(string $scope)
    {
        header('service-worker-allowed: ' . $scope);
    }

    public static function fileNotFound()
    {
        http_response_code(404);
        self::exit();
    }

}