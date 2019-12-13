### Specification Pattern ###
http://culttt.com/2014/08/25/implementing-specification-pattern/

What is The Specification Pattern?

The Specification Pattern is a way of encapsulating business rules to return a boolean value. By encapsulating a business rule within a Specification object, we can create a class that has a single responsibility, but can be combined with other Specification objects to create maintainable rules that can be combined to satisfy complex requirements.

The Specification object has a single public isSatisfiedBy() method that will return a boolean value:

```php
class UsernameIsUnique implements UsernameSpecification {
  /** @return bool **/
  public function isSatisfiedBy(Username $username)
  {
    //
  }
}
```

UML: https://en.wikipedia.org/wiki/File:Specification_UML_v2.png
Пример реализации: https://github.com/mbrevda/SpecificationPattern/blob/master/tests/Mocks/OverDueSpecification.php
