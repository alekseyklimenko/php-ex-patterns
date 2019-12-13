<?php
// Strategy

// Strategy. Используем композицию, вместо наследования.
// PHP объекты, шаблоны и методики программирования p.161

// С помощъю шаблона можно рассредоточить обязанности по классам.

abstract class Lesson
{
    private $duration;
    /** @var CostStrategy */
    private $costStrategy;

    public function __construct($duration, CostStrategy $strategy)
    {
        $this->duration = $duration;
        $this->costStrategy = $strategy;
    }

    public function cost()
    {
        return $this->costStrategy->cost($this);
    }

    public function chargeType()
    {
        return $this->costStrategy->chargeType();
    }

    public function getDuration()
    {
        return $this->duration;
    }
}

class Lecture extends Lesson
{
    // ...
}

class Seminar extends Lesson
{
    // ...
}

// Объекты CostStrategy ответственны только за расчет стоимости занятия, а Lesson управляют данными занятий.
abstract class CostStrategy
{
    abstract public function cost(Lesson $lesson);
    abstract public function chargeType();
}

class TimedCostStrategy extends CostStrategy
{
    public function cost(Lesson $lesson)
    {
        return ($lesson->getDuration() * 5);
    }

    public function chargeType()
    {
        return 'Почасовая оплата';
    }
}

class FixedCostStrategy extends CostStrategy
{
    public function cost(Lesson $lesson)
    {
        return 30;
    }

    public function chargeType()
    {
        return 'Фиксированная ставка';
    }
}

// Теперь можно легко комбинировать разные сочетания типов занятий и форм оплаты.

$lessons[] = new Seminar(4, new TimedCostStrategy());
$lessons[] = new Lecture(4, new FixedCostStrategy());

foreach ($lessons as $lesson) {
    echo 'Оплата за занятие ' . $lesson->cost() . '. ';
    echo 'Тип оплаты: ' .$lesson->chargeType() . PHP_EOL;
}
