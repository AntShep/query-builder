<?php

namespace QueryBuilder\Part;

use QueryBuilder\Expression\CompositeExpression;
use QueryBuilder\Expression\ExpressionBuilder;

/**
 * Class Where
 *
 * @package QueryBuilder\Part
 */
class Where implements PartInterface
{
    const TYPE = 'where';

    /**
     * @var CompositeExpression
     */
    protected $where;

    /**
     * @param mixed $sqlPart
     * @param bool  $append
     *
     * @return PartInterface
     */
    public function add($sqlPart, bool $append = false): PartInterface
    {
        if (!is_array($sqlPart) || empty($sqlPart)) {
            return $this;
        }

        $x        = $sqlPart[0] ?? null;
        $operator = $sqlPart[1] ?? null;
        $y        = $sqlPart[2] ?? null;
        $type     = $sqlPart[3] ?? null;

        $part = $this->assemblePart($x, $operator, $y);
        if (!$append) {
            $this->where = new CompositeExpression(CompositeExpression::TYPE_AND, [$part]);
            return $this;
        }

        if ($type === CompositeExpression::TYPE_AND) {
            $this->addAnd($part);
        }

        if ($type === CompositeExpression::TYPE_OR) {
            $this->addOr($part);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        if (empty($this->where)) {
            return '';
        }

        return 'WHERE ' . (string)$this->where;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }

    /**
     * @param mixed $x
     * @param mixed $operator
     * @param mixed $y
     *
     * @return string
     */
    protected function assemblePart($x, $operator = null, $y = null): string
    {
        if ($x instanceof CompositeExpression && !isset($operator) && !isset($y)) {
            return (string)$x;
        }

        return ExpressionBuilder::comparison($x, $operator, $y);
    }

    /**
     * @param string $part
     *
     * @return PartInterface
     */
    protected function addAnd(string $part): PartInterface
    {
        $where = $this->where;
        if ($where instanceof CompositeExpression && $where->getType() === CompositeExpression::TYPE_AND) {
            $where->add($part);
        } else {
            $parts = isset($where) ? [$where, $part] : [$part];
            $where = new CompositeExpression(CompositeExpression::TYPE_AND, $parts);
        }

        $this->where = $where;
        return $this;
    }

    /**
     * @param string $part
     *
     * @return PartInterface
     */
    protected function addOr(string $part): PartInterface
    {
        $where = $this->where;
        if ($where instanceof CompositeExpression && $where->getType() === CompositeExpression::TYPE_OR) {
            $where->add($part);
        } else {
            $parts = isset($where) ? [$where, $part] : [$part];
            $where = new CompositeExpression(CompositeExpression::TYPE_OR, $parts);
        }

        $this->where = $where;
        return $this;
    }
}