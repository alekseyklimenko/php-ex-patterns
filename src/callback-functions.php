<?php
// Callback Functions
// Функции обратного вызова, анонимные функции, замыкания.
// PHP объекты, шаблоны и методики программирования p90

// возможность расширить функциональность объекта не модифицируя его исходный код.
class Product
{
    public $name;
    public $price;

    public function __construct($name, $price)
    {
        $this->name = $name;
        $this->price = $price;
    }
}

class ProcessSale
{
    private $callbacks;

    public function registerCallback($callback)
    {
        if (!is_callable($callback)) {
            throw new Exception('Callback is not callable!');
        }
        $this->callbacks[] = $callback;
    }

    public function sale($product)
    {
        echo "{$product->name}: in process...\n";
        foreach ($this->callbacks as $callback) {
            call_user_func($callback, $product);
        }
    }
}

$logger = function ($product) {
    echo "Recording...{$product->name}\n";
};

$processor = new ProcessSale();
$processor->registerCallback($logger);
$processor->sale(new Product('Tea', 10));

// Можно использовать фабрику анонимных функций (p.94)
// Также, в данном примере используется замыкание и переменные из родительской области видимости анонимной функции.

class Totalizer
{
    public static function warnAmount($amt)
    {
        $count = 0;
        return function ($product) use ($amt, &$count) {
            $count += $product->price;
            echo "Sum: {$count}\n";
            if ($count > $amt) {
                echo "Sold in total: {$count}\n";
            }
        };
    }
}

$processor->registerCallback(Totalizer::warnAmount(10));
