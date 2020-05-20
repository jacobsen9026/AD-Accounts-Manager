<a class="text-primary text-decoration-none fas fa-arrow-circle-left mb-3" href="/groups"></a>
<?php

use App\Models\View\CardPrinter;
use System\App\Forms\Form;
use System\App\Forms\FormButton;
use System\App\Forms\FormText;
use App\Models\District\Group;

/* @var $group Group */
$group = $this->group;
//var_dump($this->group);
$createButton = new FormButton("X");
$createButton->tiny()
        ->setTheme("danger")
        ->removeInputClasses("w-100")
        ->addInputClasses("position-absolute right-10")
        ->addElementClass("top right pr-5");
$createModal = new \App\Models\View\Modal();
$createModal->setBody($this->view('/groups/delete', ['name' => $group->getName(), 'distinguishedName' => $group->getDistinguishedName()]))
        ->setId('deleteGroup')
        ->setTheme('danger ')
        ->setTitle("Delete " . $group->getName());
$createButton->setModal($createModal);
echo $createButton->print();
echo CardPrinter::printCard($this->group, $this->user);
