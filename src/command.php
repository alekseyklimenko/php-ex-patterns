<?php
// Command

// PHP объекты, шаблоны и методики программирования p245
// Позволяет отделить уровень контроллера от уровня бизнес-логики.
// Команды выполняют мало логических операций, они проверяют входные данные, обрабатывают ошибки, сохраняют данные
// и вызывают другие методы для выполнения операций.

abstract class Command
{
    abstract public function execute(CommandContext $context);
}

class LoginCommand extends Command
{
    public function execute(CommandContext $context)
    {
        $manager = Registry::getAccessManager();
        $username = $context->get('username');
        $password = $context->get('password');
        $user = $manager->login($username, $password);
        if (!$user) {
            $context->setError($manager->getError());
            return false;
        }
        $context->addParam('user', $user);
        return true;
    }
}

// Класс CommandContext - оболочка над ассоциативным массивом с определенным интерфейсом.

// Объект класса Command создается, к примеру фабрикой, на основе запрошенного url.
// пример вызывающего кода:

$cmd = CommandFactory::getCommand($request->action);
$cmd->execute($this->context);
