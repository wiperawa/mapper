<?php
declare(strict_types=1);

namespace wiperawa\mapper\traits;


trait ExtractTrait {
    
    /**
     * Export object fields listed at [[fieldsToExtract()]] to $target
     * $target can either be array, object, or a className
     *
     * @param string|object|array $target
     * @return mixed
     */
    public function extract($target){

        return $this->extractFields($target, $this->fieldsToExtract());
    }

    /**
     *
     * @param string|array|object $target
     * @param array $fields
     * @return mixed
     */
    private function extractFields( $target, array $fields ){

        $ignoredFields = $this->ignoredFields();

        if (is_string($target)) {
            $result = new $target;
        } elseif (is_object($target) ) {
            $result = clone $target;
        } else {
            $result = [];
        }

        foreach ($fields as $targetFieldName => $sourceFieldName) {

            if (in_array($sourceFieldName,$ignoredFields)) {
                continue;
            }

            $targetFieldName = ( is_int($targetFieldName) ? $sourceFieldName : $targetFieldName );

            $value = $this->resolveValue($sourceFieldName);

            if (is_array($result)) {
                $result[$targetFieldName] = $value;

            } else {
                $result = $this->fillObjectField($target, $targetFieldName, $value);
            }
        }

        return $result;
    }

    /**
     * Returns list of Fields that should be extracted to external DTO by [[export()]] function.
     * By default it returns list of current object fields when no specific fields are specified.
     *
     * You may specify your own list of fields to be returned, depends on some conditions,
     *
     * For example, the following code declares four fields:
     *
     * - `email`: the field name is the same as the property name `email`;
     * - `firstName` and `lastName`: the field names are `firstName` and `lastName`, and their
     *   values are obtained from the `first_name` and `last_name` properties;
     * - `fullName`: the field name is `fullName`. Its value is obtained by concatenating `first_name`
     *   and `last_name`.
     *
     * ```php
     * return [
     *     'email',
     *     'firstName' => 'first_name',
     *     'lastName' => 'last_name',
     *     'fullName' => function ($object) {
     *         return $object->first_name . ' ' . $object->last_name;
     *     },
     *      //Can use dots to read properties of nested objects
     *     'address' => [
     *          'country' => 'location.country',
     *          'city' => 'location.city',
     *          'state' => 'location.state',
     *          'address' => 'location.address,
     *      ],
     *      //If first item is a className, this class will be created and filled with listed fields
     *      'phone' => [
     *          PhoneDTO::class,
     *          'mobile_phone' => 'mobile_phone',
     *          'home_phone' => 'home_phone',
     *          'work_phone' => 'work_phone',
     *       ]
     *
     * ];
     * ```
     * In this method, you may also want to return different lists of fields based on some context
     * information. For example, depending on the privilege of the current application user,
     * you may return different sets of visible fields or filter out some fields.
     *
     * @return array the list of field names to be extracted to external DTO
     */
    private function fieldsToExtract(): array {

        $fields = method_exists($this,'fields') ?
            $this->fields() :
            array_keys(get_object_vars($this));

        return array_combine($fields, $fields);
    }

    /**
     * List fields that will be ignored during the extraction.
     *
     * @return array
     */
    private function ignoredFields():array {
        return [];
    }



    /*
     * Resolving value depends on FieldName of exporting object
     *
     * @return mixed
     */
    private function resolveValue($sourceFieldName) {

        if (is_string($sourceFieldName)) {
            return $this->getNestedValue($sourceFieldName, $this);
        }

        if (is_callable($sourceFieldName)) {
            return call_user_func($sourceFieldName, [$this]);
        }

        if (is_array($sourceFieldName) ){
            if (!empty($sourceFieldName) && class_exists(($targetClassName = reset($sourceFieldName)))) {

                return $this->extractFields(
                    new $targetClassName,
                    array_slice($sourceFieldName, 1)
                );

            } else {
                return $this->extractFields([], $sourceFieldName);
            }
        }
        return null;
    }

    /**
     * Getting value of field, if fieldName is a nested field.
     * For example if fieldName = 'user.location.state.stateName' will go recursively by objects and return stateName.
     *
     * @param string $fieldName
     * @param object $context
     * @return mixed
     */
    private function getNestedValue(string $fieldName, object $context) {
        $nestedFields = explode('.',$fieldName);
        if (count($nestedFields) === 1) {
            return $context->$fieldName;
        } else {
            return $this->getNestedValue($nestedFields[1], $context->$fieldName);
        }

    }

    /**
     * Filling destination object field, if possible.
     * fieldName of target object either should be accessible, or have a public setter.
     *
     * @param object $obj
     * @param string $fieldName
     * @param mixed $value
     * @return object
     * @throws \Error
     */
    private function fillObjectField (object $obj, string $fieldName, $value): object {
        $result = clone $obj;
        try {
            $result->$fieldName = $value;
        } catch (\Error $e) {
            $setter = 'set'.ucfirst($fieldName);
            if (is_callable([$result, $setter])) {
                $result->{$setter}($value);
                return $result;
            }
            throw $e;
        }
        return $result;
    }

}