<?php
// Domain Object Factory
// PHP объекты, шаблоны и методики программирования p332
// А что, если взять Data Mapper и поделить)
// Вынесем метод DataMapper::createObject в отдельный шаблон

abstract class DomainObjectFactory
{
    abstract public function createObject(array $arr);
}

class SomeObjectFactory extends DomainObjectFactory
{
    public function createObject(array $arr)
    {
        $obj = \some\domain\Object($arr['id']);
        $obj->setName($arr['name']);
        return $obj;
    }
    // 	cюда же можно добавить методы addToMap() и getFromMap();
}
