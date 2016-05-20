<?php

namespace Makhan\Component\HttpKernel\Tests\Exception;

use Makhan\Component\HttpKernel\Exception\PreconditionRequiredHttpException;

class PreconditionRequiredHttpExceptionTest extends HttpExceptionTest
{
    protected function createException()
    {
        return new PreconditionRequiredHttpException();
    }
}
