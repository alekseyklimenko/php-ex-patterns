<?php
// Front Controller
// PHP объекты, шаблоны и методики программирования p266
// Front Controller определяет центральную точку входа для каждого запроса.
// Он обрабатывает запрос и использует его, чтобы выбрать операцию для выполнения.
// Операции, как правило - шаблон Command.

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
        $cmdr = new CommandResolver();
        $cmd = $cmdr->getCommand($request);
        $cmd->execute($request);
    }
}

// Пример класса CommandResolver
class CommandResolver
{
    private static $baseCommand;
    private static $defaultCommand;

    public function __construct()
    {
        if (!self::$baseCommand) {
            self::$baseCommand = new \ReflectionClass('\woo\command\Command');
            self::$defaultCommand = new DefaultCommand();
        }
    }

    public function getCommand(Request $request)
    {
        $cmd = $request->getProperty('cmd');
        $sep = DIRECTORY_SEPARATOR;
        if (!$cmd) {
            return self::$defaultCommand;
        }
        $cmd = str_replace(['.', $sep], '', $cmd);
        $filepath = "woo{$sep}command{$sep}{$cmd}.php";
        $classname = "woo\\commamd\\{$cmd}";
        if (file_exists($filepath)) {
            @require_once($filepath);
            if (class_exists($class)) {
                $cmdClass = new ReflectionClass($classname);
                if ($cmdClass->isSubClassOf(self::baseCommand)) {
                    return $cmdClass->newInstance();
                } else {
                    $request->addFeedback("Объект Command Команды {$cmd} не найден");
                }
            }
        }
        $request->addFeedback("Команда {$cmd} не найдена");
        return clone se;f::$defaultCommand;
    }
}

abstract class Command
{
    // делаем невозможным заменить конструктор в дочерних классах
    // (чтоб, для нашего примера, гарантированно был конструктор без параметров)
    final public function __construct() {}

    public function execute(Request $request)
    {
        $this->doExecute($request);
    }

    abstract public function doExecute(Request $request);
}

// запуск кода в index.php
require('namespace/controller/Controller.php');
Controller::run();
