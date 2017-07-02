<?php

namespace QueryBuilder\Part;

/**
 * Interface PartInterface
 *
 * @package QueryBuilder\Part
 */
interface PartInterface
{
    /**
     * @param mixed $sqlPart
     * @param bool  $append
     *
     * @return PartInterface
     */
    public function add($sqlPart, bool $append = false): PartInterface;

    /**
     * @return string
     */
    public function toString(): string;

    /**
     * @return string
     */
    public function getType(): string;
}