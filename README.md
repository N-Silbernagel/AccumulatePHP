![Tests](https://github.com/N-Silbernagel/AccumulatePHP/actions/workflows/test.yml/badge.svg)

Development happens at AccumulatePHP-src

# AccumulatePHP
A PHP collections library, inspired by java collections framework.

## What is this library for
Every had to track down a bug just to find out that that one array_unique call turned your list array into an assoc array? AccumulatePHP solves those issues by distinguishing between Maps (assoc array) and Series (list array).  

## Static Analysis
AccumulatePHP provides first class support for static analysis through PHPStan level 9.

## Structure
### Accumulation
The Accumulation interface should be used to typehint against when only a basic collection (Accumulation) of items is needed. It keeps the door open for switching out implementations.

### Series
Interface for an accumulation with guaranteed order.

### MutableSeries
Interface for a series which can be modified.

### MutableArraySeries
A simple MutableSeries implementation using an array for storing values internally.

### Map
A key-value mapping.

### HashMap
A Hashtable-like map implementation.

```mermaid
classDiagram
class Countable {
    <<interface>>
    count()
}

class Traversable {
    <<interface>>
}

class Accumulation {
  <<interface>>
  isEmpty()
  toArray()
}
Accumulation <-- Traversable
Accumulation <-- Countable

class SequencedAccumulation {
  <<interface>>
}
SequencedAccumulation <-- Accumulation

class ReadonlySeries {
    <<interface>>
    map(mapConsumer)
    get(index)
    filter(filterConsumer)
    containsLoose(element)
    contains(element)
    find(findConsumer)
    findIndex(findConsumer)
}
ReadonlySeries <-- SequencedAccumulation
class Series {
    <<interface>>
    add(item)
    remove(index)
}
Series <-- ReadonlySeries

class ReadonlyMap {
    <<interface>>
    get(key)
    values()
    toAssoc()
}
ReadonlyMap <-- Accumulation
class SequencedReadonlyMap {
    <<interface>>
}
SequencedReadonlyMap <-- ReadonlyMap
SequencedReadonlyMap <-- SequencedAccumulation
class Map {
    <<interface>>
    put(key, value)
    remove(key)
}
Map <-- ReadonlyMap
class SequencedMap {
    <<interface>>
}
SequencedMap <-- SequencedReadonlyMap
SequencedMap <-- Map

class ReadonlySet {
    <<interface>>
    contains(element)
}
ReadonlySet <-- Accumulation
class Set {
    <<interface>>
    add(element)
    remove(element)
}
Set <-- ReadonlySet
```
