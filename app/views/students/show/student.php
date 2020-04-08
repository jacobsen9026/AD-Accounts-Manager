<a class="text-primary text-decoration-none fas fa-arrow-circle-left mb-3" href="/students/account-status"></a>
<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use app\models\district\CardPrinter;
//var_dump($this->student);
 echo CardPrinter::buildCard($this->student,$this->user);
//echo $this->student->getCard();
