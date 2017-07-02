<?php

namespace QueryBuilder\Part;

/**
 * Class Limit
 *
 * @package QueryBuilder\Part
 */
class Limit implements PartInterface
{
    const TYPE = 'limit';

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $offset;

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

        $this->limit = (int)$sqlPart[0];
        if (isset($sqlPart[1])) {
            $this->offset = (int)$sqlPart[1];
        }
        return $this;

    }

    /**
     * @return string
     */
    public function toString(): string
    {
        if (isset($this->offset) && $this->offset < 0) {
            throw new \RuntimeException(sprintf('LIMIT argument offset=%d is not valid', $this->offset));
        }

        $query = '';
        if (isset($this->limit)) {
            $query .= 'LIMIT ' . $this->limit;
        }

        if (isset($this->offset)) {
            $query .= ' OFFSET ' . $this->offset;
        }

        return $query;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }
}