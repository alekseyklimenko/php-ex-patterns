<?php
// Page Controller
// PHP объекты, шаблоны и методики программирования p288
// Упрощенная версия Front Controller. Фактически - это привычный для web-а контроллер.

abstract class PageController
{
    private $request;

    public function __construct()
    {
        $request = RequestRegistry::getRequest();
        if (!$request) {
            $request = new $request();
        }
        $this->request = $request;
    }

    abstract public function process();

    public function forward($resource)
    {
        include($resource);
        exit(0);
    }

    public function getRequest()
    {
        return $this->request;
    }
}

class SomeController extends PageController
{
    public function process()
    {
        //some code here
    }
}
