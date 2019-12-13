<?php
// Observer
// PHP объекты, шаблоны и методики программирования p231
// В основе шаблона Observer лежит принцип отсоединения клиентских элементов (наблюдателей)
// от центрального объекта (субъекта). Наблюдатели должны быть проинформированы, когда происходят события.

interface Observable
{
    public function attach(Observer $observer);
    public function detach(Observer $observer);
    public function notify();
}

interface Observer
{
    public function update(Observable $observable);
}

class Login implements Observable
{
    private $observers = [];

    public function attach(Observer $observer)
    {
        $this->observers[] = $observer;
    }

    public function detach(Observer $observer)
    {
        $newobservers = [];
        foreach ($this->observers as $obs) {
            if ($obs !== $observer) {
                $newobservers[] = $obs;
            }
        }
        $this->observers = $newobservers;
    }

    // метод notify вызывает сам класс Login, когда произошло нужное событие.
    public function notify()
    {
        foreach ($this->observers as $obs) {
            $obs->update($this);
        }
    }

    public function getStatus()
    {
        return 'status';
    }
}

class SecurityMonitor implements Observer
{
    public function update(Observable $observable)
    {
        $status = $observable->getStatus();
        // do some work...
    }
}

$login = new Login(); // new Observable()
$login->attach(new SecurityMonitor()); // attach(new Observer())



// В SPL есть реализация Observer, состоящая из SplObserver, SplSubject, SplObjectStorage
// Измененная реализация шаблона Observer:

class Login implements SplSubject
{
    private $storage;

    public function __construct()
    {
        $this->storage = new SplObjectStorage();
    }

    public function attach(SplObserver $observer)
    {
        $this->storage->attach($observer);
    }

    public function detach(Observer $observer)
    {
        $this->storage->detach($observer);
    }

    // метод notify вызывает сам класс Login, когда произошло нужное событие.
    public function notify()
    {
        foreach ($this->storage as $obs) {
            $obs->update($this);
        }
    }
}

// Observer, который сам присоединяется к Observable
// при вызове update, проверяя от кого прилетел вызов
// и вызывая doUpdate, только если нотификация от переданного в конструктор объекта Login.
// Таким образом гарантируется, что $subject будет класса Login с реализацией нужных методов
// (в нашем примере, метод getStatus()
abstract class LoginObserver implements SplObserver
{
    private $login;

    public function __construct(Login $login)
    {
        $this->login = $login;
        $login->attach($this);
    }

    public function update(SplSubject $subject)
    {
        if ($subject === $this->login) {
            $this->doUpdate($subject);
        }
    }

    abstract function doUpdate(Login $login);
}

class SecurityMonitor extends LoginObserver
{
    public function doUpdate(Login $login)
    {
        //do some work
    }
}

class GeneralLogger extends LoginObserver
{
    public function doUpdate(Login $login)
    {
        //do some work
    }
}

$login = new Login();
new SecurityMonitor($login); // Созданный объект сам присоединяется к $login
new GeneralLogger($login);

// Далее работаем только с $login
