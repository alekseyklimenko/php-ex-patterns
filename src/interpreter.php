<?php
// Interpreter
// PHP объекты, шаблоны и методики программирования p217
// Поведенческий шаблон проектирования, решающий часто встречающуюся, но подверженную изменениям задачу.
// Также известен как Little (Small) Language

/*
Для примера создадим DSL, который сможет интерпретировать выражение:
$input equals "4" or $input equals "четыре"
т.е. на поставленный вопрос возможно два правильных ответа: "4" или "четыре".

Элементы грамматики языка:

Описание			Имя EBNF		Имя класса				Пример
Переменная			variable 		VariableExpression		$input
Строковый литерал	<stringLiteral> LiteralExpression		"четыре"
Булево "И"			andExpr			BooleanAndExpression	$input equals "4" and $other equals "6"
Булева "ИЛИ"		orExpr 			BooleanOrExpression		$input equals "4" or $other equals "6"
Проверка равенства	equalsExpr 		EqualsExpression		$input equals "4"

EBNF - Extended Backus-Naur Form

expr ::= operand (orExpr | andExpr)*
operand ::= ( '(' expr ')' | <stringLiteral> | variable ) ( eqExpr )*
orExpr ::= 'or' operand
andExpr ::= 'and' operand
equalsExpr ::= 'equals' operand
variable ::= '$' <word>
*/

// Базовый класс, возвращающий уникальный дескриптор.
abstract class Expression
{
    private static $keyCount = 0;
    private $key;

    abstract function interpret(InterpreterContext $context);

    public function getKey()
    {
        if (!isset($this->key)) {
            self::$keyCount++;
            $this->key = self::$keyCount;
        }
        return $this->key;
    }
}


class LiteralExpression extends Expression
{
    private $value;

    public function __construct ($value)
    {
        $this->value = $value;
    }

    public function interpret(InterpreterContext $context)
    {
        $context->replace($this, $this->value);
    }
}

class InterpreterContext
{
    private $expressionStore = [];

    public function replace(Expression $expr, $value)
    {
        $this->expressionStore[$expr->getKey()] = $value;
    }

    public function lookUp(Expression $expr)
    {
        return $this->expressionStore[$expr->getKey()];
    }
}

// Зададим строку.
$context = new InterpreterContext();
$literal = new LiteralExpression('четыре');
$literal->interpret($context);
echo $context->lookUp($literal); //четыре


class VariableExpression extends Expression
{
    private $name;
    private $value;

    public function __construct ($name, $value = null)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function interpret(InterpreterContext $context)
    {
        if (!is_null($this->value)) {
            $context->replace($this, $this->value);
            $this->value = null;
        }
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getKey()
    {
        return $this->name;
    }
}

// Зададим переменную.
$myvar = new VariableExpression('input', 'четыре');
$myvar->interpret($context);
echo $context->lookUp($myvar); //четыре

$myvar2 = new VariableExpression('input');
$myvar2->interpret($context);
echo $context->lookUp($myvar2); //четыре

$myvar->setValue('пять');
$myvar->interpret($context);
echo $context->lookUp($myvar); //пять
echo $context->lookUp($myvar2); //пять


abstract class OperatorExpression extends Expression
{
    protected $lOp;
    protected $rOp;

    public function __construct(Expression $lOp, Expression $rOp)
    {
        $this->lOp = $lOp;
        $this->rOp = $rOp;
    }

    public function interpret(InterpreterContext $context)
    {
        // тут шаблон Composite
        $this->lOp->interpret($context);
        $this->rOp->interpret($context);
        $resultL = $context->lookUp($this->lOp);
        $resultR = $context->lookUp($this->rOp);
        $this->doInterpret($context, $resultL, $resultR);
    }

    /* метод doInterpret представляет собой экземпляр шаблона Template Method.
       В этом шаблоне в родительском классе и определяется, и вызывается абстрактный метод,
       реализация которого оставляется дочерним классам.
    */
    protected abstract function doInterpret(InterpreterContext $context, $resultL, $resultR);
}

class EqualsExpression extends OperatorExpression
{
    protected function doInterpret(InterpreterContext $context, $resultL, $resultR)
    {
        $context->replace($this, $resultL == $resultR);
    }
}

class BooleanOrExpression extends OperatorExpression
{
    protected function doInterpret(InterpreterContext $context, $resultL, $resultR)
    {
        $context->replace($this, $resultL || $resultR);
    }
}

class BooleanAndExpression extends OperatorExpression
{
    protected function doInterpret(InterpreterContext $context, $resultL, $resultR)
    {
        $context->replace($this, $resultL && $resultR);
    }
}

// создадим выражение с переменной, которой еще не присвоено значение.
$input = new VariableExpression('input');
$statement = new BooleanOrExpression(
    new EqualsExpression($input, new LiteralExpression('четыре')),
    new EqualsExpression($input, new LiteralExpression('4'))
);

foreach (['четыре', '4', '5'] as $val) {
    echo $val . ' - ';
    $input->setValue($val);
    $statement->interpret($context);
    if ($context->lookUp($statement)) {
        echo "соответствует\n";
    } else {
        echo "не соответствует\n";
    }
}
