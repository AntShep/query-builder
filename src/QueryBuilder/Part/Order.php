<?php

namespace QueryBuilder\Part;

/**
 * Class Order
 *
 * @package QueryBuilder\Part
 */
class Order implements PartInterface
{
    const TYPE = 'order';

    /**
     * Available orders
     */
    const ASCENDING  = 'ASC';
    const DESCENDING = 'DESC';

    /**
     * @var array
     */
    protected $order = [];

    /**
     * @var string
     */
    protected $defaultOrder = self::ASCENDING;

    /**
     * @param string $defaultOrder
     *
     * @return self
     */
    public function setDefaultDirection(string $defaultOrder): self
    {
        $defaultOrder = strtoupper($defaultOrder);
        if ($this->isOrderAllowed($defaultOrder)) {
            $this->defaultOrder = $defaultOrder;
        }
        return $this;
    }

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

        $sort  = (string)$sqlPart[0];
        $order = $sqlPart[1] ?? null;
        if ($append) {
            $this->order[$sort] = $order;
        } else {
            $this->order = [$sort => $order];
        }

        return $this;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        $parts = [];
        foreach ($this->order as $sort => $order) {
            $parts[] = sprintf(
                '%s %s', $sort,
                $this->isOrderAllowed($order) ? strtoupper($order) : $this->defaultOrder
            );
        }

        return !empty($parts) ? 'ORDER BY ' . implode(', ', $parts) : '';
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }

    /**
     * @param string|null $order
     *
     * @return bool
     */
    protected function isOrderAllowed(string $order = null)
    {
        static $allowedOrders = [
            self::ASCENDING,
            self::DESCENDING,
        ];

        return in_array(strtoupper($order), $allowedOrders);
    }
}