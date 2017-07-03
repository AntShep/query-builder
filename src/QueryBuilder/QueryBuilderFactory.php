<?php

namespace QueryBuilder;

use QueryBuilder\Part\Limit;
use QueryBuilder\Part\Order;
use QueryBuilder\Part\Select;
use QueryBuilder\Part\Table;
use QueryBuilder\Part\Where;

/**
 * Class QueryBuilderFactory
 *
 * @package QueryBuilder
 */
class QueryBuilderFactory
{
    /**
     * @return QueryBuilder
     */
    public static function simpleQBFactory()
    {
        $builder = new QueryBuilder();

        $parts = [
            Select::class,
            Table::class,
            Where::class,
            Limit::class,
            Order::class,
        ];
        foreach ($parts as $part) {
            $builder->addPart(new $part());
        }

        return $builder;
    }
}