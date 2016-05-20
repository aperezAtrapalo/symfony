<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\VarDumper\Cloner;

use Makhan\Component\VarDumper\Caster\Caster;
use Makhan\Component\VarDumper\Exception\ThrowingCasterException;

/**
 * AbstractCloner implements a generic caster mechanism for objects and resources.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
abstract class AbstractCloner implements ClonerInterface
{
    public static $defaultCasters = array(
        'Makhan\Component\VarDumper\Caster\CutStub' => 'Makhan\Component\VarDumper\Caster\StubCaster::castStub',
        'Makhan\Component\VarDumper\Caster\CutArrayStub' => 'Makhan\Component\VarDumper\Caster\StubCaster::castCutArray',
        'Makhan\Component\VarDumper\Caster\ConstStub' => 'Makhan\Component\VarDumper\Caster\StubCaster::castStub',
        'Makhan\Component\VarDumper\Caster\EnumStub' => 'Makhan\Component\VarDumper\Caster\StubCaster::castEnum',

        'Closure' => 'Makhan\Component\VarDumper\Caster\ReflectionCaster::castClosure',
        'Generator' => 'Makhan\Component\VarDumper\Caster\ReflectionCaster::castGenerator',
        'ReflectionType' => 'Makhan\Component\VarDumper\Caster\ReflectionCaster::castType',
        'ReflectionGenerator' => 'Makhan\Component\VarDumper\Caster\ReflectionCaster::castReflectionGenerator',
        'ReflectionClass' => 'Makhan\Component\VarDumper\Caster\ReflectionCaster::castClass',
        'ReflectionFunctionAbstract' => 'Makhan\Component\VarDumper\Caster\ReflectionCaster::castFunctionAbstract',
        'ReflectionMethod' => 'Makhan\Component\VarDumper\Caster\ReflectionCaster::castMethod',
        'ReflectionParameter' => 'Makhan\Component\VarDumper\Caster\ReflectionCaster::castParameter',
        'ReflectionProperty' => 'Makhan\Component\VarDumper\Caster\ReflectionCaster::castProperty',
        'ReflectionExtension' => 'Makhan\Component\VarDumper\Caster\ReflectionCaster::castExtension',
        'ReflectionZendExtension' => 'Makhan\Component\VarDumper\Caster\ReflectionCaster::castZendExtension',

        'Doctrine\Common\Persistence\ObjectManager' => 'Makhan\Component\VarDumper\Caster\StubCaster::cutInternals',
        'Doctrine\Common\Proxy\Proxy' => 'Makhan\Component\VarDumper\Caster\DoctrineCaster::castCommonProxy',
        'Doctrine\ORM\Proxy\Proxy' => 'Makhan\Component\VarDumper\Caster\DoctrineCaster::castOrmProxy',
        'Doctrine\ORM\PersistentCollection' => 'Makhan\Component\VarDumper\Caster\DoctrineCaster::castPersistentCollection',

        'DOMException' => 'Makhan\Component\VarDumper\Caster\DOMCaster::castException',
        'DOMStringList' => 'Makhan\Component\VarDumper\Caster\DOMCaster::castLength',
        'DOMNameList' => 'Makhan\Component\VarDumper\Caster\DOMCaster::castLength',
        'DOMImplementation' => 'Makhan\Component\VarDumper\Caster\DOMCaster::castImplementation',
        'DOMImplementationList' => 'Makhan\Component\VarDumper\Caster\DOMCaster::castLength',
        'DOMNode' => 'Makhan\Component\VarDumper\Caster\DOMCaster::castNode',
        'DOMNameSpaceNode' => 'Makhan\Component\VarDumper\Caster\DOMCaster::castNameSpaceNode',
        'DOMDocument' => 'Makhan\Component\VarDumper\Caster\DOMCaster::castDocument',
        'DOMNodeList' => 'Makhan\Component\VarDumper\Caster\DOMCaster::castLength',
        'DOMNamedNodeMap' => 'Makhan\Component\VarDumper\Caster\DOMCaster::castLength',
        'DOMCharacterData' => 'Makhan\Component\VarDumper\Caster\DOMCaster::castCharacterData',
        'DOMAttr' => 'Makhan\Component\VarDumper\Caster\DOMCaster::castAttr',
        'DOMElement' => 'Makhan\Component\VarDumper\Caster\DOMCaster::castElement',
        'DOMText' => 'Makhan\Component\VarDumper\Caster\DOMCaster::castText',
        'DOMTypeinfo' => 'Makhan\Component\VarDumper\Caster\DOMCaster::castTypeinfo',
        'DOMDomError' => 'Makhan\Component\VarDumper\Caster\DOMCaster::castDomError',
        'DOMLocator' => 'Makhan\Component\VarDumper\Caster\DOMCaster::castLocator',
        'DOMDocumentType' => 'Makhan\Component\VarDumper\Caster\DOMCaster::castDocumentType',
        'DOMNotation' => 'Makhan\Component\VarDumper\Caster\DOMCaster::castNotation',
        'DOMEntity' => 'Makhan\Component\VarDumper\Caster\DOMCaster::castEntity',
        'DOMProcessingInstruction' => 'Makhan\Component\VarDumper\Caster\DOMCaster::castProcessingInstruction',
        'DOMXPath' => 'Makhan\Component\VarDumper\Caster\DOMCaster::castXPath',

        'ErrorException' => 'Makhan\Component\VarDumper\Caster\ExceptionCaster::castErrorException',
        'Exception' => 'Makhan\Component\VarDumper\Caster\ExceptionCaster::castException',
        'Error' => 'Makhan\Component\VarDumper\Caster\ExceptionCaster::castError',
        'Makhan\Component\DependencyInjection\ContainerInterface' => 'Makhan\Component\VarDumper\Caster\StubCaster::cutInternals',
        'Makhan\Component\VarDumper\Exception\ThrowingCasterException' => 'Makhan\Component\VarDumper\Caster\ExceptionCaster::castThrowingCasterException',
        'Makhan\Component\VarDumper\Caster\TraceStub' => 'Makhan\Component\VarDumper\Caster\ExceptionCaster::castTraceStub',
        'Makhan\Component\VarDumper\Caster\FrameStub' => 'Makhan\Component\VarDumper\Caster\ExceptionCaster::castFrameStub',

        'PHPUnit_Framework_MockObject_MockObject' => 'Makhan\Component\VarDumper\Caster\StubCaster::cutInternals',
        'Prophecy\Prophecy\ProphecySubjectInterface' => 'Makhan\Component\VarDumper\Caster\StubCaster::cutInternals',
        'Mockery\MockInterface' => 'Makhan\Component\VarDumper\Caster\StubCaster::cutInternals',

        'PDO' => 'Makhan\Component\VarDumper\Caster\PdoCaster::castPdo',
        'PDOStatement' => 'Makhan\Component\VarDumper\Caster\PdoCaster::castPdoStatement',

        'AMQPConnection' => 'Makhan\Component\VarDumper\Caster\AmqpCaster::castConnection',
        'AMQPChannel' => 'Makhan\Component\VarDumper\Caster\AmqpCaster::castChannel',
        'AMQPQueue' => 'Makhan\Component\VarDumper\Caster\AmqpCaster::castQueue',
        'AMQPExchange' => 'Makhan\Component\VarDumper\Caster\AmqpCaster::castExchange',
        'AMQPEnvelope' => 'Makhan\Component\VarDumper\Caster\AmqpCaster::castEnvelope',

        'ArrayObject' => 'Makhan\Component\VarDumper\Caster\SplCaster::castArrayObject',
        'SplDoublyLinkedList' => 'Makhan\Component\VarDumper\Caster\SplCaster::castDoublyLinkedList',
        'SplFileInfo' => 'Makhan\Component\VarDumper\Caster\SplCaster::castFileInfo',
        'SplFileObject' => 'Makhan\Component\VarDumper\Caster\SplCaster::castFileObject',
        'SplFixedArray' => 'Makhan\Component\VarDumper\Caster\SplCaster::castFixedArray',
        'SplHeap' => 'Makhan\Component\VarDumper\Caster\SplCaster::castHeap',
        'SplObjectStorage' => 'Makhan\Component\VarDumper\Caster\SplCaster::castObjectStorage',
        'SplPriorityQueue' => 'Makhan\Component\VarDumper\Caster\SplCaster::castHeap',
        'OuterIterator' => 'Makhan\Component\VarDumper\Caster\SplCaster::castOuterIterator',

        'MongoCursorInterface' => 'Makhan\Component\VarDumper\Caster\MongoCaster::castCursor',

        ':curl' => 'Makhan\Component\VarDumper\Caster\ResourceCaster::castCurl',
        ':dba' => 'Makhan\Component\VarDumper\Caster\ResourceCaster::castDba',
        ':dba persistent' => 'Makhan\Component\VarDumper\Caster\ResourceCaster::castDba',
        ':gd' => 'Makhan\Component\VarDumper\Caster\ResourceCaster::castGd',
        ':mysql link' => 'Makhan\Component\VarDumper\Caster\ResourceCaster::castMysqlLink',
        ':pgsql large object' => 'Makhan\Component\VarDumper\Caster\PgSqlCaster::castLargeObject',
        ':pgsql link' => 'Makhan\Component\VarDumper\Caster\PgSqlCaster::castLink',
        ':pgsql link persistent' => 'Makhan\Component\VarDumper\Caster\PgSqlCaster::castLink',
        ':pgsql result' => 'Makhan\Component\VarDumper\Caster\PgSqlCaster::castResult',
        ':process' => 'Makhan\Component\VarDumper\Caster\ResourceCaster::castProcess',
        ':stream' => 'Makhan\Component\VarDumper\Caster\ResourceCaster::castStream',
        ':stream-context' => 'Makhan\Component\VarDumper\Caster\ResourceCaster::castStreamContext',
        ':xml' => 'Makhan\Component\VarDumper\Caster\XmlResourceCaster::castXml',
    );

    protected $maxItems = 2500;
    protected $maxString = -1;
    protected $useExt;

    private $casters = array();
    private $prevErrorHandler;
    private $classInfo = array();
    private $filter = 0;

    /**
     * @param callable[]|null $casters A map of casters.
     *
     * @see addCasters
     */
    public function __construct(array $casters = null)
    {
        if (null === $casters) {
            $casters = static::$defaultCasters;
        }
        $this->addCasters($casters);
        $this->useExt = extension_loaded('makhan_debug');
    }

    /**
     * Adds casters for resources and objects.
     *
     * Maps resources or objects types to a callback.
     * Types are in the key, with a callable caster for value.
     * Resource types are to be prefixed with a `:`,
     * see e.g. static::$defaultCasters.
     *
     * @param callable[] $casters A map of casters.
     */
    public function addCasters(array $casters)
    {
        foreach ($casters as $type => $callback) {
            $this->casters[strtolower($type)][] = $callback;
        }
    }

    /**
     * Sets the maximum number of items to clone past the first level in nested structures.
     *
     * @param int $maxItems
     */
    public function setMaxItems($maxItems)
    {
        $this->maxItems = (int) $maxItems;
    }

    /**
     * Sets the maximum cloned length for strings.
     *
     * @param int $maxString
     */
    public function setMaxString($maxString)
    {
        $this->maxString = (int) $maxString;
    }

    /**
     * Clones a PHP variable.
     *
     * @param mixed $var    Any PHP variable.
     * @param int   $filter A bit field of Caster::EXCLUDE_* constants.
     *
     * @return Data The cloned variable represented by a Data object.
     */
    public function cloneVar($var, $filter = 0)
    {
        $this->prevErrorHandler = set_error_handler(function ($type, $msg, $file, $line, $context) {
            if (E_RECOVERABLE_ERROR === $type || E_USER_ERROR === $type) {
                // Cloner never dies
                throw new \ErrorException($msg, 0, $type, $file, $line);
            }

            if ($this->prevErrorHandler) {
                return call_user_func($this->prevErrorHandler, $type, $msg, $file, $line, $context);
            }

            return false;
        });
        $this->filter = $filter;

        try {
            $data = $this->doClone($var);
        } catch (\Exception $e) {
        }
        restore_error_handler();
        $this->prevErrorHandler = null;

        if (isset($e)) {
            throw $e;
        }

        return new Data($data);
    }

    /**
     * Effectively clones the PHP variable.
     *
     * @param mixed $var Any PHP variable.
     *
     * @return array The cloned variable represented in an array.
     */
    abstract protected function doClone($var);

    /**
     * Casts an object to an array representation.
     *
     * @param Stub $stub     The Stub for the casted object.
     * @param bool $isNested True if the object is nested in the dumped structure.
     *
     * @return array The object casted as array.
     */
    protected function castObject(Stub $stub, $isNested)
    {
        $obj = $stub->value;
        $class = $stub->class;

        if (isset($class[15]) && "\0" === $class[15] && 0 === strpos($class, "class@anonymous\x00")) {
            $stub->class = get_parent_class($class).'@anonymous';
        }
        if (isset($this->classInfo[$class])) {
            $classInfo = $this->classInfo[$class];
        } else {
            $classInfo = array(
                new \ReflectionClass($class),
                array_reverse(array($class => $class) + class_parents($class) + class_implements($class) + array('*' => '*')),
            );

            $this->classInfo[$class] = $classInfo;
        }

        $a = $this->callCaster('Makhan\Component\VarDumper\Caster\Caster::castObject', $obj, $classInfo[0], null, $isNested);

        foreach ($classInfo[1] as $p) {
            if (!empty($this->casters[$p = strtolower($p)])) {
                foreach ($this->casters[$p] as $p) {
                    $a = $this->callCaster($p, $obj, $a, $stub, $isNested);
                }
            }
        }

        return $a;
    }

    /**
     * Casts a resource to an array representation.
     *
     * @param Stub $stub     The Stub for the casted resource.
     * @param bool $isNested True if the object is nested in the dumped structure.
     *
     * @return array The resource casted as array.
     */
    protected function castResource(Stub $stub, $isNested)
    {
        $a = array();
        $res = $stub->value;
        $type = $stub->class;

        if (!empty($this->casters[':'.$type])) {
            foreach ($this->casters[':'.$type] as $c) {
                $a = $this->callCaster($c, $res, $a, $stub, $isNested);
            }
        }

        return $a;
    }

    /**
     * Calls a custom caster.
     *
     * @param callable        $callback The caster.
     * @param object|resource $obj      The object/resource being casted.
     * @param array           $a        The result of the previous cast for chained casters.
     * @param Stub            $stub     The Stub for the casted object/resource.
     * @param bool            $isNested True if $obj is nested in the dumped structure.
     *
     * @return array The casted object/resource.
     */
    private function callCaster($callback, $obj, $a, $stub, $isNested)
    {
        try {
            $cast = call_user_func($callback, $obj, $a, $stub, $isNested, $this->filter);

            if (is_array($cast)) {
                $a = $cast;
            }
        } catch (\Exception $e) {
            $a[(Stub::TYPE_OBJECT === $stub->type ? Caster::PREFIX_VIRTUAL : '').'⚠'] = new ThrowingCasterException($e);
        }

        return $a;
    }
}
