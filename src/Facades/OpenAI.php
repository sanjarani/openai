<?php

namespace Sanjarani\OpenAI\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sanjarani\OpenAI\OpenAI
 */
class OpenAI extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'openai';
    }
}

