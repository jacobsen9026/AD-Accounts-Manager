<?php

namespace App\Models\View;


class Toast extends ViewModel
{
    private $header = '';
    private $body = '';
    private $timeout = 0;
    private $image = '';


    public function __construct(string $header, string $body, $timeout = 0)
    {
        parent::__construct();
        $this->setHeader($header)
            ->addToBody($body)
            ->setTimeout($timeout);

    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     *
     * @return Toast
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }


    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param string $header
     *
     * @return Toast
     */
    public function setHeader($header)
    {
        $this->header = $header;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     *
     * @return Toast
     */
    public function addToBody($body)
    {
        $this->body .= $body;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     *
     * @return Toast
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }


    public function printToast()
    {
        $toast = ['header' => $this->getHeader(), 'body' => $this->getBody(), 'timeout' => $this->getTimeout(), 'image' => $this->getImage()];

        $html = $this->view('layouts/toast', $toast);

        return $html;
    }


}