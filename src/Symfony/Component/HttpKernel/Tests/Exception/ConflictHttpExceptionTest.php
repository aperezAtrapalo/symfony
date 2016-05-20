<?php

namespace Makhan\Component\HttpKernel\Tests\Exception;

use Makhan\Component\HttpKernel\Exception\ConflictHttpException;

class ConflictHttpExceptionTest extends HttpExceptionTest
{
    protected function createException()
    {
        return new ConflictHttpException();
    }
}
