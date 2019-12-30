# xwpd/thinkphp-testing 
一个测试友好的ThinkPHP测试扩展

## 安装
```bash
composer require xwpd/thinkphp-testing --dev
```
修改 phpunit.xml 文件,在 phpunit 标签加入 bootstrap="vendor/autoload.php" 
```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit 
        ...
        bootstrap="vendor/autoload.php"
        ...
>
...
</phpunit>
```
## 使用时注意
1. 测试类必须继承 Xwpd\ThinkTesting\TestCase 测试类
1. 如果不是使用thinkPHP命令（php think unit） 运行的测试，需要设置 $app_path 和加载基础文件。   
例：
    ```php
    namespace Tests;
    
    use Xwpd\ThinkTesting\TestCase as BaseTestCase;
    
    abstract class TestCase extends BaseTestCase
    {
        protected $app_path = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'application';//指定应用目录
        protected $baseUrl = 'http://localhost';
    
        public function __construct($name = null, array $data = [], $dataName = '')
        {
            require_once __DIR__ . '/../thinkphp/base.php';//加载基础文件
            parent::__construct($name, $data, $dataName);
        }
    }
    ```
   
## 功能
1.  模拟对象  
    只要是使用容器进行管理的对象，都可以使用以下方法进行模拟(具体的 [Mockery](http://docs.mockery.io/en/latest/) 使用方法，请参考 [文档](http://docs.mockery.io/en/latest/)：
    ```php
    use Mockery;
    use Mockery\Mock;
    
    $this->instance('think\Cache', Mockery::mock('think\Cache', function ($mock) {
        /**
         * @var Mock $mock
         */
        $return='return';
        $key='key';
        return $mock->shouldReceive('get')->with($key)->andReturn($return);
    }));
    ```
   
    为了让以上过程更加便捷：
    
    ```php
    use Mockery;
    use Mockery\Mock;
    
    $this->mock('think\Cache', function ($mock) {
        /**
         * @var Mock $mock
         */
        $return='return';
        $key='key';
        return $mock->shouldReceive('get')->with($key)->andReturn($return);
     });
    ```
    同样，如果你想侦查一个对象，基本测试用例类提供了一个便捷的 spy 方法作为 Mockery::spy 的替代方法:
    
    ```php
    use App\Service;
    use Mockery\Mock;
    
    $this->spy('think\Cache', function ($mock) {
        /**
         * @var Mock $mock
         */
        $return='return';
        $key='key';
        return $mock->shouldReceive('get')->with($key)->andReturn($return);
    });
    ```
        
1.  每次测试后数据库回滚  
    每次运行测试用例后，为了不互相污染数据，可以选择数据库回滚。   
    例：
    ```php
    namespace Tests;
    
    use Xwpd\ThinkTesting\TestCase as BaseTestCase;
    use Xwpd\ThinkTesting\Traits\DatabaseTransactions;
    
    abstract class TestCase extends BaseTestCase
    {
        use DatabaseTransactions;//每次测试回滚数据
        protected $app_path = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'application';//指定应用目录
        protected $baseUrl = 'http://localhost';
    
        public function __construct($name = null, array $data = [], $dataName = '')
        {
            require_once __DIR__ . '/../thinkphp/base.php';//加载基础文件
            parent::__construct($name, $data, $dataName);
        }
    }
    ```
         
1. Facade mock 模拟  
    安装扩展(不要加--dev) 
    ```bash
    composer require xwpd/thinkphp-facade 
    ```
    具体使用，请参考 [xwpd/thinkphp-facade](https://github.com/xwpd/thinkphp-facade)
    
1. 模型工厂  
    安装扩展(加--dev) 
    ```bash
    composer require xwpd/thinkphp-model-factory 
    ```
   具体使用，请参考 [xwpd/thinkphp-model-factory](https://github.com/xwpd/thinkphp-model-factory)