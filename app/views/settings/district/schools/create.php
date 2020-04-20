<?php

use app\database\Schema;
use system\app\forms\Form;
use system\app\forms\FormText;
use system\app\forms\FormButton;
use app\models\district\DistrictDatabase;
use app\models\district\School;

$schools = (DistrictDatabase::getSchools2($this->districtID));


foreach ($schools as $school) {
    /* @var $school School */
    //echo $school->getAbbr();
    $createSchoolForm = new Form("/settings/schools/create/" . $this->districtID, 'createSchool');
    $schoolAbbr = new FormText('', 'Abbr', 'abbr', $school->getAbbr());
    //$schoolAbbrHidden = new FormText('', 'Abbr', 'abbr', $school->getAbbr());
    $schoolName = new FormText('', 'Display Name', 'name', $school->getName());
    //$schoolNameHidden = new FormText('', 'Display Name', 'name', $school->getName());
    $schoolOUHidden = new FormText('', '', 'ou', $school->getOu());
    $schoolOUHidden->hidden();
    $schoolAbbr//->disable()
            ->small();
    //$schoolName->disable();
    ///$schoolAbbrHidden->hidden();
    //$schoolNameHidden->hidden();

    $createButton = new FormButton("Create " . $school->getAbbr());
    $createButton->small();
    $createSchoolForm->addElementToNewRow($schoolAbbr)
            //->addElementToCurrentRow($schoolAbbrHidden)
            ->addElementToCurrentRow($schoolName)
            //->addElementToCurrentRow($schoolNameHidden)
            ->addElementToCurrentRow($schoolOUHidden)
            ->addElementToCurrentRow($createButton);
    echo $createSchoolForm->print();
}
?>