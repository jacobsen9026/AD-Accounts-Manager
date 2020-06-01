<?php


namespace App\Controllers\Jobs;


use System\Jobs\Job;

class UpdateAppJob extends JobController
{
    public function initiateUpdate()
    {
        $command = APPPATH . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'jobs' .
            $job = new Job();

    }

}