<?php

/*
 * This file is part of the Silence package.
 *
 * (c) Andrew Gebrich <an_gebrich@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE file that was distributed with this
 * source code.
 */

declare(strict_types=1);

namespace Silence\Collection;

use ArrayAccess;
use ArrayIterator;
use Closure;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * A collection that allows to work with data structures as generics.
 *
 * @template TKey of array-key
 * @template TValue
 * @implements ArrayAccess<TKey, TValue>
 * @implements IteratorAggregate<TKey, TValue>
 * @phpstan-consistent-constructor
 */
class BaseCollection implements IteratorAggregate, Countable, ArrayAccess
{
    /**
     * @var array<TKey, TValue>
     */
    private array $entities = [];

    /**
     * @param array<TKey, TValue>|BaseCollection<TKey, TValue> $entities
     */
    public function __construct(array|BaseCollection $entities = [])
    {
        if ($entities instanceof self) {
            $this->merge($entities);
        } else {
            foreach ($entities as $pos => $entity) {
                $this->set($pos, $entity);
            }
        }
    }

    /**
     * @return Traversable<int|string, TValue>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->entities);
    }

    /**
     * @param int|string $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->has($offset);
    }

    /**
     * @param int|string $offset
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    /**
     * @param int|string $offset
     * @param TValue $value
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->set($offset, $value);
    }

    /**
     * @param int|string $offset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        $this->remove($offset);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->entities);
    }

    /**
     * @param int|string $key
     * @param TValue $value
     * @return static
     */
    public function set(int|string $key, mixed $value): static
    {
        $this->entities[$key] = $value;
        return $this;
    }

    /**
     * @param TValue $value
     * @return $this
     */
    public function append(mixed $value): static
    {
        $this->entities[] = $value;
        return $this;
    }

    /**
     * @param int|string $key
     * @return static
     */
    public function remove(int|string $key): static
    {
        unset($this->entities[$key]);
        return $this;
    }

    /**
     * @param string|int $key
     * @return bool
     */
    public function has(string|int $key): bool
    {
        return isset($this->entities[$key]);
    }

    /**
     * @param string|int $key
     * @param mixed $default
     * @return TValue
     */
    public function get(string|int $key, mixed $default = null): mixed
    {
        return $this->entities[$key] ?? $default;
    }

    /**
     * @param Closure $closure
     * @return static
     */
    public function each(Closure $closure): static
    {
        foreach ($this as $item) {
            $closure($item);
        }

        return $this;
    }

    /**
     * @param Closure(TValue):mixed $mapper
     * @return static
     */
    public function map(Closure $mapper): static
    {
        $clone = clone $this;
        $clone->entities = array_map($mapper, $clone->entities);

        return $clone;
    }

    /**
     * @param BaseCollection<TKey, TValue> $collection
     * @return self<TKey, TValue>
     */
    public function merge(BaseCollection $collection): self
    {
        /** @var array<TKey, TValue> $result */
        $result = array_merge_recursive($this->entities, $collection->entities);
        $this->entities = $result;
        return $this;
    }

    /**
     * @param array<int|string, TValue> $array
     * @return self<TKey, TValue>
     */
    public function mergeArray(array $array): self
    {
        /** @var array<TKey, TValue> $result */
        $result = array_merge_recursive($this->entities, $array);
        $this->entities = $result;
        return $this;
    }

    /**
     * @return array<int|string, TValue>
     */
    public function getArrayCopy(): array
    {
        return $this->entities;
    }
}
