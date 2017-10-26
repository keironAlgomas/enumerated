<?php

namespace DavidIanBonner\Enumerated;

use Exception;
use ReflectionClass;
use Illuminate\Support\Collection;

class Enum
{
    /** @var string */
    private $value;

    /** @var array */
    private static $loaded = [];

    /** @var array */
    protected static $values = [];

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        $this->validateValue($value);
        $this->value = $value;
    }

    /**
     * Return the enumerators values.
     *
     * @param boolean $keys
     * @return array
     */
    public static function allValues($keys = false) : array
    {
        $caller = get_called_class();

        if (! isset(static::$values[$caller])) {
            static::$values[$caller] = static::getDeclaredConstants();
        }

        return $keys ? static::$values[$caller] : array_values(static::$values[$caller]);
    }

    /**
     * Return a collection of the declared values.
     *
     * @param  boolean $keys
     * @return Illuminate\Support\Collection
     */
    public static function collect($keys = false) : Collection
    {
        return Collection::make(static::allValues($keys));
    }

    /**
     * Return an instance of a desired value.
     *
     * @param  string $value
     * @return DavidIanBonner\Enumerated\Enum
     */
    public static function ofType($value) : Enum
    {
        $key = get_called_class().':'.$value;

        if (! isset(self::$loaded[$key])) {
            self::$loaded[$key] = new static($value);
        }

        // We can safely return the instance from the loaded array as
        // validation is carried out in the constructor and the
        // $loaded property is private.

        return self::$loaded[$key];
    }

    /**
     * Return the value.
     *
     * @return string
     */
    public function value() : string
    {
        return $this->value;
    }

    /**
     * Validate the value.
     *
     * @param  string $value
     * @throws DavidIanBonner\Enumerated\EnumNotValidException
     * @return void
     */
    public static function validateValue($value)
    {
        if (! is_string($value) || ! in_array($value, static::allValues())) {
            throw new EnumNotValidException("The value [{$value}] is not a valid type.");
        }
    }

    /**
     * Check the value is valid and return a boolean.
     *
     * @param  string  $value
     * @return boolean
     */
    public static function isValid($value) : bool
    {
        try {
            static::validateValue($value);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Get the declared constants.
     *
     * @return array
     */
    protected static function getDeclaredConstants() : array
    {
        $reflection = new ReflectionClass(get_called_class());

        return (array) $reflection->getConstants();
    }
}
