<?php

namespace App\Models\View;


class Toast extends ViewModel
{
    protected $header = '';
    protected $body = '';
    protected $timeout = 2000;
    protected $image = '';
    protected bool $closable = false;
    protected string $classes;
    protected string $id = '';
    protected $startShown = true;

    public function __construct(string $header, string $body, $timeout = 1000)
    {
        parent::__construct();
        $this->setHeader($header)
            ->addToBody($body)
            ->setTimeout($timeout);
        $this->classes = 'position-fixed center top';
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

    public function startHidden()
    {
        $this->startShown = false;
    }

    public function printToast()
    {
        $toast = ['toastClasses' => $this->getClasses(),
            'header' => $this->getHeader(),
            'body' => $this->getBody(),
            'timeout' => $this->getTimeout(),
            'image' => $this->getImage(),
            'closable' => $this->closable,
            'id' => $this->id,
            'shown' => $this->startShown];

        $html = $this->view('layouts/toast', $toast);

        return $html;
    }

    /**
     * @return string
     */
    public function getClasses(): string
    {
        return $this->classes;
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

    public function closable()
    {
        $this->closable = true;
    }

    public function bottom()
    {
        $this->removeClasses('top')
            ->addClasses('bottom');
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

    public function setId(string $string)
    {
        $this->id = $string;
        return $this;
    }


}