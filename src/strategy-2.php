<?php
// Strategy
// PHP объекты, шаблоны и методики программирования p227
// Еще один пример стратегии.

abstract class Question
{
    protected $prompt;
    protected $marker;

    public function __construct($prompt, Marker $marker)
    {
        $this->marker = $marker;
        $this->prompt = $prompt;
    }

    public function mark($responce)
    {
        $this->marker->mark($responce);
    }
}

class TextQuestion extends Question
{
    // some specific logic
}

class AVQuestion extends Question
{
    // some specific logic
}


abstract class Marker
{
    protected $test;

    public function __construct($test)
    {
        $this->test = $test;
    }

    abstract function mark($responce);
}

class MarkLogicMarker extends Marker
{
    private $engine;

    public function __construct($test)
    {
        parent::__construct($test);
        //$this->engine = new MarkParse($test); //will be implemented in later examples
    }

    public function mark($responce)
    {
        //return $this->engine->evaluate($responce);
        return true;
    }
}

class MatchMarker extends Marker
{
    public function mark($responce)
    {
        return $this->test == $responce;
    }
}

class RegexpMarker extends Marker
{
    public function mark($responce)
    {
        return preg_match($this->test, $responce);
    }
}


$markers = [
    new RegexpMarker('/П.ть/'),
    new MatchMarker('Пять'),
    new MarkLogicMarker('$input equals "Пять"'),
];
foreach ($markers as $marker) {
    echo get_class($marker) . "\n";
    $question = new TextQuestion('Сколько пальцев на руке?', $marker);
    foreach (['Пять', 'Шесть'] as $responce) {
        echo "Ответ: {$responce}: ";
        if ($question->mark($responce)) {
            echo "правильно\n";
        } else {
            echo "не правильно\n";
        }
    }
}
