<?php

namespace QueryBuilder\Part;

/**
 * Class Table
 *
 * @package QueryBuilder\Part
 */
class Table implements PartInterface
{
    const TYPE = 'table';

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @param mixed $sqlPart
     * @param bool  $append
     *
     * @return PartInterface
     */
    public function add($sqlPart, bool $append = false): PartInterface
    {
        if (is_string($sqlPart)) {
            $this->tableName = $sqlPart;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        if (empty($this->tableName) || !is_string($this->tableName)) {
            throw new \RuntimeException(sprintf('The table name cannot be empty and must be a string'));
        }

        return $this->tableName;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }
}