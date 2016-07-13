What about performance?
-----------------------

**Ok, so having strict types is nice and all, but how badly will it bog down my application??**

Of course since we're now using objects instead of scalar types, it does make our application slightly heavier on memory.

Simple benchmark file: `simple-benchmark.php`

Some benchmarking using X instances of the objects vs X primitives yielded the following results:
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

For a total of 3.2 million vars.
real	0m16.676s
user	0m16.452s
sys	0m0.204s
```

So if you're building an extremely high performance API, it's probably best to keep using primitives, although the performance gain is negligable.
While primitives are still faster and use less  memory than objects, it may still be worth having strict types... if not for syntactic  sugar the
 object type APIs provide, then mainly for making your application stricter and less prone to bugs.
