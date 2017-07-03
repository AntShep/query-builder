<?php

namespace QueryBuilder;

use QueryBuilder\Part\Limit;
use QueryBuilder\Part\Order;
use QueryBuilder\Part\PartInterface;
use QueryBuilder\Part\Select;
use QueryBuilder\Part\Table;
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
    private $parts = [];

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
     * @param string $tableName
     *
     * @return self
     */
    public function table(string $tableName): self
    {
        if (!isset($this->parts[Table::TYPE])) {
            $this->parts[Table::TYPE] = new Table();
        }

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

        $query = $this->parts[Select::TYPE]->toString() . ' ' . $this->parts[Table::TYPE]->toString()
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