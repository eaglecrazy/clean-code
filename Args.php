<?php

namespace CleanCode;

use CleanCode\ArgumentMarshaler\BooleanArgumentMarshaler;
use CleanCode\ArgumentMarshaler\DoubleArgumentMarshaler;
use CleanCode\ArgumentMarshaler\IntegerArgumentMarshaler;
use CleanCode\ArgumentMarshaler\StringArgumentMarshaler;
use CleanCode\Exceptions\ArgsException;
use CleanCode\Exceptions\ParseException;
use Exception;

/**
 * Class Args
 *
 * @package CleanCode
 */
class Args
{
    private const STRING_KEY_END = '*';
    private const INTEGER_KEY_END = '#';
    private const DOUBLE_KEY_END = '##';

    private string $schema;
    private array $args;
    private bool $valid = true;
    private array $unexpectedArguments = [];
    private array $marshalers = [];
    private array $argsFound = [];
    private array $argumentsParseErrors = [];
    private Iterator $argsList;

    /**
     * @param string $schema
     * @param array $args
     * @throws ParseException
     */
    public function __construct(string $schema, array $args)
    {
        $this->schema = $schema;
        $this->args = $args;
        $this->argsList = new Iterator($args);
        $this->valid = $this->parse();
    }

    /**
     * Analyze the schema and arguments.
     *
     * @return bool
     * @throws ParseException
     */
    private function parse(): bool
    {
        if (strlen($this->schema) == 0 && $this->argsList->notEmpty()) {
            return true;
        }

        $this->parseSchema();

        try {
            $this->parseArguments();
        } catch (ArgsException $e) {
            $this->argumentsParseErrors[] = $e;
            $this->valid = false;
        }

        return $this->valid;
    }

    /**
     * Analyze the schema.
     *
     * @throws ParseException
     */
    private function parseSchema(): void
    {
        $schema = explode(',', $this->schema);

        foreach ($schema as $element) {
            if (strlen($element)) {
                $trimmedElement = trim($element);
                $this->parseSchemaElement($trimmedElement);
            }
        }
    }

    /**
     *  Analyze the element of schema.
     *
     * @param string $element
     * @throws ParseException
     */
    private function parseSchemaElement(string $element): void
    {
        $elementId = $element[0];

        $this->validateSchemaElementId($elementId);

        $elementTail = substr($element, 1);

        if ($elementTail === '') {
            $this->marshalers[$elementId] = new BooleanArgumentMarshaler();
        } else if ($elementTail === self::STRING_KEY_END) {
            $this->marshalers[$elementId] = new StringArgumentMarshaler();
        } else if ($elementTail === self::INTEGER_KEY_END) {
            $this->marshalers[$elementId] = new IntegerArgumentMarshaler();
        } else if ($elementTail === self::DOUBLE_KEY_END) {
            $this->marshalers[$elementId] = new DoubleArgumentMarshaler();
        } else {
            throw new ParseException("Unknown element:" . $elementId . " in schema: " . $this->schema, 0);
        }
    }

    /**
     * Validate schema element.
     *
     * @param string $elementId
     * @throws ParseException
     */
    private function validateSchemaElementId(string $elementId): void
    {
        if (!ctype_alpha($elementId)) {
            throw new ParseException("Bad character:" . $elementId . " in schema: " . $this->schema, 0);
        }
    }

    /**
     * Analyze the arguments.
     *
     * @throws ArgsException
     */
    private function parseArguments(): void
    {
        while ($this->argsList->hasNext()){
            $this->parseArgument($this->argsList->current());
            $this->argsList->next();
        }
    }

    /**
     * Analyze the argument.
     *
     * @param string $arg
     * @throws ArgsException
     */
    private function parseArgument(string $arg): void
    {
        if ($arg[0] == '-' && strlen($arg) >= 2) {
            $this->parseElement($arg);
        } else {
            $this->unexpectedArguments[] = $arg;
            throw new ArgsException(ErrorCodeEnum::UNEXPECTED_ARGUMENT(), $arg);
        }
    }

    /**
     * Analyze element.
     *
     * @param string $arg
     * @throws ArgsException
     */
    private function parseElement(string $arg): void
    {
        if ($this->setArgument($arg)) {
            $this->argsFound[] = $arg;
        } else {
            $this->unexpectedArguments[] = $arg;
            throw new ArgsException(ErrorCodeEnum::UNEXPECTED_ARGUMENT(), $arg);
        }
    }

    /**
     * Set the argument.
     *
     * @param string $arg
     * @return bool
     */
    private function setArgument(string $arg): bool
    {
        $argType = substr($arg, 1, 1);

        /** @var ArgumentMarshaler $stringMarshaler */
        $marshaler = $this->marshalers[$argType] ?? null;

        if(!$marshaler){
            return false;
        }

        $marshaler->set($this->argsList->current());

        return true;
    }

    /**
     * Get the number of arguments.
     *
     * @return int
     */
    public function cardinality(): int
    {
        return count($this->argsFound);
    }

    /**
     * Get the scheme.
     *
     * @return string
     */
    public function usage(): string
    {
        if (strlen($this->schema) > 0) {
            return '-[' . $this->schema . ']';
        } else {
            return '';
        }
    }

    /**
     * Get error message.
     *
     * @return string
     * @throws Exception
     */
    public function errorMessage(): string
    {
        $message = '';
        $newer = 'This cannot be, because this can never be!!!';

        /** @var ArgsException $error */
        foreach ($this->argumentsParseErrors as $error) {
            switch ($error->getErrorCode()) {
                case ErrorCodeEnum::MISSING_STRING() :
                    $message .= 'Could not find string parameter for ' . $error->getArgument() . '.' . PHP_EOL;
                    break;
                case ErrorCodeEnum::MISSING_INTEGER() :
                    $message .= 'Could not find integer parameter for ' . $error->getArgument() . '.' . PHP_EOL;
                    break;
                case ErrorCodeEnum::INVALID_INTEGER() :
                    $message .= 'Invalid integer parameter: ' . $error->getArgument() . '.' . PHP_EOL;
                    break;
                case ErrorCodeEnum::MISSING_DOUBLE() :
                    $message .= 'Could not find double parameter for ' . $error->getArgument() . '.' . PHP_EOL;
                    break;
                case ErrorCodeEnum::INVALID_DOUBLE() :
                    $message .= 'Invalid double parameter: ' . $error->getArgument() . '.' . PHP_EOL;
                    break;
                case ErrorCodeEnum::UNEXPECTED_ARGUMENT() :
                    $message .= 'Argument "' . $error->getArgument() . '" unexpected.' . PHP_EOL;
                    break;
                case ErrorCodeEnum::OK() :
                    throw new Exception($newer);
                default :
                    throw new Exception($newer);
            }
        }

        return $message;
    }

    /**
     * Get a boolean argument.
     *
     * @param string $key
     * @return bool
     */
    public function getBoolean(string $key): bool
    {
        /** @var BooleanArgumentMarshaler $am */
        $am = $this->marshalers[$key] ?? null;

        return $am ? $am->get() : false;
    }

    /**
     * Get a string argument.
     *
     * @param string $key
     * @return string
     */
    public function getString(string $key): string
    {
        /** @var StringArgumentMarshaler $am */
        $am = $this->marshalers[$key] ?? null;

        return $am ? $am->get() : '';
    }

    /**
     * Get a integer argument.
     *
     * @param string $key
     * @return integer|null
     */
    public function getInt(string $key): int
    {
        /** @var IntegerArgumentMarshaler $am */
        $am = $this->marshalers[$key] ?? null;

        return $am ? $am->get() : 0;
    }

    /**
     * Get a double argument.
     *
     * @param string $key
     * @return float|null
     */
    public function getDouble(string $key): float
    {
        /** @var DoubleArgumentMarshaler $am */
        $am = $this->marshalers[$key] ?? null;

        return $am ? $am->get() : 0;
    }

    /**
     * Determine if an argument exists.
     *
     * @param string $arg
     * @return bool
     */
    public function has(string $arg): bool
    {
        return in_array($arg, $this->argsFound);
    }

    /**
     * Get the state of validation.
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }
}
