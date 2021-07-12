<?php


namespace System\App\Forms;


trait FormDataTargets
{
    protected string $dataToggle = '';
    protected string $dataTarget = '';
    protected string $dataTextAlt = '';

    public function setDataToggle(string $string)
    {
        $this->dataToggle = $string;
        return $this;
    }

    public function setDataTarget(string $string)
    {
        $this->dataTarget = $string;
        return $this;
    }

    public function setDataTextAlt(string $string)
    {
        $this->dataTextAlt = $string;
        return $this;
    }
}