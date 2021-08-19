<?php

namespace CleanCode\Chapter14;

use CleanCode\Chapter14\ArgumentMarshaler\BooleanArgumentMarshaler;
use CleanCode\Chapter14\ArgumentMarshaler\DoubleArgumentMarshaler;
use CleanCode\Chapter14\ArgumentMarshaler\IntegerArgumentMarshaler;
use CleanCode\Chapter14\ArgumentMarshaler\StringArgumentMarshaler;
use Exception;

/**
 * Class Args
 *
 * @package CleanCode
 */
class Args
{
    private string $schema;
    private bool $valid = true;
    private array $marshalers = [];
    private array $argsFound = [];
    private array $argumentsParseErrors = [];
    private Iterator $argsList;

    /**
     * @param string $schema
     * @param array $args
     * @throws ArgsException
     */
    public function __construct(string $schema, array $args)
    {
        $this->schema = $schema;
        $this->argsList = new Iterator($args);
        $this->valid = $this->parse();
    }

    /**
     * Analyze the schema and arguments.
     *
     * @return bool
     * @throws ArgsException
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
     * @throws ArgsException
     */
    private function parseSchema(): void
    {
        $schema = explode(',', $this->schema);

        foreach ($schema as $element) {
            if (strlen($element)) {
                $trimmedElement = trim($element);

                $marshaler = MarshalerProvider::getMarshaler($trimmedElement);

                $this->marshalers[$trimmedElement[0]] = $marshaler;
            }
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

        /** @var ArgsException $error */
        foreach ($this->argumentsParseErrors as $error) {
            $message .= $error->getErrorMessage();
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
