<?php

namespace QueryBuilder\Expression;

/**
 * Class CompositeExpression
 *
 * @package QueryBuilder\Expression
 */
class CompositeExpression implements \Countable
{
    /**
     * Constants that represents an AND|OR composite expression.
     */
    const TYPE_AND = 'AND';
    const TYPE_OR  = 'OR';

    /**
     * @var string
     */
    private $type = self::TYPE_AND;

    /**
     * @var array
     */
    private $parts = [];

    /**
     * CompositeExpression constructor.
     *
     * @param string $type
     * @param array  $parts
     */
    public function __construct(string $type, array $parts = [])
    {
        $type = strtoupper($type);
        if ($this->isTypeAllowed($type)) {
            $this->type = $type;
        }

        $this->addMultiple($parts);
    }

    /**
     * @param array $parts
     *
     * @return self
     */
    public function addMultiple(array $parts): self
    {
        foreach ($parts as $part) {
            $this->add($part);
        }

        return $this;
    }

    /**
     * @param mixed $part
     *
     * @return self
     */
    public function add($part): self
    {
        if (!empty($part) || ($part instanceof self && $part->count() > 0)) {
            $this->parts[] = $part;
        }

        return $this;
    }

    /**
     * @return integer
     */
    public function count(): int
    {
        return count($this->parts);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if (count($this->parts) === 1) {
            return (string)$this->parts[0];
        }

        return '(' . implode(') ' . $this->type . ' (', $this->parts) . ')';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    protected function isTypeAllowed(string $type): bool
    {
        static $allowedTypes = [
            self::TYPE_AND,
            self::TYPE_OR,
        ];

        return in_array($type, $allowedTypes);
    }
}
