<?php


namespace System\App\Forms;


class FormElementGroup extends FormElement implements FormElementInterface

{
    /**
     * @var array <FormElement>
     */
    private $elements = [];

    public function __construct($label = '', $subLabel = '', $name = '', $value = '')
    {
        parent::__construct($label, $subLabel, $name, $value);
        $this->auto();
        $this->setSize('large');
    }


    public function addElementToGroup(FormElement $element)
    {
        $this->elements[] = $element;
        return $this;
    }

    public function getElementHTML()
    {
        // TODO: Implement getElementHTML() method.
        $elementsOutput = '<!--FormElementGroup--> <div class="row">';
        $index = 0;
        $last = count($this->elements) - 1;
        foreach ($this->elements as $element) {
            //Modify sub elements here
            $element = $this->preProcessGroupElement($element, $index, $last);
            $elementsOutput .= '<!--FormElementElement--> ';
            $element->preProcess();

            if ($element->hideLabels() === false && $element instanceof FormTextArea === false) {
                $elementsOutput .= $element->printLabel() . "\n";
                $elementsOutput .= $element->printSubLabel() . "\n";
            }
            $elementsOutput .= $element->getElementHTML() . "\n";
            $elementsOutput .= $element->printScript() . "\n";
            $elementsOutput .= $element->printAJAX() . "\n";
            $elementsOutput .= $element->printModal() . "\n";
            //$elementsOutput .= '<!--FormElementElement--> <div class="col">';
            //$elementsOutput .= $element->print();
            //$elementsOutput .= '</div>';
            $index++;
        }

        $elementsOutput .= '</div>';
        return $elementsOutput;
    }

    private function preProcessGroupElement(FormElement $element, int $elementIndex, int $lastIndex)
    {

        $element->addElementClasses("p-0");
        if ($elementIndex < $lastIndex) {
            //var_dump("corner right");
            $element->addInputClasses('rounded-r-0');
        }
        if ($elementIndex > 0) {
            //var_dump("corner left");

            $element->addInputClasses('rounded-l-0');
        }
        return $element;
    }
}