<?php
// Decorator
// PHP объекты, шаблоны и методики программирования p207
// Для решения проблемы меняющейся функциональность шаблон Decorator
// использует композицию и делегирование вместо наследования.


abstract class Tile
{
    abstract public function getWealthFactor();
}

class Plains extends Tile
{
    private $wealthFactor = 2;

    public function getWealthFactor()
    {
        return $this->wealthFactor;
    }
}

abstract class TileDecorator extends Tile
{
    protected $tile;

    public function __construct(Tile $tile)
    {
        $this->tile = $tile;
    }
}

class DiamondDecorator extends TileDecorator
{
    public function getWealthFactor()
    {
        return $this->tile->getWealthFactor() + 2;
    }
}

class PolutionDecorator extends TileDecorator
{
    public function getWealthFactor()
    {
        return $this->tile->getWealthFactor() - 4;
    }
}


$tile = new Plains();
echo $tile->getWealthFactor(); // 2

$tile = new DiamondDecorator(new Plains());
echo $tile->getWealthFactor(); // 4

$tile = new PolutionDecorator(new DiamondDecorator(new Plains()));
echo $tile->getWealthFactor(); // 0

// Также, с помощью декораторов можно реализовать, к примеру, логирование.
