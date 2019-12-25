<?php


namespace Xwpd\ThinkTesting;

use think\App;
use think\helper\Str;
use Xwpd\ThinkTesting\Traits\DatabaseTransactions;
use Xwpd\ThinkTesting\Traits\InteractsWithContainer;
use Mockery;
use Exception;

abstract class TestCase extends \think\testing\TestCase
{
    use InteractsWithContainer;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    /**
     * 项目目录，在ide运行测试用例或者在非thinkPHP目录使用的时候需要设置
     * @var string
     */
    protected $app_path = '';

    protected function getAppPath()
    {
        return $this->app_path ? $this->app_path : \think\facade\App::getAppPath();
    }

    protected function setUp()
    {
        parent::setUp();
        $app = new App($this->getAppPath());
        $app->initialize();
        $app::setInstance($app);
        $this->setUpTraits();
    }

    /**
     * Boot the testing helper traits.
     *
     * @return array
     */
    protected function setUpTraits()
    {
        $uses = array_flip(class_uses_recursive(static::class));
        if (isset($uses[DatabaseTransactions::class])) {
            $this->beginDatabaseTransaction();
        }

        return $uses;
    }

    /**
     * Boot the testing helper traits.
     *
     * @return array
     */
    protected function tearDownTraits()
    {
        $uses = array_flip(class_uses_recursive(static::class));
        if (isset($uses[DatabaseTransactions::class])) {
            $this->rollBackDatabase();
        }

        return $uses;
    }

    /**
     * @throws Exception
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->tearDownTraits();

        $this->closeMockery();
    }

    /**
     * @throws Exception
     */
    protected function closeMockery()
    {
        if (class_exists('Mockery')) {
            if ($container = Mockery::getContainer()) {
                $this->addToAssertionCount($container->mockery_getExpectationCount());
            }

            try {
                Mockery::close();
            } catch (Exception $e) {
                if (!Str::contains($e->getMethodName(), ['doWrite', 'askQuestion'])) {
                    throw $e;
                }
            }
        }
    }
}