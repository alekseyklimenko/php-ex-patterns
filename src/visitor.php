<?php
// Visitor
// PHP объекты, шаблоны и методики программирования p238
// Шаблон Visitor позволяет добавить определенные функции в Composite, не раздувая интерфейс композита.
// Расширим пример из описания Composite

class UnitException extends Exception {}

abstract class Unit
{
    protected $depth;

    abstract public function bonbardStrenght();

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

    public function accept(ArmyVisitor $visitor)
    {
        $method = 'visit' . get_class($this);
        $visitor->$method($this);
    }

    protected function setDepth()
    {
        $this->depth = $depth;
    }

    public function getDepth()
    {
        return $this->depth;
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
        $unit->setDepth($this->depth+1);
        $this->units[] = $unit;
    }

    public function removeUnit(Unit $unit)
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

    public function accept(ArmyVisitor $visitor)
    {
        parent::accept($visitor);
        foreach ($this->units as $unit) {
            $unit->accept($visitor);
        }
    }
}

abstract class ArmyVisitor
{
    abstract public function visit(Unit $unit);

    public function visitArcher(Archer $unit)
    {
        $this->visit($unit);
    }

    public function visitLaserCannonUnit(Archer $unit)
    {
        $this->visit($unit);
    }

    public function visitArmy(Army $unit)
    {
        $this->visit($unit);
    }
}

class TextDumpArmyVisitor extends ArmyVisitor
{
    private $text = '';

    public function visit(Unit $unit)
    {
        $text = "Ударная сила: {$unit->bombardStrenght()}\n";
        $pad = $unit->getDepth();
        // $text .= 'что-то еще очень важное';
        $this->text .= $text;
    }

    public function getText()
    {
        return $this->text;
    }
}

class TaxCollectionVisitor extends ArmyVisitor
{
    private $due = 0;
    private $report = '';

    public function visit(Unit $unit)
    {
        $this->levy($unit, 1);
    }

    public function visitArcher(Archer $unit)
    {
        $this->levy($unit, 2);
    }

    public function visitLaserCannonUnit(Archer $unit)
    {
        $this->levy($unit, 5);
    }

    private function levy(Unit $unit, $amount)
    {
        $this->report .= 'Налог для ' . get_class($unit);
        $this->report .= ": $amount\n";
        $this->due += $amount;
    }

    public function getReport()
    {
        return $this->report;
    }

    public function getTax()
    {
        return $this->due;
    }
}


$mainArmy = new Army();
$mainArmy->addUnit(new Archer());
$mainArmy->addUnit(new LaserCannonUnit());

$subArmy = new Army();
$subArmy->addUnit(new Archer());
$subArmy->addUnit(new Archer());
$subArmy->addUnit(new Archer());
$mainArmy->addUnit($subArmy);

$textDump = new TextDumpArmyVisitor();
$mainArmy->accept($textDump);
echo $textDump->getText();

$taxCollector = new TaxCollectionVisitor();
$mainArmy->accept($taxCollector);
echo $taxCollector->getReport();
echo 'Итого: ' . $taxCollector->getTax() . "\n";
