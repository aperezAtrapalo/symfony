<?php

namespace Makhan\Component\HttpKernel\Tests\Exception;

use Makhan\Component\HttpKernel\Exception\PreconditionFailedHttpException;

class PreconditionFailedHttpExceptionTest extends HttpExceptionTest
{
    protected function createException()
    {
        return new PreconditionFailedHttpException();
    }
}
