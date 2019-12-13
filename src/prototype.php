<?php
// Prototype
// PHP объекты, шаблоны и методики программирования p190
// У шаблонов Abstract Factory и Factory Method есть недостаток: плодится много классов.
// Prototype помогает сократить количество классов, заменив наследование композицией.
// Пример шаблона в сеттинге игры "Цивилизация"

// Типы местности
class Sea
{
    private $navigability = 0;
    public function __construct($navigability)
    {
        $this->navigability = $navigability;
    }
}
class EarthSea extends Sea {}
class MarsSea extends Sea {}

class Plains {}
class EarthPlains extends Plains {}
class MarsPlains extends Plains {}

class Forest {}
class EarthForest extends Forest {}
class MarsForest extends Forest {}

// Фабрика, клонирующая объекты, которые были переданы ей при инициализации.
// (шаблон Prototype)
class TerrainFactory
{
    private $sea;
    private $forest;
    private $plains;

    public function __construct(Sea $sea, Plains $plains, Forest $forest)
    {
        $this->sea = $sea;
        $this->plains = $plains;
        $this->forest = $forest;
    }

    public function getSea()
    {
        return clone $this->sea;
    }

    public function getPlains()
    {
        return clone $this->plains;
    }

    public function getForest()
    {
        return clone $this->forest;
    }
}

// Можно составлять довольно сложную композицию объектов:
//$factory = new TerrainFactory(new EarthSea(-1, new Resource(/*FishResource|OilResource*/)), new EarthPlains(), new EarthForest());


$factory = new TerrainFactory(new EarthSea(-1), new EarthPlains(), new EarthForest());

print_r($factory->getSea());
print_r($factory->getPlains());
print_r($factory->getForest());

// Клонируя объекты нужно не забывать клонировать все вложенные объекты:

class Contained {}

class Container
{
    public $contained;

    public function __construct()
    {
        $this->contained = new Contained();
    }

    public function __clone()
    {
        $this->contained = clone $this->contained;
    }
}
