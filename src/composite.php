<?php
// Composite
// PHP объекты, шаблоны и методики программирования p197
// Шаблон Composite позволяет группировать объекты, при этом у объекта-композита остается тот же интерфейс, т.е.
// к композиту можно обращаться так же как и к любому объекту-элементу.
// Композит может содержать в себе другие объекты-композиты.


class UnitException extends Exception {}

abstract class Unit
{
    abstract public function bombardStrenght();

    public function addUnit(Unit $unit)
    {
        // Реализовать в композите.
        throw new UnitException(get_class($this) . "is class-element and cannot call addUnit()");
    }

    public function removeUnit(Unit $unit)
    {
        // Реализовать в композите.
        throw new UnitException(get_class($this) . "is class-element and cannot call removeUnit()");
    }
}

// Классы-элементы
class Archer extends Unit
{
    public function bombardStrenght()
    {
        return 4;
    }
}

class LaserCannonUnit extends Unit
{
    public function bombardStrenght()
    {
        return 44;
    }
}

// Класс-композит
class Army extends Unit
{
    private $units = [];

    public function addUnit(Unit $unit)
    {
        if (in_array($unit, $this->units, true)) {
            return;
        }
        $this->units[] = $unit;
    }

    public function removeUnit($unit)
    {
        $this->units = array_diff(
            $this->units,
            [$unit],
            function ($a, $b) {return ($a === $b) ? 0 : 1;}
        );
    }

    public function bombardStrength()
    {
        $result = 0;
        foreach ($this->units as $unit) {
            $result += $unit->bombardStrength();
        }
        return $result;
    }
}

// Использование
$mainArmy = new Army();
$mainArmy->addUnit(new Archer());
$mainArmy->addUnit(new LaserCannonUnit());

$subArmy = new Army();
$subArmy->addUnit(new Archer());
$subArmy->addUnit(new Archer());
$subArmy->addUnit(new Archer());

$mainArmy->addUnit($subArmy);
echo "Общая ударная сила: {$mainArmy->bombardStrength()}\n";
