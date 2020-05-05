<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace HyperfTest\Constants;

use Hyperf\Constants\AnnotationReader;
use Hyperf\Constants\ConstantsCollector;
use Hyperf\Contract\ContainerInterface;
use Hyperf\Contract\TranslatorInterface;
use Hyperf\Translation\ArrayLoader;
use Hyperf\Translation\Translator;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Context;
use HyperfTest\Constants\Stub\ErrorCodeStub;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class AnnotationReaderTest extends TestCase
{
    protected function setUp()
    {
        $reader = new AnnotationReader();

        $ref = new \ReflectionClass(ErrorCodeStub::class);
        $classConstants = $ref->getReflectionConstants();

        $data = $reader->getAnnotations($classConstants);
        ConstantsCollector::set(ErrorCodeStub::class, $data);

        Context::set(sprintf('%s::%s', TranslatorInterface::class, 'locale'), null);
    }

    public function testGetAnnotations()
    {
        $this->getContainer();

        $data = ConstantsCollector::get(ErrorCodeStub::class);

        $this->assertSame('Server Error!', $data[ErrorCodeStub::SERVER_ERROR]['message']);
        $this->assertSame('SHOW ECHO', $data[ErrorCodeStub::SHOW_ECHO]['message']);
        $this->assertSame('ECHO', $data[ErrorCodeStub::SHOW_ECHO]['echo']);

        $this->assertArrayNotHasKey(ErrorCodeStub::NO_MESSAGE, $data);
    }

    public function testGetMessageWithArguments()
    {
        $this->getContainer();

        $res = ErrorCodeStub::getMessage(ErrorCodeStub::PARAMS_INVALID);

        $this->assertSame('Params[%s] is invalid.', $res);

        $res = ErrorCodeStub::getMessage(ErrorCodeStub::PARAMS_INVALID, 'user_id');

        $this->assertSame('Params[user_id] is invalid.', $res);

        $res = ErrorCodeStub::getMessage(ErrorCodeStub::PARAMS_INVALID, ['order_id']);

        $this->assertSame('Params[order_id] is invalid.', $res);
    }

    public function testGetMessageUsingTranslator()
    {
        $container = $this->getContainer(true);

        $res = ErrorCodeStub::getMessage(ErrorCodeStub::SERVER_ERROR);
        $this->assertSame('Server Error!', $res);

        $res = ErrorCodeStub::getMessage(ErrorCodeStub::TRANSLATOR_ERROR_MESSAGE);
        $this->assertSame('Error Message', $res);

        $res = ErrorCodeStub::getMessage(ErrorCodeStub::TRANSLATOR_NOT_EXIST, ['name' => 'Hyperf']);
        $this->assertSame('Hyperf is not exist.', $res);

        Context::set(sprintf('%s::%s', TranslatorInterface::class, 'locale'), 'zh_CN');
        $res = ErrorCodeStub::getMessage(ErrorCodeStub::TRANSLATOR_ERROR_MESSAGE);
        $this->assertSame('错误信息', $res);
    }

    protected function getContainer($has = false)
    {
        $container = Mockery::mock(ContainerInterface::class);
        ApplicationContext::setContainer($container);

        $container->shouldReceive('get')->with(TranslatorInterface::class)->andReturnUsing(function () {
            $loader = new ArrayLoader();
            $loader->addMessages('en', 'error', [
                'message' => 'Error Message',
                'not_exist' => ':name is not exist.',
            ]);
            $loader->addMessages('zh_CN', 'error', ['message' => '错误信息']);
            return new Translator($loader, 'en');
        });

        $container->shouldReceive('has')->andReturn($has);

        return $container;
    }
}
