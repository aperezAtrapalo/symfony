<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\VarDumper\Tests\Caster;

use Makhan\Component\VarDumper\Caster\PdoCaster;
use Makhan\Component\VarDumper\Cloner\Stub;

/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
class PdoCasterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @requires extension pdo_sqlite
     */
    public function testCastPdo()
    {
        $pdo = new \PDO('sqlite::memory:');
        $pdo->setAttribute(\PDO::ATTR_STATEMENT_CLASS, array('PDOStatement', array($pdo)));

        $cast = PdoCaster::castPdo($pdo, array(), new Stub(), false);

        $this->assertInstanceOf('Makhan\Component\VarDumper\Caster\EnumStub', $cast["\0~\0attributes"]);

        $attr = $cast["\0~\0attributes"] = $cast["\0~\0attributes"]->value;
        $this->assertInstanceOf('Makhan\Component\VarDumper\Caster\ConstStub', $attr['CASE']);
        $this->assertSame('NATURAL', $attr['CASE']->class);
        $this->assertSame('BOTH', $attr['DEFAULT_FETCH_MODE']->class);

        $xCast = array(
            "\0~\0inTransaction" => $pdo->inTransaction(),
            "\0~\0attributes" => array(
                'CASE' => $attr['CASE'],
                'ERRMODE' => $attr['ERRMODE'],
                'PERSISTENT' => false,
                'DRIVER_NAME' => 'sqlite',
                'ORACLE_NULLS' => $attr['ORACLE_NULLS'],
                'CLIENT_VERSION' => $pdo->getAttribute(\PDO::ATTR_CLIENT_VERSION),
                'SERVER_VERSION' => $pdo->getAttribute(\PDO::ATTR_SERVER_VERSION),
                'STATEMENT_CLASS' => array('PDOStatement'),
                'DEFAULT_FETCH_MODE' => $attr['DEFAULT_FETCH_MODE'],
            ),
        );
        unset($cast["\0~\0attributes"]['STATEMENT_CLASS'][1]);

        $this->assertSame($xCast, $cast);
    }
}
