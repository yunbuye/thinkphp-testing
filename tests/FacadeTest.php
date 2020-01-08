<?php


namespace Yunbuye\ThinkTesting\Tests;


use think\facade\Cache;
use think\facade\Config;
use think\helper\Str;
use Yunbuye\ThinkTesting\TestCase;
use \Mockery\Mock;

class FacadeTest extends TestCase
{
    /**
     * 指定应用目录
     * @var string
     */
    protected $app_path = __DIR__ . '/application/';

    /**
     * 对象可以被 mock
     */
    public function test_ob_can_mock()
    {
        $re = Str::random(15);
        $key = Str::random(5);
        $this->mock('think\Cache', function ($mock) use ($re, $key) {
            /**
             * @var Mock $mock
             */
            return $mock->shouldReceive('get')->with($key)->andReturn($re);
        });
        $get = Cache::get($key);
        $this->assertTrue($get == $re);
        $get = Cache::get($key);
        $this->assertTrue($get == $re);
    }


    protected $cache_key = 'aasdsdadf';

    /**
     * 测试之间的相互独立，mock不会相互干扰1
     */
    public function test_mock_independent_1()
    {
        $re = Str::random(15);
        $key = $this->cache_key;
        $this->mock('think\Cache', function ($mock) use ($re, $key) {
            /**
             * @var Mock $mock
             */
            return $mock->shouldReceive('get')->with($key)->once()->andReturn($re);
        });
        $get = Cache::get($key);
        $this->assertTrue($get == $re);
    }

    /**
     * 测试之间的相互独立，mock不会相互干扰2
     */
    public function test_mock_independent_2()
    {

        $key = $this->cache_key;
        Config::set('cache.type', 'File');
        Config::set('cache.path', __DIR__ . '../temp');

        $get = Cache::get($key);

        $this->assertTrue($get == false);
    }
}