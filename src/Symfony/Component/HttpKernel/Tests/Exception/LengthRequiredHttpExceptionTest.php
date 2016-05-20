<?php

namespace Makhan\Component\HttpKernel\Tests\Exception;

use Makhan\Component\HttpKernel\Exception\LengthRequiredHttpException;

class LengthRequiredHttpExceptionTest extends HttpExceptionTest
{
    protected function createException()
    {
        return new LengthRequiredHttpException();
    }
}
