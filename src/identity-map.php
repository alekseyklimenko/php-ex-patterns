<?php
// Identity Map
// PHP объекты, шаблоны и методики программирования p321
// Используется для предотвращения следующей проблемы Data Mapper-а:
// $val1 = $model->getById(1);
// $val2 = $model->getById($val1->id);
// $val1->setName('qwe');
// $val1->save();
// $val2->save();

class ObjectWatcher
{
    private $all = [];
    private static $instance;

    private function __construct() 	{}

    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function globalKey(DomainObject $obj)
    {
        return get_class($obj) . '.' . $obj->getId();
    }

    public static function add(DomainObject $obj)
    {
        $inst = self::instance();
        $inst->all[$inst->globalKey($obj)] = $obj;
    }

    public static function exists($classname, $id)
    {
        $inst = self::instance();
        $key = "{$classname}.{$id}";
        if (isset($inst->all[$key])) {
            return $inst->all[$key];
        }
        return null;
    }
}

// Фрагмент Data Mapper-a
abstract class Mapper
{
    private function getFromMap($id)
    {
        return ObjectWatcher::exists($this->getClass(), $id);
    }

    private function addToMap(DomainObject $obj)
    {
        ObjectWatcher::add($obj);
    }

    public function find($id)
    {
        $obj = $this->getFromMap($id);
        if ($obj) {
            return $obj;
        }
        // get from DB
        return $obj;
    }

    public function createObject($arr)
    {
        $obj = $this->getFromMap($arr['id']);
        if ($obj) {
            return $obj;
        }
        $obj = new DomainObject();
        // ......
        $this->addToMap($obj);
        return $obj;
    }

    public function insert(DomainObject $obj)
    {
        //insert into DB and get PK
        $this->addToMap($obj);
    }

    abstract protected function getClass();
}
