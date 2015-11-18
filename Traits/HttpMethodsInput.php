<?php

namespace Minds\Traits;

trait HttpMethodsInput
{
    /**
     * Returns a $_GET value (fail-safe)
     * @param  string $key
     * @param  array  $options See getInputValue()
     * @return mixed
     */
    protected static function getQueryValue($key, array $options = [])
    {
        return static::getInputValue($key, $_GET, $options);
    }

    /**
     * Returns a $_POST value (fail-safe)
     * @param  string $key
     * @param  array  $options See getInputValue()
     * @return mixed
     */
    protected static function getPostValue($key, array $options = [])
    {
        return static::getInputValue($key, $_POST, $options);
    }

    /**
     * Returns a fail-safe value from an array or dictionary.
     * @param  string $key
     * @param  array  &$input  Source array / dictionary
     * @param  array  $options `required` => true will throw an Exception when the key is missing
     * @return mixed
     */
    protected static function getInputValue($key, array &$input, array $options = [])
    {
        if (!isset($input[$key]) && !empty($options['required'])) {
            throw new \UnexpectedValueException("Missing required key: {$key}");
        } elseif (!isset($input[$key])) {
            return null;
        }

        // TODO: [emi] Add sanitization (with a flag) when needed

        return $input[$key];
    }

    // TODO: [emi] Add a method to read a JSON payload data (json_decode via php://input)
}
