<?php

namespace QueryBuilder\Expression;

use QueryBuilder\Helper\QueryBuilderHelper;

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
     * @param mixed $x
     * @param mixed $operator
     * @param mixed $y
     * @param bool  $forceEscape
     *
     * @return string
     */
    public static function comparison($x, $operator, $y, bool $forceEscape = true): string
    {
        if ($forceEscape) {
            $y = QueryBuilderHelper::escape($y);
        }

        return trim($x . ' ' . $operator . ' ' . $y);
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
        return $this->comparison($x, 'IN', sprintf('(%s)', QueryBuilderHelper::escape($y)), false);

    }

    /**
     * @param mixed       $x
     * @param mixed|array $y
     *
     * @return string
     */
    public function notIn($x, $y): string
    {
        return $this->comparison($x, 'NOT IN', sprintf('(%s)', QueryBuilderHelper::escape($y)), false);
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
        return $this->comparison(
            $x,
            'BETWEEN',
            sprintf('%s AND %s', QueryBuilderHelper::escape($min), QueryBuilderHelper::escape($max)),
            false
        );
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
        return $this->comparison(
            $x,
            'NOT BETWEEN',
            sprintf('%s AND %s', QueryBuilderHelper::escape($min), QueryBuilderHelper::escape($max)),
            false
        );
    }
}
