<?php
namespace LaraPackage\Api\Contracts\Validation;

interface Relation extends Transform
{
    /**
     * Validation rules for creating a relation
     *
     * @return array
     */
    public function createRelationsValidationRules();

    /**
     * Validation rules for replacing a relation
     *
     * @return array
     */
    public function replaceRelationsValidationRules();
}
