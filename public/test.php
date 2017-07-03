<?php

$loader = include_once __DIR__ . '/../vendor/autoload.php';
$loader->setPsr4('QueryBuilder\\', __DIR__ . '/../src/QueryBuilder');

use QueryBuilder\QueryBuilderFactory;

$builder    = QueryBuilderFactory::simpleQBFactory();
$subBuilder = QueryBuilderFactory::simpleQBFactory();

try {
    $subBuilder->table('user')
               ->select('id')
               ->where($subBuilder->exp()->andX($subBuilder->exp()->between('age', 10, 40)));

    $builder->table('user')
            ->select('id', 'name')
            ->where(
                $builder->exp()->orX(
                    $builder->exp()->andX(
                        $builder->exp()->eq('foo', 3),
                        $builder->exp()->eq('bar', 'hello')
                    ),
                    $builder->exp()->andX(
                        $builder->exp()->eq('foo', 1337),
                        $builder->exp()->eq('bar', 'world')
                    )
                )
            )
            ->orWhere('createDatetime', '>=', '2017-01-01')
            ->andWhere($builder->exp()->notIn('id', $subBuilder))
            ->orderBy('name')
            ->limit(10);

    echo $builder;
} catch (Throwable $exception) {
    echo get_class($exception) . ' --> ' . $exception->getMessage();
}
