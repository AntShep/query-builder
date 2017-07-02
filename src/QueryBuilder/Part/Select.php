<?php

namespace QueryBuilder\Part;

/**
 * Class Select
 *
 * @package QueryBuilder\Part
 */
class Select implements PartInterface
{
    const TYPE = 'select';

    /**
     * @var array
     */
    protected $selects = [];

    /**
     * @param mixed $sqlPart
     * @param bool  $append
     *
     * @return PartInterface
     */
    public function add($sqlPart, bool $append = false): PartInterface
    {
        if (!is_array($sqlPart)) {
            return $this;
        }

        if (!$append) {
            $this->selects = $sqlPart;
            return $this;
        }

        foreach ($sqlPart as $select) {
            array_push($this->selects, $select);
        }

        array_unique($this->selects);

        return $this;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        $template = 'SELECT %s FROM';

        $selects = !empty($this->selects) ? implode(', ', $this->selects) : '*';
        return sprintf($template, $selects);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }
}