<?php

namespace QueryBuilder;

use QueryBuilder\Expression\CompositeExpression;
use QueryBuilder\Expression\ExpressionBuilder;
use QueryBuilder\Part\Limit;
use QueryBuilder\Part\Order;
use QueryBuilder\Part\PartInterface;
use QueryBuilder\Part\Select;
use QueryBuilder\Part\Table;
use QueryBuilder\Part\Where;
use Throwable;

/**
 * Class QueryBuilder
 *
 * @package QueryBuilder
 */
class QueryBuilder
{
    /**
     * @var PartInterface[]
     */
    protected $parts = [];

    /**
     * @var ExpressionBuilder
     */
    protected $expBuilder;

    /**
     * @param PartInterface $part
     *
     * @return self
     */
    public function addPart(PartInterface $part): self
    {
        $this->parts[$part->getType()] = $part;
        return $this;
    }

    /**
     * @return ExpressionBuilder
     */
    public function exp(): ExpressionBuilder
    {
        if (!isset($this->expBuilder)) {
            $this->expBuilder = new ExpressionBuilder();
        }
        return $this->expBuilder;
    }

    /**
     * @param string $tableName
     *
     * @return self
     */
    public function table(string $tableName): self
    {
        $this->checkPart(Table::TYPE);

        $part = $this->parts[Table::TYPE];
        $part->add($tableName);

        return $this;
    }

    /**
     * @param mixed $select
     *
     * @return self
     */
    public function select($select = null): self
    {
        $this->checkPart(Select::TYPE);

        if (empty($select)) {
            return $this;
        }

        $part    = $this->parts[Select::TYPE];
        $selects = is_array($select) ? $select : func_get_args();

        $part->add($selects, false);
        return $this;
    }

    /**
     * @param mixed $select
     *
     * @return self
     */
    public function addSelect($select = null): self
    {
        $this->checkPart(Select::TYPE);

        if (empty($select)) {
            return $this;
        }

        $part    = $this->parts[Select::TYPE];
        $selects = is_array($select) ? $select : func_get_args();

        $part->add($selects, true);
        return $this;
    }

    /**
     * @param mixed  $x
     * @param string $operator
     * @param mixed  $y
     *
     * @return self
     */
    public function where($x, string $operator = null, $y = null): self
    {
        $this->checkPart(Where::TYPE);

        $part = $this->parts[Where::TYPE];
        $part->add([$x, $operator, $y]);
        return $this;
    }

    /**
     * @param mixed  $x
     * @param string $operator
     * @param mixed  $y
     *
     * @return self
     */
    public function andWhere($x, string $operator = null, $y = null): self
    {
        $this->checkPart(Where::TYPE);

        $part = $this->parts[Where::TYPE];
        $part->add([$x, $operator, $y, CompositeExpression::TYPE_AND], true);
        return $this;
    }

    /**
     * @param mixed  $x
     * @param string $operator
     * @param mixed  $y
     *
     * @return self
     */
    public function orWhere($x, string $operator = null, $y = null): self
    {
        $this->checkPart(Where::TYPE);

        $part = $this->parts[Where::TYPE];
        $part->add([$x, $operator, $y, CompositeExpression::TYPE_OR], true);
        return $this;
    }

    /**
     * @param string $sort
     * @param string $order
     *
     * @return self
     */
    public function orderBy(string $sort, string $order = null): self
    {
        $this->checkPart(Order::TYPE);

        $part = $this->parts[Order::TYPE];
        $part->add([$sort, $order], false);
        return $this;
    }

    /**
     * @param string $sort
     * @param string $order
     *
     * @return self
     */
    public function addOrderBy(string $sort, string $order = null): self
    {
        $this->checkPart(Order::TYPE);

        $part = $this->parts[Order::TYPE];
        $part->add([$sort, $order], true);
        return $this;
    }

    /**
     * @param int      $limit
     * @param int|null $offset
     *
     * @return self
     */
    public function limit(int $limit, int $offset = null): self
    {
        $this->checkPart(Limit::TYPE);

        $this->parts[Limit::TYPE]->add([$limit, $offset]);
        return $this;
    }

    /**
     * @return string
     */
    public function toString()
    {
        $this->checkPart(Select::TYPE);
        $this->checkPart(Table::TYPE);

        $query = $this->parts[Select::TYPE]->toString() . ' FROM ' . $this->parts[Table::TYPE]->toString()
            . (isset($this->parts[Where::TYPE]) ? ' ' . $this->parts[Where::TYPE]->toString() : '')
            . (isset($this->parts[Order::TYPE]) ? ' ' . $this->parts[Order::TYPE]->toString() : '')
            . (isset($this->parts[Limit::TYPE]) ? ' ' . $this->parts[Limit::TYPE]->toString() : '');

        return trim($query);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        try {
            return $this->toString();
        } catch (Throwable $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @param string $type
     */
    protected function checkPart(string $type)
    {
        if (!isset($this->parts[$type])) {
            throw new \LogicException(sprintf('The SQL part "%s" is not set or not allowed', $type));
        }
    }
}