What about performance?
-----------------------

**Ok, so having strict types is nice and all, but how badly will it bog down my application??**

Of course since we're now using objects instead of scalar types, it does make our application heavier.

Simple performance file: `simple-performance.php`

Some runs using X instances of the objects vs X primitives yielded the following results
 [on a machine like this](machine.md):

```
TESTS
====================================================================
X=100,000 Variables
Primitive total time: 3.1471319201279E-7/var
Primitive memory: 5.73MB
ObjectTypes total time: 7.3061742240503E-6/var
ObjectTypes memory: 37.4MB
------ 
X=500,000 Variables
Primitive total time: 3.3653346207013E-7/var
Primitive memory: 13.5MB
ObjectTypes total time: 7.4994658995054E-6/var
ObjectTypes memory: 187.23MB
------ 
X=1,000,000 Variables
Primitive total time: 4.7465395054146E-7/var
Primitive memory: 26MB
ObjectTypes total time: 7.877584496483E-6/var
ObjectTypes memory: 354.44MB
```

So if you're building a high performance application, it's probably best to keep using primitives for most variables.