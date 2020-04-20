<?php

$this->customRoutes[] = array("install", "index", "home", "index");

/*
 * Settings routes
 */
$this->customRoutes[] = array("Districts", "*", "settings\Districts", "*");
$this->customRoutes[] = array("Schools", "*", "settings\Schools", "*");
$this->customRoutes[] = array("Grades", "*", "settings\Grades", "*");
$this->customRoutes[] = array("Departments", "*", "settings\Departments", "*");
$this->customRoutes[] = array("Teams", "*", "settings\Teams", "*");

