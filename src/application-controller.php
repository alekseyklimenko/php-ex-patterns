<?php
// Application Controller
// PHP объекты, шаблоны и методики программирования p276
// Application Controller это класс (или набор классов), который Front Controller может использовать,
// чтобы получить команды и нужный view на основе запроса пользователя.

class Controller
{
    private $applicationHelper;

    private function __construct() {}

    public static function run()
    {
        $instance = new Controller();
        $instance->init();
        $instance->handleRequest();
    }

    public function init()
    {
        $this->applicationHelper = ApplicationHelper::instance();
        $this->applicationHelper->init();
    }

    public function handleRequest()
    {
        $request = new Request();
        $appController = $this->applicationHelper::appController();
        while ($cmd = $appController->getCommand($request)) {
            $cmd->execute($request);
        }
        $this->invokeView($appController->getView($request));
    }
}
