![Tests](https://github.com/N-Silbernagel/AccumulatePHP-src/actions/workflows/ci.yml/badge.svg)
[![Latest Stable Version](http://poser.pugx.org/nsilbernagel/accumulatephp/v)](https://packagist.org/packages/nsilbernagel/accumulatephp)
[![Total Downloads](http://poser.pugx.org/nsilbernagel/accumulatephp/downloads)](https://packagist.org/packages/nsilbernagel/accumulatephp)
[![License](http://poser.pugx.org/nsilbernagel/accumulatephp/license)](https://packagist.org/packages/nsilbernagel/accumulatephp)
[![PHP Version Require](http://poser.pugx.org/nsilbernagel/accumulatephp/require/php)](https://packagist.org/packages/nsilbernagel/accumulatephp)

Development happens at [AccumulatePHP-src](https://github.com/N-Silbernagel/AccumulatePHP-src)

# AccumulatePHP
A PHP collections library, inspired by java collections framework.

## What is this library for
Using more refined datastructures allows for safer, often more efficient code than using arrays (list and assoc). TreeMap for example guarantees being searchable in O (log n). Furthermore, non-scalar keys can be used as keys in maps.

## Static Analysis
AccumulatePHP provides first class support for static analysis through PHPStan level 9.

## Examples
### ArraySeries
```php
// create empty list
$series = ArraySeries::new();

// create list with elements 1,2 and 3
$series = ArraySeries::of(1,2,3);

// add 4
$series->add(4);

// remove 4th element
$series->remove(3);

// filter list for event number
$evenNumbers = $series->filter(fn(int $number) => $number % 2 === 0)

// map list, multiplay all elements by 2
$multiplied = $series->map(fn(int $number) => $number*2)
```
For a complete overview of ArraySeries and the other series available, please refer to the source files under src/series

### HashMap
```php
// create empty map
$series = ArraySeries::new();

// create map with initial entries
$series = ArraySeries::of(
    Entry::of('example', 'code'),
    Entry::of('is', 'fun'),
);

// add entry
$series->put('isnt', 'it?');

// remove entry via key
$series->remove('isnt');
```
Hashmaps can use any type keys, except for resources and arrays. Classes may implement Hashable interface to determine their hash function and definition of equality. You may refer to https://www.baeldung.com/java-equals-hashcode-contracts to learn more about equals and hashcode and their contracts. 
## Structure

```mermaid
classDiagram
    direction BT
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
    Accumulation <|-- Traversable
    Accumulation <|-- Countable
    
    class SequencedAccumulation {
      <<interface>>
    }
    SequencedAccumulation <|-- Accumulation
    
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
    ReadonlySeries <|-- SequencedAccumulation
    class Series {
        <<interface>>
        add(item)
        remove(index)
    }
    Series <|-- ReadonlySeries
    
    class ReadonlyMap {
        <<interface>>
        get(key)
        values()
        toAssoc()
    }
    ReadonlyMap <|-- Accumulation
    class SequencedReadonlyMap {
        <<interface>>
    }
    SequencedReadonlyMap <|-- ReadonlyMap
    SequencedReadonlyMap <|-- SequencedAccumulation
    class Map {
        <<interface>>
        put(key, value)
        remove(key)
    }
    Map <|-- ReadonlyMap
    class SequencedMap {
        <<interface>>
    }
    SequencedMap <|-- SequencedReadonlyMap
    SequencedMap <|-- Map
    
    class ReadonlySet {
        <<interface>>
        contains(element)
    }
    ReadonlySet <|-- Accumulation
    class Set {
        <<interface>>
        add(element)
        remove(element)
    }
    Set <|-- ReadonlySet
```

### Accumulation
The base interface of this library. An accumulation (collection) of items. Iterable and Countable.

### SequencedAccumulation
An Accumulation with a defined sequence or order of elements. Which order this is is up to the implementation. It might be insertion order for some or natural order for others.

### ReadonlySeries
A SequencedAccumulation with that allows getting by index, mapping, filtering etc.

### Series
Like the ReadonlySeries but with methods for mutation.

### ArraySeries
Basic array implementation of a series

### ReadonlyMap
A readonly key-value mapping. Can be created from and converted to associative arrays, will lose any non-scalar keys during conversion. Iterable over its entry objects. It is up to the implementation what type of keys are supported. It is strongly recommended to only use the same type of key for a map (can be enforced through static analysis tools).

### Entry
An entry of a map.

### SequencedReadonlyMap
A ReadonlyMap with defined Order of keys

### Map
Like ReadonlyMap but with methods for mutation.

### SequencedMap
A Map with defined Order of keys

### HashMap
A Hashtable-like map implementation.

### TreeMap
A Red-Black Tree SequencedMap implementation. Keys are ordered by their natural order (spaceship operator) by default.

### ReadonlySet
An accumulation where every element may only exist once

### Set
Like ReadonlySap but with methods for mutation

### HashSet
Hash implementation of a Set. Uses HashMap in the background.

### StrictSet
A Set implementation using php strict comparison.
