<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\TwigBundle\Tests\Controller;

use Makhan\Bundle\TwigBundle\Tests\TestCase;
use Makhan\Bundle\TwigBundle\Controller\ExceptionController;
use Makhan\Component\Debug\Exception\FlattenException;
use Makhan\Component\HttpFoundation\Request;

class ExceptionControllerTest extends TestCase
{
    public function testShowActionCanBeForcedToShowErrorPage()
    {
        $twig = new \Twig_Environment(
            new \Twig_Loader_Array(array(
                '@Twig/Exception/error404.html.twig' => 'ok',
            ))
        );

        $request = Request::create('whatever', 'GET');
        $request->headers->set('X-Php-Ob-Level', 1);
        $request->attributes->set('showException', false);
        $exception = FlattenException::create(new \Exception(), 404);
        $controller = new ExceptionController($twig, /* "showException" defaults to --> */ true);

        $response = $controller->showAction($request, $exception, null);

        $this->assertEquals(200, $response->getStatusCode()); // successful request
        $this->assertEquals('ok', $response->getContent());  // content of the error404.html template
    }

    public function testFallbackToHtmlIfNoTemplateForRequestedFormat()
    {
        $twig = new \Twig_Environment(
            new \Twig_Loader_Array(array(
                '@Twig/Exception/error.html.twig' => 'html',
            ))
        );

        $request = Request::create('whatever');
        $request->headers->set('X-Php-Ob-Level', 1);
        $request->setRequestFormat('txt');
        $exception = FlattenException::create(new \Exception());
        $controller = new ExceptionController($twig, false);

        $response = $controller->showAction($request, $exception);

        $this->assertEquals('html', $request->getRequestFormat());
    }
}
