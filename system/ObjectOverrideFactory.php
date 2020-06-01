<?php


namespace System;


class ObjectOverrideFactory
{

    public function convert($obj, string $targetClas)
    {
        var_dump($obj);
        /* @var $sourceClass string */
        $sourceClass = get_class($obj);
        $sourceReflectionClass = new \ReflectionClass($sourceClass);
        $targetReflectionClass = new \ReflectionClass($targetClas);
        var_dump($targetReflectionClass);


        $sourceFields = get_class_methods($sourceClass);
        var_dump($sourceFields);
        //$newObj = new $targetClas ();
        foreach ($sourceFields as $sourceField) {

        }

    }

}