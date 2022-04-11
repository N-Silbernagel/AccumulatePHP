WIP: this project is about 4 working hours old, so it´s a heavy work in progress.

# PHPile
A PHP Collection Library. Strongly inspired by Java Collections API.

## What is this library for
This library aims at adding compile- and runtime type safety to PHP arrays by providing Collection-API like Classes. 

## Type safety
### "Compile"time
By relying heavily on generics, this library offers excellent static analysability.

### Runtime
Collection classes can be implemented to perform checks on the items inside of them to guarantee runtime type safety or even other contracts.

## Structure
### Pile
The Pile interface is to this library what the Collection interface is to Java Collections API. It is not called Collection because Collections in the PHP world are kinda ruled by Larvel and Doctrine.

### Series
A series guarantees order.

### MutableSeries
A series which can be modified.

### MutableArraySeries
A simple MutableSeries implementation using an array for storing values internally.

### MutableStrictSeries
An abstract class to implement for guaranting runtime safeties.

### MutableIntSeries
An implmentation of MutableStrictSeries, all items will be ints during runtime.
