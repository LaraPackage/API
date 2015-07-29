<?php
namespace LaraPackage\Api\Contracts\Validation;

interface Entity extends Transform
{
    /**
     * Validation rules for creating an entity
     *
     * @return array
     */
    public function createValidationRules();

    /**
     * Validation rules for replacing an entity
     *
     * @param int $id
     *
     * @return array
     */
    public function replaceValidationRules($id);

    /**
     * Validation rules for updating an entity
     *
     * @param int $id
     *
     * @return array
     */
    public function updateValidationRules($id);

    /**
     * Validation messages to be used instead of the default ones
     *
     * @return array
     */
    public function validationMessages();
}
