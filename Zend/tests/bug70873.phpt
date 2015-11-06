--TEST--
Bug #70873 (Private static property access of parent must fail)
--FILE--
<?php

class A {
    private static $x = 1;
}

class B extends A {
    function bar() {
        var_dump(self::$x);
    }
};

$a = new B;
$a->bar();

?>
--EXPECTF--

Fatal error: Uncaught Error: Cannot access private property B::$x in %s:%d
Stack trace:
#0 %s(%d): B->bar()
#1 {main}
  thrown in %s on line %d
