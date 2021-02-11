<?php
$form = new \System\App\Forms\Form('', 'refresh');
$button = new \System\App\Forms\FormButton('Refresh');
$button->addAJAXRequest(\System\Request::get()->getUri());
$closeModal = \App\Models\View\Javascript::on($button->getId(), 'resetSession()');
$button->setScript($closeModal);
$form->addElementToNewRow($button);
echo $form->print();
