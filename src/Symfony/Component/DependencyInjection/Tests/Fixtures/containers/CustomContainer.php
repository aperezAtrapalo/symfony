<?php

namespace Makhan\Component\DependencyInjection\Tests\Fixtures\containers;

use Makhan\Component\DependencyInjection\Container;
use Makhan\Component\DependencyInjection\ParameterBag\ParameterBag;

class CustomContainer extends Container
{
    public function getBarService()
    {
    }

    public function getFoobarService()
    {
    }
}
