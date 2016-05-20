<?php

namespace Makhan\Component\HttpKernel\Tests\Exception;

use Makhan\Component\HttpKernel\Exception\NotAcceptableHttpException;

class NotAcceptableHttpExceptionTest extends HttpExceptionTest
{
    protected function createException()
    {
        return new NotAcceptableHttpException();
    }
}
