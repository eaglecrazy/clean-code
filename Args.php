<?php

namespace CleanCode;

/**
 * Class Args
 *
 * @package CleanCode
 */
class Args
{
    private string $schema;
    private array  $args;
    private bool   $valid;
    private array $unexpectedArguments = [];
    private array $booleanArgs = [];
    private int   $numberOfArguments = 0;

    /**
     * @param string $schema
     * @param array $args
     */
    public function __construct(string $schema, array $args)
    {
        $this->schema = $schema;
        $this->args = $args;
        $this->valid = $this->parse();
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
     * Analyze the arguments.
     *
     * @return bool
     */
    private function parse(): bool
    {
        if (strlen($this->schema) == 0 && count($this->args) == 0) {
            return true;
        }

        $this->parseSchema();
        $this->parseArguments();

        return count($this->unexpectedArguments) == 0;
    }

    /**
     * Analyze the schema.
     */
    private function parseSchema(): void
    {
        $schema = explode(',', $this->schema);

        foreach ($schema as $element) {
            $this->parseSchemaElement($element);
        }
    }

    /**
     *  Analyze the element of schema.
     *
     * @param string $element
     */
    private function parseSchemaElement(string $element): void
    {
        if (strlen($element) == 1) {
            $this->parseBoolSchemaElement($element);
        }
    }

    /**
     * Analyze boolean element of schema.
     *
     *
     * @param string $element
     */
    private function parseBoolSchemaElement(string $element): void
    {
        $char = $element[0];

        if (ctype_alpha($char)) {
            //By default, the boolean argument is false.
            $this->booleanArgs[$char] = false;
        }
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
        if ($arg[0] == '-') {
            $this->parseElements($arg);
        }
    }

    /**
     *  Analyze elements of the argument.
     *
     * @param string $arg
     */
    private function parseElements(string $arg): void
    {
        for ($i = 1; $i < strlen($arg); $i++) {
            $this->parseElement($arg[$i]);
        }
    }

    /**
     * Analyze element.
     *
     * @param string $argChar
     */
    private function parseElement(string $argChar): void
    {
        if ($this->isBoolean($argChar)) {
            $this->numberOfArguments++;

            //If a boolean argument was specified, then it is true.
            $this->setBoolArg($argChar, true);
        } else {
            $this->unexpectedArguments[] = $argChar;
        }
    }

    /**
     * Set boolean argument.
     *
     * @param string $argChar
     * @param bool $value
     */
    private function setBoolArg(string $argChar, bool $value): void
    {
        $this->booleanArgs[$argChar] = $value;
    }

    /**
     * Determine if there is such an element in the schema.
     *
     * @param string $argChar
     * @return bool
     */
    private function isBoolean(string $argChar): bool
    {
        return array_key_exists($argChar, $this->booleanArgs);
    }

    /**
     * Get the number of arguments.
     *
     * @return int
     */
    public function cardinality(): int
    {
        return $this->numberOfArguments;
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
     */
    public function errorMessage(): string
    {
        if (count($this->unexpectedArguments) > 0) {
            return $this->unexpectedArgumentMessage();
        } else {
            return '';
        }
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
     * @param string $arg
     * @return bool
     */
    public function getBool(string $arg): bool
    {
        return $this->booleanArgs[$arg];
    }
}
