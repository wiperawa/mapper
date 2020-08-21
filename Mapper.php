<?php
declare(strict_types=1);

namespace wiperawa\mapper;

Class Mapper
{

    /**
     * @var array Map of fields of exporting/hydrating model
     */
    private $map = [];

    /**
     * @var array ignored fields
     */
    private $ignoredFields = [];

    /**
     * Export object fields listed at [[fieldsToExtract()]] to $target
     * $target can either be array, object, or a className
     *
     * @param object $source
     * @param string|object|array $target
     * @return mixed
     */
    public function extract(object $source, $target = [])
    {
        if (!$this->map ) {
            $this->map = $this->getObjectFields($source);
        }
        return $this->extractFields($source, $this->map, $target);
    }

    /**
     *
     * @param $source
     * @param string|array|object $target defaults to []
     * @param array $fields
     * @return mixed
     */
    private function extractFields(object $source, array $fields, $target = [])
    {

        $ignoredFields = $this->ignoredFields;

        if (is_string($target)) {
            $result = new $target;
        } elseif (is_object($target)) {
            $result = clone $target;
        } else {
            $result = [];
        }

        foreach ($fields as $targetFieldName => $sourceFieldName) {

            if (in_array($sourceFieldName, $ignoredFields)) {
                continue;
            }

            $targetFieldName = (is_int($targetFieldName) ? $sourceFieldName : $targetFieldName);

            $value = $this->resolveValue($source, $sourceFieldName);
            if (is_array($result)) {
                $result[$targetFieldName] = $value;

            } else {
                $this->setObjectField($result, $targetFieldName, $value);
            }
        }

        return $result;
    }

    /**
     * Set of Fields that should be extracted to external DTO by [[export()]] function.
     * By default this is the list of current object fields when no specific fields are specified.
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
     *
     * @param array $map
     * @return self
     */

    public function setMap(array $map): self  {
        $new = clone $this;
        $new->map = $map;
        return $new;
    }

    /**
     * Returns object fields, if map isn't set.
     * first trying to call [[fields()]] on object, if cant, then getting just object vars.
     * *fields() function used in Yii2 framework, for example, to return object real/virtual fields.
     * @param object $object
     * @return array
     */
    private function getObjectFields(object $object): array {
        $fields = method_exists($object,'fields') ?
            $object->fields() :
            array_keys(get_object_vars($object));

        return array_combine($fields, $fields);
    }

    /**
     * Set ignored fields
     *
     * @param array $fields
     * @return self
     */
    public function setIgnoredFields(array $fields): self
    {
        $new = clone $this;
        $new->ignoredFields = $fields;
        return $new;
    }


    /*
     * Resolving value depends on FieldName of exporting object
     *
     * @return mixed
     */
    private function resolveValue(object $source,$sourceFieldName)
    {

        if (is_string($sourceFieldName)) {
            return $this->getNestedValue($source, $sourceFieldName);
        }

        if (is_callable($sourceFieldName)) {
            return call_user_func($sourceFieldName, $source);
        }

        if (is_array($sourceFieldName)) {
            if (!empty($sourceFieldName) &&
                is_string($targetClassName = reset($sourceFieldName)) &&
                class_exists($targetClassName)) {

                return $this->extractFields(
                    $source,
                    array_slice($sourceFieldName, 1),
                    new $targetClassName
                );

            } else {
                return $this->extractFields($source,$sourceFieldName);
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
    private function getNestedValue(object $context, string $fieldName)
    {

        $nestedFields = explode('.', $fieldName);
        if (count($nestedFields) === 1) {
            return $context->$fieldName;

        } else {
            return $this->getNestedValue($context->{$nestedFields[0]}, $nestedFields[1]);
        }

    }

    /**
     * Filling destination object field, if possible.
     * fieldName of target object either should be accessible, or have a public setter.
     *
     * @param object $obj
     * @param string $fieldName
     * @param mixed $value
     * @return void
     * @throws \Error|\Exception
     */
    private function setObjectField(object $obj, string $fieldName, $value)
    {

        $nestedFields = explode('.', $fieldName);
        if (count($nestedFields) > 1) {

            $this->setObjectField($obj->{$nestedFields[0]}, $nestedFields[1], $value);
        }
        try {
            $obj->$fieldName = $value;
        } catch (\Error|\Exception $e) {

            $setter = 'set' . ucfirst($fieldName);
            if (is_callable([$obj, $setter])) {
                $obj->{$setter}($value);
                return;
            }
            throw $e;
        }
    }

}