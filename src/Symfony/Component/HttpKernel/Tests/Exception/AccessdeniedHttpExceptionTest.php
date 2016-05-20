<?php

namespace Makhan\Component\HttpKernel\Tests\Exception;

use Makhan\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AccessDeniedHttpExceptionTest extends HttpExceptionTest
{
    protected function createException()
    {
        return new AccessDeniedHttpException();
    }
}
