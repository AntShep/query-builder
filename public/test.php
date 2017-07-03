<?php

$loader = include_once __DIR__ . '/../vendor/autoload.php';
$loader->setPsr4('QueryBuilder\\', __DIR__ . '/../src/QueryBuilder');

use QueryBuilder\QueryBuilderFactory;

$builder = QueryBuilderFactory::simpleQBFactory();

try {
    $builder->select('id', 'name')
            ->addSelect(['email', 'phone'])
            ->table('user')
            ->limit(20)
            ->addOrderBy('name', 'desc')
            ->orderBy('phone')
            ->addOrderBy('id');

    echo $builder;
    echo "\n";
    echo $builder;
    echo "\n";

} catch (Throwable $exception) {
    echo get_class($exception) . ' --> ' . $exception->getMessage();
}
