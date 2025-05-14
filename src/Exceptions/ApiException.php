<?php

namespace Sanjarani\OpenAI\Exceptions;

use Exception;
use Throwable;

class ApiException extends Exception
{
    protected ?string $errorType;
    protected ?string $errorCode;

    public function __construct(
        string $message = "",
        int $code = 0,
        ?string $errorType = null,
        ?string $errorCode = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->errorType = $errorType;
        $this->errorCode = $errorCode;
    }

    public function getErrorType(): ?string
    {
        return $this->errorType;
    }

    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    public function __toString(): string
    {
        $str = parent::__toString();
        if ($this->errorType) {
            $str .= "\nError Type: {$this->errorType}";
        }
        if ($this->errorCode) {
            $str .= "\nError Code: {$this->errorCode}";
        }
        return $str;
    }
}

