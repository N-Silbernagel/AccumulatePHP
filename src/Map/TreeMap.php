<?php

declare(strict_types=1);

namespace AccumulatePHP\Map;

use AccumulatePHP\Comparable;
use AccumulatePHP\Comparator;
use AccumulatePHP\NoSuchElement;
use AccumulatePHP\Series\ArraySeries;
use AccumulatePHP\Series\MutableSeries;
use IteratorAggregate;
use JetBrains\PhpStorm\Pure;
use Traversable;

/**
 * Red-Black Tree implementation of a Map
 *
 * @template TKey
 * @template TValue
 * @implements SequencedMutableMap<TKey, TValue>
 * @implements IteratorAggregate<int, Entry<TKey, TValue>>
 */
final class TreeMap implements SequencedMutableMap, IteratorAggregate
{
    public const RED = false;
    public const BLACK = true;

    /**
     * @var TreeMapEntry<TKey, TValue>|null
     */
    private ?TreeMapEntry $root = null;
    private int $size = 0;

    /**
     * @param Comparator<TKey>|null $comparator
     */
    private function __construct(
        private ?Comparator $comparator = null
    )
    {
    }

    /**
     * @return self<TKey, TValue>
     */
    #[Pure]
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

    /**
     * @template TComparator
     * @param Comparator<TComparator> $comparator
     * @return self<TComparator, TValue>
     */
    #[Pure]
    public static function comparingBy(Comparator $comparator): self
    {
        return new self($comparator);
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

            $this->rebalanceAfterInsertion($newRoot);

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

        $this->rebalanceAfterInsertion($entry);

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

    /**
     * @phpstan-assert-if-true null $this->root
     * @phpstan-assert-if-false !null $this->root
     */
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
     * @return MutableSeries<TValue>
     */
    public function values(): MutableSeries
    {
        /** @var MutableSeries<TValue> $series */
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
     * @param TreeMapEntry<TKey, TValue> $root
     * @return TreeMapEntry<TKey, TValue>
     */
    #[Pure]
    private function getRightMostNode(TreeMapEntry $root): TreeMapEntry
    {
        $current = $root;

        do {
            $previous = $current;
            $current = $current->getRight();
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

    private function compare(mixed $first, mixed $second): int
    {
        if ($this->comparator instanceof Comparator) {
            return $this->comparator->compare($first, $second);
        }

        if ($first instanceof Comparable && $second instanceof Comparable) {
            return $first->compareTo($second);
        }

        if (is_scalar($first) !== is_scalar($second)) {
            throw new IncomparableKeys($first, $second);
        }

        return $second <=> $first;
    }

    /**
     * @param TreeMapEntry<TKey, TValue> $current
     * @param bool $fromLeft
     * @return TValue|null
     */
    private function removeNode(TreeMapEntry $current, bool $fromLeft): mixed
    {
        $replacement = null;
        if ($current->getRight() !== null) {
            // make right node the new parent, attach old left node on the left of the smallest element
            // of the right subtree
            $replacement = $current->getRight();
            if ($current->getLeft() !== null) {
                $leftMostNodeOfRightSubtree = $this->getLeftMostNode($replacement);
                $leftMostNodeOfRightSubtree->setLeft($current->getLeft());
                $current->getLeft()->setParent($leftMostNodeOfRightSubtree);
            }
            $replacement->setParent($current->getParent());
        } elseif ($current->getLeft() !== null) {
            // make left node the new parent
            $replacement = $current->getLeft();
            $replacement->setParent($current->getParent());
        }

        if ($fromLeft && $current->getParent() !== null) {
            $current->getParent()->setLeft($replacement);
        } elseif (!$fromLeft && $current->getParent() !== null) {
            $current->getParent()->setRight($replacement);
        } else {
            $this->root = $replacement;
        }


        if ($replacement !== null) {
            $this->rebalanceAfterDeletion($replacement);
        }
        $this->size--;
        return $current->getValue();
    }

    /**
     * @param TreeMapEntry<TKey, TValue> $entry
     */
    private function rebalanceAfterInsertion(TreeMapEntry $entry): void
    {
        if ($this->root === null) {
            throw new TreeMapDoesNotHaveRoot();
        }

        while ($entry !== null && $entry !== $this->root && $entry->getParent()?->getColor() === TreeMap::RED) {
            $entry = $this->fixViolationAfterInsertion($entry);
        }

        $this->root->setColor(TreeMap::BLACK);
    }

    /**
     * @param TreeMapEntry<TKey, TValue>|null $entry
     */
    private function rotateRight(?TreeMapEntry $entry): void
    {
        if ($entry === null) {
            return;
        }

        $left = $entry->getLeft();
        $entry->setLeft($left?->getRight());

        $left?->getRight()?->setParent($entry);
        $left?->setParent($entry->getParent());
        if (!$entry->hasParent()) {
            $this->root = $left;
        } elseif ($entry->getParent()->getRight() === $entry) {
            $entry->getParent()->setRight($left);
        } else {
            $entry->getParent()->setLeft($left);
        }

        $left?->setRight($entry);
        $entry->setParent($left);
    }

    /**
     * @param TreeMapEntry<TKey, TValue>|null $entry
     */
    private function rotateLeft(?TreeMapEntry $entry): void
    {
        if ($entry == null) {
            return;
        }

        $right = $entry->getRight();
        $entry->setRight($right?->getLeft());

        $right?->getLeft()?->setParent($entry);
        $right?->setParent($entry->getParent());
        if (!$entry->hasParent()) {
            $this->root = $right;
        } elseif ($entry->getParent()->getLeft() === $entry) {
            $entry->getParent()->setLeft($right);
        } else {
            $entry->getParent()->setRight($right);
        }

        $right?->setLeft($entry);
        $entry->setParent($right);
    }

    /**
     * @param TreeMapEntry<TKey, TValue> $subject
     * @return TreeMapEntry<TKey, TValue>|null
     */
    private function fixViolationAfterInsertion(TreeMapEntry $subject): ?TreeMapEntry
    {
        if ($subject->getParent() === $subject->getParent()?->getParent()?->getLeft()) {
            $subject = $this->fixLineViolation($subject);
        } else {
            $subject = $this->fixTriangleViolation($subject);
        }
        return $subject;
    }

    /**
     * @param TreeMapEntry<TKey, TValue> $subject
     * @return TreeMapEntry<TKey, TValue>|null
     */
    private function fixLineViolation(TreeMapEntry $subject): ?TreeMapEntry
    {
        $uncle = $subject->getParent()?->getParent()?->getRight();
        if ($uncle?->getColor() === TreeMap::RED) {
            $subject->getParent()?->setColor(TreeMap::BLACK);
            $uncle->setColor(TreeMap::BLACK);
            $subject->getParent()?->getParent()?->setColor(TreeMap::RED);
            $subject = $subject->getParent()?->getParent();
        } else {
            if ($subject === $subject->getParent()?->getRight()) {
                $subject = $subject->getParent();
                $this->rotateLeft($subject);
            }
            $subject->getParent()?->setColor(TreeMap::BLACK);
            $subject->getParent()?->getParent()?->setColor(TreeMap::RED);
            $this->rotateRight($subject->getParent()?->getParent());
        }
        return $subject;
    }

    /**
     * @param TreeMapEntry<TKey, TValue> $subject
     * @return TreeMapEntry<TKey, TValue>|null
     */
    private function fixTriangleViolation(TreeMapEntry $subject): ?TreeMapEntry
    {
        $uncle = $subject->getParent()?->getParent()?->getLeft();
        if ($uncle?->getColor() === TreeMap::RED) {
            $subject->getParent()?->setColor(TreeMap::BLACK);
            $uncle->setColor(TreeMap::BLACK);
            $subject->getParent()?->getParent()?->setColor(TreeMap::RED);
            $subject = $subject->getParent()?->getParent();
        } else {
            if ($subject === $subject->getParent()?->getLeft()) {
                $subject = $subject->getParent();
                $this->rotateRight($subject);
            }
            $subject->getParent()?->setColor(TreeMap::BLACK);
            $subject->getParent()?->getParent()?->setColor(TreeMap::RED);
            $this->rotateLeft($subject->getParent()?->getParent());
        }
        return $subject;
    }

    /**
     * @param TreeMapEntry<TKey, TValue> $entry
     */
    private function rebalanceAfterDeletion(TreeMapEntry $entry): void
    {
        while ($entry !== $this->root && $entry?->getColor() === TreeMap::BLACK) {
            $entry = $this->fixViolationAfterDeletion($entry);
        }

        $entry?->setColor(TreeMap::BLACK);
    }

    /**
     * @param TreeMapEntry<TKey, TValue> $entry
     * @return TreeMapEntry<TKey, TValue>|null $entry
     */
    private function fixViolationAfterDeletion(TreeMapEntry $entry): ?TreeMapEntry
    {
        if ($entry === $entry->getParent()?->getLeft()) {
            $sibling = $entry->getParent()->getRight();

            if ($sibling?->getColor() === TreeMap::RED) {
                $sibling->setColor(TreeMap::BLACK);
                $entry->getParent()->setColor(TreeMap::RED);
                $this->rotateLeft($entry->getParent());
            }

            if ($sibling->getLeft()?->getColor() === TreeMap::BLACK && $sibling->getRight()?->getColor() === TreeMap::BLACK) {
                $sibling->setColor(TreeMap::RED);
                $entry = $entry->getParent();
            } else {
                if ($sibling->getRight()?->getColor() === TreeMap::BLACK) {
                    $sibling->getLeft()?->setColor(TreeMap::BLACK);
                    $sibling->setColor(TreeMap::RED);
                    $this->rotateRight($sibling);
                }
                $sibling->setColor($entry->getParent()->getColor());
                $entry->getParent()->setColor(TreeMap::BLACK);
                $sibling->getRight()?->setColor(TreeMap::BLACK);
                $this->rotateLeft($entry->getParent());
                $entry = $this->root;
            }
        } else {
            $sibling = $entry->getParent()?->getLeft();

            if ($sibling?->getColor() === TreeMap::RED) {
                $sibling->setColor(TreeMap::BLACK);
                $entry->getParent()?->setColor(TreeMap::RED);
                $this->rotateRight($entry->getParent());
            }

            if ($sibling->getRight()?->getColor() === TreeMap::BLACK && $sibling->getLeft()?->getColor() === TreeMap::BLACK) {
                $sibling->setColor(TreeMap::RED);
                $entry = $entry->getParent();
            } else {
                if ($sibling->getLeft()?->getColor() === TreeMap::BLACK) {
                    $sibling->getRight()?->setColor(TreeMap::BLACK);
                    $sibling->setColor(TreeMap::RED);
                    $this->rotateLeft($sibling);
                }
                $parentColor = $entry->getParent()?->getColor();
                if ($parentColor !== null) {
                    $sibling->setColor($parentColor);
                }
                $entry->getParent()?->setColor(TreeMap::BLACK);
                $sibling->getLeft()?->setColor(TreeMap::BLACK);
                $this->rotateRight($entry->getParent());
                $entry = $this->root;
            }
        }

        return $entry;
    }

    /**
     * @return Entry<TKey, TValue>
     */
    public function first(): Entry
    {
        if ($this->isEmpty()) {
            throw new NoSuchElement;
        }
        return $this->getLeftMostNode($this->root)->getEntry();
    }

    /**
     * @return Entry<TKey, TValue>
     */
    public function last(): Entry
    {
        if ($this->isEmpty()) {
            throw new NoSuchElement;
        }
        return $this->getRightMostNode($this->root)->getEntry();
    }
}
