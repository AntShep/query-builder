<?php

namespace QueryBuilder\Expression;

/**
 * Class ExpressionBuilder
 *
 * @package QueryBuilder\Expression
 */
class ExpressionBuilder
{
    const EQ  = '=';
    const NEQ = '<>';
    const LT  = '<';
    const LTE = '<=';
    const GT  = '>';
    const GTE = '>=';

    /**
     * @param mixed $x
     *
     * @return CompositeExpression
     */
    public function andX($x = null): CompositeExpression
    {
        return new CompositeExpression(CompositeExpression::TYPE_AND, func_get_args());
    }

    /**
     * @param mixed $x
     *
     * @return CompositeExpression
     */
    public function orX($x = null): CompositeExpression
    {
        return new CompositeExpression(CompositeExpression::TYPE_OR, func_get_args());
    }

    /**
     * Creates a comparison expression.
     *
     * @param mixed  $x
     * @param string $operator
     * @param mixed  $y
     *
     * @return string
     */
    public function comparison($x, string $operator, $y): string
    {
        return $x . ' ' . $operator . ' ' . $y;
    }

    /**
     * @param mixed $x
     * @param mixed $y
     *
     * @return string
     */
    public function eq($x, $y): string
    {
        return $this->comparison($x, self::EQ, $y);
    }

    /**
     * @param mixed $x
     * @param mixed $y
     *
     * @return string
     */
    public function neq($x, $y): string
    {
        return $this->comparison($x, self::NEQ, $y);
    }

    /**
     * @param mixed $x
     * @param mixed $y
     *
     * @return string
     */
    public function lt($x, $y): string
    {
        return $this->comparison($x, self::LT, $y);
    }

    /**
     * @param mixed $x
     * @param mixed $y
     *
     * @return string
     */
    public function lte($x, $y): string
    {
        return $this->comparison($x, self::LTE, $y);
    }

    /**
     * @param mixed $x
     * @param mixed $y
     *
     * @return string
     */
    public function gt($x, $y): string
    {
        return $this->comparison($x, self::GT, $y);
    }

    /**
     * @param mixed $x
     * @param mixed $y
     *
     * @return string
     */
    public function gte($x, $y): string
    {
        return $this->comparison($x, self::GTE, $y);
    }

    /**
     * @param mixed $x
     *
     * @return string
     */
    public function isNull($x): string
    {
        return $x . ' IS NULL';
    }

    /**
     * @param mixed $x
     *
     * @return string
     */
    public function isNotNull($x): string
    {
        return $x . ' IS NOT NULL';
    }

    /**
     * @param mixed       $x
     * @param mixed|array $y
     *
     * @return string
     */
    public function in($x, $y): string
    {
        return $this->comparison($x, 'IN', sprintf('(%s)', implode(', ', (array)$y)));

    }

    /**
     * @param mixed       $x
     * @param mixed|array $y
     *
     * @return string
     */
    public function notIn($x, $y): string
    {
        return $this->comparison($x, 'NOT IN', sprintf('(%s)', implode(', ', (array)$y)));
    }

    /**
     * @param mixed $x
     * @param mixed $min
     * @param mixed $max
     *
     * @return string
     */
    public function between($x, $min, $max): string
    {
        return $this->comparison($x, 'BETWEEN', sprintf('%s AND %s', $min, $max));
    }

    /**
     * @param mixed $x
     * @param mixed $min
     * @param mixed $max
     *
     * @return string
     */
    public function notBetween($x, $min, $max): string
    {
        return $this->comparison($x, 'NOT BETWEEN', sprintf('%s AND %s', $min, $max));
    }
}
