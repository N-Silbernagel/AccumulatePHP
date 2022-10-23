<?php

declare(strict_types=1);

namespace AccumulatePHP\Map;

use AccumulatePHP\Series\ArraySeries;
use AccumulatePHP\Series\Series;
use IteratorAggregate;
use JetBrains\PhpStorm\Pure;
use Traversable;

/**
 * @template TKey
 * @template TValue
 * @implements Map<TKey, TValue>
 * @implements IteratorAggregate<int, Entry<TKey, TValue>>
 */
final class TreeMap implements Map, IteratorAggregate
{
    public const RED = false;
    public const BLACK = true;

    /**
     * @var TreeMapEntry<TKey, TValue>|null
     */
    private ?TreeMapEntry $root = null;
    private int $size = 0;

    private function __construct()
    {
    }

    /**
     * @return self<TKey, TValue>
     */
    public static function new(): self
    {
        return new self();
    }

    /**
     * @param Entry<TKey, TValue>[] $array
     * @return self<TKey, TValue>
     */
    public static function fromArray(array $array): self
    {
        $map = self::new();

        foreach ($array as $item) {
            $map->put($item->getKey(), $item->getValue());
        }

        return $map;
    }

    /**
     * @param Entry<TKey, TValue> ...$items
     * @return self<TKey, TValue>
     */
    public static function of(...$items): self
    {
        return self::fromArray($items);
    }

    public function toArray(): array
    {
        $entries = [];

        foreach ($this as $entry) {
            $entries[] = $entry;
        }

        return $entries;
    }

    /**
     * @param TKey $key
     * @param TValue $value
     * @return null|TValue
     */
    public function put(mixed $key, mixed $value): mixed
    {
        if (is_null($this->root)) {
            /** @var TreeMapEntry<TKey, TValue> $newRoot */
            $newRoot = TreeMapEntry::of($key, $value);
            $this->root = $newRoot;
            $this->size++;
            return null;
        }

        $current = $this->root;

        do {
            $parent = $current;
            $comparisonResult = $this->compare($current->getKey(), $key);
            if ($comparisonResult === -1) {
                $current = $current->getLeft();
            } elseif ($comparisonResult === 1) {
                $current = $current->getRight();
            } else {
                $oldValue = $current->getValue();
                $current->setValue($value);
                return $oldValue;
            }
        } while ($current != null);

        /** @var TreeMapEntry<TKey, TValue> $entry */
        $entry = TreeMapEntry::of($key, $value, parent: $parent);

        if ($comparisonResult === -1) {
            $parent->setLeft($entry);
        } else {
            $parent->setRight($entry);
        }

        $this->size++;
        return null;
    }

    public function remove(mixed $key): mixed
    {
        if (is_null($this->root)) {
            return null;
        }

        $current = $this->root;
        $fromLeft = false;

        while ($current !== null) {
            $comparisonResult = $this->compare($current->getKey(), $key);

            if ($comparisonResult === 0) {
                return $this->removeNode($current, $fromLeft);
            }

            if ($comparisonResult === -1) {
                $current = $current->getLeft();
                $fromLeft = true;
            } elseif ($comparisonResult === 1) {
                $current = $current->getRight();
                $fromLeft = false;
            }
        }

        return null;
    }

    public function get(mixed $key): mixed
    {
        if (is_null($this->root)) {
            return null;
        }

        $current = $this->root;

        do {
            $comparisonResult = $this->compare($current->getKey(), $key);

            if ($comparisonResult === 0) {
                return $current->getValue();
            }

            if ($comparisonResult === -1) {
                $current = $current->getLeft();
            } elseif ($comparisonResult === 1) {
                $current = $current->getRight();
            }
        } while ($current != null);

        return null;
    }

    #[Pure]
    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function count(): int
    {
        return $this->size;
    }

    /**
     * @return Series<TValue>
     */
    public function values(): Series
    {
        /** @var Series<TValue> $series */
        $series = ArraySeries::new();

        foreach ($this as $entry) {
            $series->add($entry->getValue());
        }

        return $series;
    }

    /**
     * @template InputKey of string|int
     * @template InputValue
     * @param array<InputKey, InputValue> $assocArray
     * @return self<InputKey, InputValue>
     */
    public static function fromAssoc(array $assocArray): self
    {
        /** @var self<InputKey, InputValue> $new */
        $new = self::new();

        foreach ($assocArray as $key => $value) {
            $new->put($key, $value);
        }

        return $new;
    }

    #[Pure]
    public function toAssoc(): array
    {
        $assoc = [];

        foreach ($this as $item) {
            if (is_scalar($item->getKey())) {
                $assoc[$item->getKey()] = $item->getValue();
            }
        }

        return $assoc;
    }

    public function getIterator(): Traversable
    {
        if (is_null($this->root)) {
            return;
        }

        $current = $this->getLeftMostNode($this->root);

        while (!is_null($current)) {
            yield $current->getEntry();

            $current = $this->getNextBiggerNode($current);
        }
    }

    /**
     * @param TreeMapEntry<TKey, TValue> $root
     * @return TreeMapEntry<TKey, TValue>
     */
    #[Pure]
    private function getLeftMostNode(TreeMapEntry $root): TreeMapEntry
    {
        $current = $root;

        do {
            $previous = $current;
            $current = $current->getLeft();
        } while (!is_null($current));

        return $previous;
    }

    /**
     * @param TreeMapEntry<TKey, TValue> $current
     * @return TreeMapEntry<TKey, TValue>|null
     */
    #[Pure]
    private function getNextBiggerNode(TreeMapEntry $current): ?TreeMapEntry
    {
        // if the node has a right node, that one is the next bigger
        $right = $current->getRight();

        if (!is_null($right)) {
            return $this->getLeftMostNode($right);
        }

        // otherwise we need to go up the tree
        $parent = $current->getParent();

        if (is_null($parent)) {
            return null;
        }

        // when we are "coming from the left" (eg. the current node is the left node of its parent)
        // the parent is the next bigger
        // find the next parent where we are "coming from the left"
        while ($parent?->getRight() === $current) {
            $current = $parent;
            $parent = $parent->getParent();
        }

        // if there ist no such parent, we've had the biggest element of the tree, which is the rightmost
        if (is_null($parent)) {
            return null;
        }

        // if we found a parent were we "came from the left", that is the next bigger node
        return $parent;
    }

    private function compare(mixed $target, mixed $comparison): int
    {
        if (is_scalar($target) !== is_scalar($comparison)) {
            throw new IncomparableKeysException($target, $comparison);
        }
        return $comparison <=> $target;
    }

    /**
     * @param TreeMapEntry<TKey, TValue> $current
     * @param bool $fromLeft
     * @return TValue|null
     */
    private function removeNode(TreeMapEntry $current, bool $fromLeft): mixed
    {
        $replacingNode = null;
        if ($current->getRight() !== null) {
            // make right node the new parent, attach old left node on the left of the smallest element
            // of the right subtree
            $replacingNode = $current->getRight();
            if ($current->getLeft() !== null) {
                $leftMostNodeOfRightSubtree = $this->getLeftMostNode($replacingNode);
                $leftMostNodeOfRightSubtree->setLeft($current->getLeft());
                $current->getLeft()->setParent($leftMostNodeOfRightSubtree);
            }
            $replacingNode->setParent($current->getParent());
        } elseif ($current->getLeft() !== null) {
            // make left node the new parent
            $replacingNode = $current->getLeft();
            $replacingNode->setParent($current->getParent());
        }

        if ($fromLeft && $current->getParent() !== null) {
            $current->getParent()->setLeft($replacingNode);
        } elseif (!$fromLeft && $current->getParent() !== null) {
            $current->getParent()->setRight($replacingNode);
        } else {
            $this->root = $replacingNode;
        }


        $this->size--;
        return $current->getValue();
    }
}
