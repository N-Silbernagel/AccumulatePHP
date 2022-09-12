![Tests](https://github.com/N-Silbernagel/AccumulatePHP/actions/workflows/test.yml/badge.svg)

# AccumulatePHP
Better collections

## What is this library for
Every had to track down a bug just to find out that that one array_unique call turned your list array into an assoc array? AccumulatePHP solves those issues by distinguishing between Maps (assoc array) and Series (list array).  

## Static Analysis
AccumulatePHP provides first class support for static analysis by using generics constantly being checked at PHPStan level 9.

## Structure
### Pile
The Pile interface should be used to typehint against when only a basic collection (pile) of items is needed. It keeps the door open for switching out implementations. For example from Series to Set (not available yet).

### Series
A Pile with guaranteed order.

### MutableSeries
A series which can be modified.

### MutableArraySeries
A simple MutableSeries implementation using an array for storing values internally.

### Map
A key-value mapping where keys can be strings or int and values can be mixed.

### MutableArrayMap
Map implementation with capability of adding and removing items.
