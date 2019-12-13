<?php
// Abstract Factory
// PHP объекты, шаблоны и методики программирования p184
// см также предыдущий Factory Method

// Теперь у нас есть несколько продуктов, для каждого из которых нужен свой кодировщик из каждого формата.
abstract class CommsManager
{
    abstract function getHeaderText();
    abstract function getApptEncoder();     // встречи
    abstract function getTtdEncoder();      // дела
    abstract function getContactEncoder();  // контакты
    abstract function getFooterText();
}

class BloggsCommsManager extends CommsManager
{
    public function getHeaderText()
    {
        // специфика создания заголовка для формата Bloggs
    }

    public function getFooterText()
    {
        // специфика создания футера для формата Bloggs
    }

    // Классы BloggsApptEncoder, BloggsTtdEncoder, BloggsContactEncoder
    // не связаны наследованием, могут реализавывать единый интерфейс.
    public function getApptEncoder()
    {
        return new BloggsApptEncoder();
    }

    public function getTtdEncoder()
    {
        return new BloggsTtdEncoder();
    }

    public function getContactEncoder()
    {
        return new BloggsContactEncoder();
    }
}

// PS. Фактически это переиспользование шаблона Factory Method.
// Для динамически-типизированных языков, где нет ограничения на тип возвращаемого результата
// можно использовать модифицированный вариант.

abstract class CommsManager2
{
    const APPT = 1;
    const TTD = 2;
    const CONTACT = 3;
    abstract function getHeaderText();
    abstract function make($flag);
    abstract function getFooterText();
}

class BloggsCommsManager2 extends CommsManager2
{
    public function getHeaderText()
    {
        // специфика создания заголовка для формата Bloggs
    }

    public function getFooterText()
    {
        // специфика создания футера для формата Bloggs
    }

    public function make($flag)
    {
        switch($flag) {
            case self::APPT:
                return new BloggsApptEncoder();
            case self::CONTACT:
                return new BloggsContactEncoder();
            case self::TTD:
                return new BloggsTtdEncoder();
        }
        return null;
    }
}
