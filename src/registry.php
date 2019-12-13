<?php
// Registry
// PHP объекты, шаблоны и методики программирования p255
// Registry - класс, предоставляющий доступ к данным (не обязательно объектам)
// По сути, это Singleton, хранящий в себе ссылки на другие, созданные кем-то объекты.

class Registry
{
    private static $instance;
    private $request;

    private function __construct() 	{}

    public function getRequest()
    {
        // Registry можно использовать как фабрику
        // if (!$this->request) {
        // 	$this->request = new Request(...);
        // }
        return $this->request;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    // можно расширить класс для удобства тестирования
    private static $testMode;
    public static function testMode($mode = true)
    {
        self::$testMode = $mode;
    }

    public static function instance()
    {
        if (self::$testMode) {
            return new MockRegistry();
        }
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
