<?php


namespace System\App\Forms;


class FormFloatingButton extends FormButton
{
    /**
     * FormFloatingButton constructor.
     * This button is a button that hides until the form is altered
     *
     * @param string $name
     * @param string $size
     */
    public function __construct(string $name, $size = "medium")
    {
        parent::__construct($name, $size);


        $this->tiny()
            ->addElementClasses("floating-form-button position-fixed")
            ->addInputClasses('rounded-circle p-2 mr-4 mb-4')
            ->setScript('');
    }


}