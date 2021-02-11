<?php

use App\Models\View\CardPrinter;

use App\Models\District\Group;

/* @var $group Group */
$group = $this->group;

echo CardPrinter::printCard($this->group);
