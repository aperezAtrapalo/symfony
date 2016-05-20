<?php

namespace Makhan\Component\HttpKernel\Tests\Exception;

use Makhan\Component\HttpKernel\Exception\GoneHttpException;

class GoneHttpExceptionTest extends HttpExceptionTest
{
    protected function createException()
    {
        return new GoneHttpException();
    }
}
