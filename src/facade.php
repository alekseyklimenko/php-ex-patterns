<?php
// Facade
// PHP объекты, шаблоны и методики программирования p213
// Facade предоставляет простой интерфейс для сложных систем.

class Product
{
    public $id;
    public $name;
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}

// некий "сложный" процедурный код:
function getProductFileLines($file)
{
    return file($file);
}

function getNameFromLine($line)
{
    // какая-то обработка...
    return $line;
}

function getIdFromLine($line)
{
    // какая-то обработка...
    return $line;
}

function getProductObjectFromId($id, $name)
{
    // какая-то обработка...
    return new Product($id, $name);
}

// использование кода:
$lines = getProductFileLines('file.txt');
$objects = [];
foreach ($lines as $line) {
    $id = getIdFromLine($line);
    $name = getNameFromLine($line);
    $objects[$id] = getProductObjectFromId($id, $name);
}

// Facade для вот этого всего
class ProductFacade
{
    private $products = [];
    private $file;

    public function __construct($file)
    {
        $this->file = $file;
        $this->compile();
    }

    private function compile()
    {
        $lines = getProductFileLines($this->file);
        foreach ($lines as $line) {
            $id = getIdFromLine($line);
            $name = getNameFromLine($line);
            $this->products[$id] = getProductObjectFromId($id, $name);
        }
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function getProduct($id)
    {
        return $this->products[$id];
    }
}

$facade = new ProductFacade('file.txt');
$objects = $facade->getProducts();
