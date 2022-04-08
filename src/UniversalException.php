<?php

namespace Brooklyn\Exceptions;

use Exception;
use Throwable;


class UniversalException extends Exception
{

    /**
     * @param string $message
     * @param string|null $location
     * @param int $code
     * @param array|null $context
     * @param bool $defaultContext
     * @param Throwable|null $previous
     */
    public function __construct(string $message, protected ?string $location, int $code = 500, protected ?array $context = null, protected bool $defaultContext = false, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return object
     */
    public function render(): object
    {
        return response()->json($this->generateResponse(), $this->getCode());
    }

    /**
     * @return array
     */
    private function generateResponse(): array
    {
        $response = [];
        $response["message"] = $this->message;
        !$this->location ?: $response["location"] = $this->location;
        !$this->context ?: $response["context"] = $this->getData();
        $response["code"] = $this->code;
        return $response;

    }

    /**
     * @return array
     */
    private function getData(): array
    {
        $this->defaultContext ? $context = [
            '_file' => $this->getFile(),
            '_line' => $this->getLine(),
            '_previous' => $this->getPrevious(),
        ] : $context = [];

        return array_merge($context, $this->context);
    }
}
