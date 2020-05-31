<?php


namespace System;


class ObjectOverrideFactory
{

    public function convert($obj, string $targetClas)
    {
        /* @var $sourceClass string */
        $sourceClass = get_class($obj);
        $sourceReflectionClass = new \ReflectionClass($sourceClass);
        var_dump($sourceReflectionClass->getConstructor());
        $sourceFields = get_class_vars($sourceClass);
        $newObj = new $targetClas ();
        foreach ($sourceFields as $sourceField) {

        }

    }

}