<?php

namespace App\Models\View;


class Toast extends ViewModel
{
    private $header = '';
    private $body = '';
    private $timeout = 2000;
    private $image = '';
    private bool $closable = false;
    private string $classes;

    public function __construct(string $header, string $body, $timeout = 0)
    {
        parent::__construct();
        $this->setHeader($header)
            ->addToBody($body)
            ->setTimeout($timeout);
        $this->classes = 'position-fixed  center top';
    }

    /**
     * @return string
     */
    public function getClasses(): string
    {
        return $this->classes;
    }

    /**
     * @param string $classes
     *
     * @return Toast
     */
    public function addClasses(string $classes): Toast
    {
        if (is_string($classes)) {
            $this->classes = trim(str_replace("  ", " ", $this->classes)) . ' ' . trim($classes);
        } elseif (is_array($classes)) {
            foreach ($classes as $class) {
                $this->classes = trim(str_replace("  ", " ", $this->classes)) . ' ' . trim($class);
            }
        }
        return $this;
    }

    public function removeClasses(string $classes): Toast
    {
        $this->classes = str_replace($classes, '', $this->getClasses());
        return $this;
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
        $toast = ['toastClasses' => $this->getClasses(), 'header' => $this->getHeader(), 'body' => $this->getBody(), 'timeout' => $this->getTimeout(), 'image' => $this->getImage(), 'closable' => $this->closable];

        $html = $this->view('layouts/toast', $toast);

        return $html;
    }

    public function closable()
    {
        $this->closable = true;
    }

    public function bottom()
    {
        $this->removeClasses('top')
            ->addClasses('bottom');
    }


}