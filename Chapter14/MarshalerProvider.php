<?php

namespace CleanCode\Chapter14;

use CleanCode\Chapter14\ArgumentMarshaler\BooleanArgumentMarshaler;
use CleanCode\Chapter14\ArgumentMarshaler\DoubleArgumentMarshaler;
use CleanCode\Chapter14\ArgumentMarshaler\IntegerArgumentMarshaler;
use CleanCode\Chapter14\ArgumentMarshaler\StringArgumentMarshaler;

abstract class MarshalerProvider
{
    private const BOOLEAN_TAIL = '';
    private const STRING_TAIL = '*';
    private const INTEGER_TAIL = '#';
    private const DOUBLE_TAIL = '##';

    /**
     * Get marshaler.
     *
     * @param string $element
     * @return ArgumentMarshaler
     * @throws ArgsException
     */
    public static function getMarshaler(string $element): ArgumentMarshaler
    {
        self::validate($element);

        $elementTail = substr($element, 1);

        $map = self::getMap();

        if (!array_key_exists($elementTail, $map)) {
            throw new ArgsException(ErrorCodeEnum::SCHEMA_UNKNOWN_ELEMENT(), $elementTail);
        }

        $marshalerClass = $map[$elementTail];

        return new $marshalerClass();
    }

    /**
     * Get a map of the correspondence between the "tail" of the element and the marshaler.
     *
     * @return string[]
     */
    private static function getMap(): array
    {
        return [
            self::BOOLEAN_TAIL => BooleanArgumentMarshaler::class,
            self::STRING_TAIL => StringArgumentMarshaler::class,
            self::INTEGER_TAIL => IntegerArgumentMarshaler::class,
            self::DOUBLE_TAIL => DoubleArgumentMarshaler::class,
        ];
    }

    /**
     * Validate element.
     *
     * @param string $element
     * @throws ArgsException
     */
    private static function validate(string $element): void
    {
        $elementId = $element[0];

        if (!ctype_alpha($elementId)) {
            throw new ArgsException(ErrorCodeEnum::SCHEMA_BAD_CHARACTER(), $elementId);
        }
    }
}
