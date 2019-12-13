<?php
// Unit of Work
// PHP объекты, шаблоны и методики программирования p325
// Аналогичен Identity Map, но про сохранения объектов, а не загрузку.

class ObjectWatcher
{
    private $all = [];
    private $dirty = [];
    private $new = [];
    private $delete = []; //в этом примере не используется.
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

    public static function exists($classname, $id)
    {
        $inst = self::instance();
        $key = "{$classname}.{$id}";
        if (isset($inst->all[$key])) {
            return $inst->all[$key];
        }
        return null;
    }

    public static function add(DomainObject $obj)
    {
        $inst = self::instance();
        $inst->all[$inst->globalKey($obj)] = $obj;
    }

    public static function addNew(DomainObject $obj)
    {
        $inst = self::instance();
        // у нас еще нет id
        $inst->new[] = $obj;
    }

    public static function addDelete(DomainObject $obj)
    {
        $inst = self::instance();
        $inst->delete[$inst->globalKey($obj)] = $obj;
    }

    public static function addDirty(DomainObject $obj)
    {
        $inst = self::instance();
        if (!in_array($obj, $inst->new, true)) {
            $inst->dirty[$inst->globalKey($obj)] = $obj;
        }
    }

    // пометить объект, как "неизмененный"
    public static function addClean(DomainObject $obj)
    {
        $inst = self::instance();
        unset($inst->dirty[$inst->globalKey($obj)]);
        unset($inst->delete[$inst->globalKey($obj)]);
        $inst->new = array_filter($inst->new, function ($a) use ($obj) {
            return !($a === $obj);
        });
    }

    public function performOperations()
    {
        foreach ($this->dirty as $key => $obj) {
            $obj->finder()->update($obj);
        }
        foreach ($this->new as $key => $obj) {
            $obj->finder()->insert($obj);
        }
        $this->dirty = [];
        $this->new = [];
    }
}

abstract class DomainObject
{
    private $id = -1;

    public function __construct($id = null)
    {
        if (is_null($id)) {
            $this->markNew();
        } else {
            $this->id = $id;
        }
    }

    public function markNew()
    {
        ObjectWatcher::addNew($this);
    }

    public function markDeleted()
    {
        ObjectWatcher::addDelete($this);
    }

    public function markDirty()
    {
        ObjectWatcher::addDirty($this);
    }

    public function markClean()
    {
        ObjectWatcher::addClean($this);
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    // возвращает Data Mapper
    public function finder()
    {
        return self::getFinder(get_class($this));
    }

    public static function getFinder($type)
    {
        return HelperFactory::getFinder($type);
    }
}

//В Data Mapper нужно модифицировать метод createObject
class DataMapper
{
    public function createObject($arr)
    {
        $obj = $this->getFromMap($arr['id']);
        if ($obj) {
            return $obj;
        }
        $obj = $this->doCreateObject($arr);
        $this->addToMap($obj);
        $obj->markClean();
        return $obj;

    }
}

class Some extends DomainObject
{
    public function setName($name)
    {
        $this->name = $name;
        $this->markDirty();
    }
}
