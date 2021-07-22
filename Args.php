<?php

namespace CleanCode;

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

    private string $schema;
    private array $args;
    private bool $valid = true;
    private array $unexpectedArguments = [];
    private array $booleanArgs = [];
    private array $stringArgs = [];
    private array $argsFound = [];
    private string $errorArgument = '\0';
    private string $errorCode;

    /**
     * @param string $schema
     * @param array $args
     * @throws ParseException
     */
    public function __construct(string $schema, array $args)
    {
        $this->errorCode = ErrorCodeEnum::OK();

        $this->schema = $schema;
        $this->args = $args;
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
        if (strlen($this->schema) == 0 && count($this->args) == 0) {
            return true;
        }

        $this->parseSchema();
        $this->parseArguments();

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

        $elementTail = substr($element, 1);

        $this->validateSchemaElementId($elementId);

        if ($this->isBooleanSchemaElement($elementTail)) {
            $this->parseBoolSchemaElement($element);
        } else if ($this->isStringSchemaElement($elementTail)) {
            $this->parseStringSchemaElement($element);
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
     * Create storage for string element.
     *
     * @param string $elementId
     */
    private function parseStringSchemaElement(string $elementId): void
    {
        $this->stringArgs[$elementId] = '';
    }

    /**
     * Determine if it is a string element.
     *
     * @param string $elementTail
     * @return bool
     */
    private function isStringSchemaElement(string $elementTail): bool
    {
        return $elementTail === self::STRING_KEY_END;
    }

    /**
     * Determine if it is a boolean element.
     *
     * @param string $elementTail
     * @return bool
     */
    private function isBooleanSchemaElement(string $elementTail): bool
    {
        return $elementTail === '';
    }

    /**
     * Create storage for boolean element.
     *
     * @param string $elementId
     */
    private function parseBoolSchemaElement(string $elementId): void
    {
        $this->booleanArgs[$elementId] = false;
    }

    /**
     * Analyze the arguments.
     */
    private function parseArguments(): void
    {
        foreach ($this->args as $arg) {
            $this->parseArgument($arg);
        }
    }

    /**
     * Analyze the argument.
     *
     * @param string $arg
     */
    private function parseArgument(string $arg): void
    {
        if ($arg[0] == '-' && strlen($arg) >= 2) {
            $this->parseElement($arg);
        } else {
            $this->unexpectedArguments[] = $arg;
            $this->valid = false;
        }
    }

    /**
     * Analyze element.
     *
     * @param string $arg
     */
    private function parseElement(string $arg): void
    {
        if ($this->setArgument($arg)) {
            $this->argsFound[] = $arg;
        } else {
            $this->unexpectedArguments[] = $arg;
            $this->valid = false;
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
        $set = true;

        $argType = substr($arg, 1, 1);

        if ($this->isBoolean($argType)) {
            //If a boolean argument was specified, then it is true.
            $this->setBooleanArg($argType);
        } else if ($this->isString($argType)) {
            $this->setStringArg($argType, $arg);
        } else {
            $set = false;
        }

        return $set;
    }

    /**
     * Set string argument.
     *
     * @param string $argId
     * @param string $arg
     */
    private function setStringArg(string $argId, string $arg): void
    {
        $str = substr($arg, 2);

        $key = $argId . self::STRING_KEY_END;

        try {
            $this->stringArgs[$key] = $str;
        } catch (Exception $e) {
            $this->valid = false;
            $this->errorArgument = $arg;
            $this->errorCode = ErrorCodeEnum::MISSING_STRING();
        }
    }

    /**
     * Determine if there is such a string element in the schema.
     *
     * @param string $stringType
     * @return bool
     */
    private function isString(string $stringType): bool
    {
        $key = $stringType . self::STRING_KEY_END;

        return array_key_exists($key, $this->stringArgs);
    }

    /**
     * Set boolean argument.
     *
     * @param string $key
     */
    private function setBooleanArg(string $key): void
    {
        $this->booleanArgs[$key] = true;
    }

    /**
     * Determine if there is such a boolean element in the schema.
     *
     * @param string $argId
     * @return bool
     */
    private function isBoolean(string $argId): bool
    {
        return array_key_exists($argId, $this->booleanArgs);
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
        if (count($this->unexpectedArguments) > 0) {
            return $this->unexpectedArgumentMessage();
        } else {
            switch ($this->errorCode) {
                case ErrorCodeEnum::MISSING_STRING() :
                    return 'Could not find string parameter for ' . $this->errorArgument;
                case ErrorCodeEnum::OK() :
                    return '';
            }
        }
        return '';
    }

    /**
     * Get unexpected argument message.
     *
     * @return string
     */
    private function unexpectedArgumentMessage(): string
    {
        $message = 'Argument(s) - ';

        foreach ($this->unexpectedArguments as $argument) {
            $message .= $argument;
        }
        $message .= ' unexpected.';

        return $message;
    }

    /**
     * Get a boolean argument.
     *
     * @param string $key
     * @return bool
     */
    public function getBool(string $key): bool
    {
        return $this->booleanArgs[$key] ?? false;
    }

    /**
     * Get a string argument.
     *
     * @param string $id
     * @return string
     */
    public function getString(string $id): string
    {
        $key = $id . self::STRING_KEY_END;

        return $this->stringArgs[$key] ?? '';
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

    /**
     * What the fuck is Uncle Bob doing with this method?
     * I have been trying to understand how to parse strings
     * with it for a long time. But I didn't get it.
     * In the end, I did it my own way.
     * I will remove this unused method in the next iteration.
     *
     * @param string $arg
     */
    private function parseElements(string $arg): void
    {
        for ($i = 1; $i < strlen($arg); $i++) {
            $this->parseElement($arg[$i]);
        }
    }
}
