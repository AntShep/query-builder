<?php
namespace QueryBuilder;

use QueryBuilder\Part\Limit;
use QueryBuilder\Part\Select;
use QueryBuilder\Part\Table;


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
            Limit::class,
        ];
        foreach ($parts as $part) {
            $builder->addPart(new $part());
        }

        return $builder;
    }
}