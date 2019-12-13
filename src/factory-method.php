<?php
// Factory Method
// PHP объекты, шаблоны и методики программирования p179

// Шаблон Factory Method позволяет использовать наследование и полиморфизм,
// чтобы инкапсулировать создание конкретных продуктов.
// Другими словами, для каждого протокола(ApptEncoder) создается свой подкласс типа CommsManager,
// в катором реализован свой метод getApptEncoder() (собственно, фабричный метод)

// Класс-кодировщик. Преобразует некие входящие данные в наш формат.
abstract class ApptEncoder
{
    abstract function encode();
}

class BloggsApptEncoder extends ApptEncoder
{
    public function encode()
    {
        // преобразуем данные из формата Bloggs
    }
}

class MegaApptEncoder extends ApptEncoder
{
    public function encode()
    {
        // преобразуем данные из формата Mega
    }
}

// класс, работающий с данными, поступившими от кодировщика
abstract class CommsManager
{
    abstract function getHeaderText();
    abstract function getApptEncoder();
    abstract function getFooterText();
}

class BloggsCommsManager extends CommsManager
{
    public function getHeaderText()
    {
        // специфика создания заголовка для формата Bloggs
    }

    public function getFooterText()
    {
        // специфика создания футера для формата Bloggs
    }

    // создание подходящено кодировщика
    public function getApptEncoder()
    {
        return new BloggsApptEncoder();
    }
}

class MegaCommsManager extends CommsManager
{
    public function getHeaderText()
    {
        // специфика создания заголовка для формата Mega
    }

    public function getFooterText()
    {
        // специфика создания футера для формата Mega
    }

    // создание подходящено кодировщика
    public function getApptEncoder()
    {
        return new MegaApptEncoder();
    }
}

// Метод BloggsCommsManager::getApptEncoder() возвращает объект типа BloggsApptEncoder.
// Клиентский код, вызывающий getApptEncoder(), ожидает получить объект типа ApptEncoder
// и необязательно должен знать что-либо о конкретном классе продукта, который он получил.
