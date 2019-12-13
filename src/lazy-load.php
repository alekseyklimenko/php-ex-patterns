<?php
// Lazy load
// PHP объекты, шаблоны и методики программирования p329
// (см DataMapper p307)

class DeferredEventCollection extends EventCollection
{
    private $stmt;
    private $valueArray;
    private $run = false;

    public function __construct(Mapper $mapper, \PDOStatement $stmtHandle, array $valueArray)
    {
        parent::__construct(null, $mapper);
        $this->stmt = $stmtHandle;
        $this->valueArray = $valueArray;
    }

    //notifyAccess вызывается из любого метода Data Mapper-a, вызов которого был выполнен извне.
    public function notifyAccess()
    {
        if (!$this->run) {
            $this->stmt->execute($this->valueArray);
            $this->raw = $this->stmt->fetchAll();
            $this->total = count($this->raw);
        }
        $this->run = true;
    }
}
