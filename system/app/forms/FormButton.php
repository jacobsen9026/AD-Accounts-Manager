<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system\app\forms;

/**
 * Description of FormButton
 *
 * @author cjacobsen
 */
class FormButton extends FormElement implements FormElementInterface {

    private $target = '';
    private $theme = "primary";
    private $type = 'submit';

    //put your code here

    /**
     *
     * @param type $label
     * @param type $subLabel
     * @param type $size Must be one of: small,medium,large
     */
    function __construct(string $name, $size = "medium") {
        $this->setName($name);
        $this->setSize($size);
    }

    function getTheme() {
        return $this->theme;
    }

    function getType() {
        return $this->type;
    }

    function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function setTheme($theme) {
        $this->theme = $theme;
        return $this;
    }

    public function getTarget() {
        return $this->target;
    }

    public function getId() {
        if (parent::getId() == null) {
            return str_replace(" ", "", $this->getName()) . "Button";
        }
    }

    public function setTarget($target) {
        $this->target = $target;
        return $this;
    }

    /**
      public function getHTML() {
      switch ($this->getSize()) {
      case "large":


      break;
      case "medium":

      $html = '<div class="col-md-5 mx-auto"><div class="nav justify-content-center nav-pills h-100 p-3">';
      break;
      case "small":
      $html = '<div class="col-sm-3 mx-auto"><div class="nav justify-content-center nav-pills h-100 p-3">';
      break;

      default:
      break;
      }
      $html .= '
      <button type="' . $this->getType() . '" class="nav-link w-100 btn btn-' . $this->getTheme() . '">'
      . $this->getLabel() .
      '</button>
      </div></div>';
      $html .= $this->printScript();
      return $html;
      }
     *
     */
    public function getElementHTML() {
        $html = '
        <button id="' . $this->getID() . '" type="' . $this->getType() . '" class="nav-link my-3 w-100 btn btn-' . $this->getTheme() . '">'
                . $this->getName() .
                '</button>';
        return $html;
    }

    /**
     *
     * @param type $url
     * @param type $outputID
     * @param array $data
     */
    public function addAJAXRequest($url, $outputID = '', array $data = null) {
        $spinner = "<span class='spinner-border text-primary'>";
        $this->setScript('     $("#' . $this->getId() . '").on("click", function () {
                        console.log("works");
                        $("#' . $outputID . '").html("' . $spinner . '");
                        $.get("' . $url . '", function (data) {
                                    console.log("request completed");
                                    console.log(data);
                                    //now update the div with the new data
                                    $("#' . $outputID . '").attr("value",data);
                }
                );
                //$("#ldappermissiontestajaxOutput").slideDown("slow");
                });');
        return $this;
    }

}
