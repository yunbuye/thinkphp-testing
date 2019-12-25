# xwpd/thinkphp-testing 
一个ThinkPHP友好的测试扩展

## 安装
```bash
composer require xwpd/thinkphp-testing --dev
```
修改 phpunit.xml 文件,在 phpunit 标签加入 bootstrap="vendor/autoload.php" 
```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit 
         bootstrap="vendor/autoload.php"
>
....
</phpunit>
```
## 使用时注意
1. 测试类必须继承 Xwpd\ThinkTesting\TestCase 测试类
1. 如果不是使用thinkPHP命令（php think unit） 运行的测试，需要设置 $app_path 。   
例：
    ```php
    namespace Tests;
    
    use Xwpd\ThinkTesting\TestCase as BaseTestCase;
    /**
     * Class TestCase
     * @package Tests
     * @mixin \PHPUnit\Framework\TestCase
     */
    abstract class TestCase extends BaseTestCase
    {
      /**
       * 指定应用目录
       * @var string 
       */
        protected $app_path = __DIR__ . '/../application/';
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
    use Xwpd\ThinkTesting\DatabaseTransactions;
    /**
     * Class TestCase
     * @package Tests
     * @mixin \PHPUnit\Framework\TestCase
     */
    abstract class TestCase extends BaseTestCase
    {
      /**
       * 指定应用目录
       * @var string 
       */
        protected $app_path = __DIR__ . '/../application/';
    
       /**
       * 使用数据库回滚
       */
        use  DatabaseTransactions;
        /**
        * 指定要回滚的数据库连接
        */
        protected $connectionsToTransact = [
           null,//注意null值代表默认数据库，不设置则默认会回滚默认数据库
            'mysql_1'//其他非默认数据库连接名
        ];
    }
    ```
         
1. Facade mock 模拟  
    安装扩展(不要加--dev) 
    ```bash
    composer require xwpd/thinkphp-facade 
    ```
    具体使用，请参考 xwpd/thinkphp-facade 
    
1. 模型工厂  
modelFact
 