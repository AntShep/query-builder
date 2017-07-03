**Simple SQL Query Builder**
---
**Useful commands:**
- Install dependencies via composer: 
  ```
  Example 1: path\to\php.exe path\to\composer.phar -n install
  Example 2: composer -n install
  Example 3: C:\php\php-7.1\php.exe C:\ProgramData\ComposerSetup\bin\composer.phar -n install
  ```
 
 **How to use**:
 ```PHP
 use QueryBuilder\QueryBuilderFactory;
 
 $builder    = QueryBuilderFactory::simpleQBFactory();
 $subBuilder = QueryBuilderFactory::simpleQBFactory();
 
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
 ```