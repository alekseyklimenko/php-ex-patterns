<?php
// Late static binding
// Позднее статическое связывание.
// PHP объекты, шаблоны и методики программирования. p70

abstract class DomainObject
{
    private $group;

    public function __construct()
    {
        $this->group = static::getGroup();
    }

    public static function create()
    {
        return new static();
    }

    public static function getGroup()
    {
        return 'default';
    }
}

class User extends DomainObject {}
class Document extends DomainObject
{
    public static function getGroup()
    {
        return 'document';
    }
}
class Spreadsheet extends Document {}

print_r(User::create()); // default
print_r(Spreadsheet::create()); // document
