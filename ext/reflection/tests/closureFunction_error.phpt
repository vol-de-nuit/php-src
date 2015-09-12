--TEST--
Testing closure() functionality
--FILE--
<?php

include('closureFunction.inc');

echo 'Cannot access privateInstance method statically'."\n";
try {
	$fn = closure(['Foo', 'privateInstanceFunc']);
	echo "Test failed to fail and return was : ".var_export($test, true)."\n";
}
catch (\ReflectionException $le) {
	//This is the expected outcome.
}
catch (\Throwable $t) {
	echo "Wrong exception type thrown: ".get_class($t)." : ".$e->getMessage()."\n";
}


echo 'Cannot access privateInstance method statically with colon scheme'."\n";
try {
	$fn = closure('Foo::privateInstanceFunc');
	echo "Test failed to fail and return was : ".var_export($test, true)."\n";
}
catch (\ReflectionException $le) {
	//This is the expected outcome.
}
catch (\Throwable $t) {
	echo "Wrong exception type thrown: ".get_class($t)." : ".$e->getMessage()."\n";
}

echo 'Cannot access privateInstance method'."\n";
try {
	$fn = closure([new Foo, 'privateInstanceFunc']);
	echo "Test failed to fail and return was : ".var_export($test, true)."\n";
}
catch (\ReflectionException $le) {
	//This is the expected outcome.
}
catch (\Throwable $t) {
	echo "Wrong exception type thrown: ".get_class($t)." : ".$e->getMessage()."\n";
}

echo 'SubClass cannot access private instance method'."\n";
try {
	$fn = closure([new SubFoo, 'privateInstanceFunc']);
	echo "Test failed to fail, closure is : ".var_export($fn, true)."\n";
}
catch (\ReflectionException $le) {
	//This is the expected outcome.
}
catch (\Throwable $t) {
	echo "Wrong exception type thrown: ".get_class($t)." : ".$e->getMessage()."\n";
}

echo 'Cannot access private static function of instance'."\n";
try {
	$fn = closure([new Foo, 'privateStaticFunction']);
	echo "Test failed to fail, closure is : ".var_export($fn, true)."\n";
}
catch (\ReflectionException $le) {
	//This is the expected outcome.
}
catch (\Throwable $t) {
	echo "Wrong exception type thrown: ".get_class($t)." : ".$e->getMessage()."\n";
}

echo 'Cannot access private static method statically'."\n";
try {
	$fn = closure(['Foo', 'privateStaticFunction']);
	echo "Test failed to fail, closure is : ".var_export($fn, true)."\n";
}
catch (\ReflectionException $le) {
	//This is the expected outcome.
}
catch (\Throwable $t) {
	echo "Wrong exception type thrown: ".get_class($t)." : ".$e->getMessage()."\n";
}

echo 'Cannot access private static method statically with colon scheme'."\n";
try {
	$fn = closure('Foo::privateStaticFunction');
	echo "Test failed to fail, closure is : ".var_export($fn, true)."\n";
}
catch (\ReflectionException $le) {
	//This is the expected outcome.
}
catch (\Throwable $t) {
	echo "Wrong exception type thrown: ".get_class($t)." : ".$e->getMessage()."\n";
}

echo 'Non-existent method should fail'."\n";
try {
	closure('Foo::nonExistentFunction');
	echo "Test failed to fail, closure is : ".var_export($fn, true)."\n";
}
catch (\ReflectionException $le) {
	//This is the expected outcome.
}
catch (\Throwable $t) {
	echo "Wrong exception type thrown: ".get_class($t)." : ".$e->getMessage()."\n";
}

echo 'Non-existent class should fail'."\n";
try {
	$fn = closure(['NonExistentClass', 'foo']);
	echo "Test failed to fail, closure is : ".var_export($fn, true)."\n";
}
catch (\ReflectionException $le) {
	//This is the expected outcome.
}
catch (\Throwable $t) {
	echo "Wrong exception type thrown: ".get_class($t)." : ".$e->getMessage()."\n";
}

echo 'Non-existent function should fail'."\n";
try {
	$fn = closure('thisDoesNotExist');
	echo "Test failed to fail, closure is : ".var_export($fn, true)."\n";
}
catch (\ReflectionException $le) {
	//This is the expected outcome.
}
catch (\Throwable $t) {
	echo "Wrong exception type thrown: ".get_class($t)." : ".$e->getMessage()."\n";
}


// echo 'Instance with magic call method should fail'."\n";
// try {
// 	$fn = closure([new MagicCall, 'anything']);
// 	echo "Test failed to fail and return was : ".var_export($test, true)."\n";
// }
// catch (\ReflectionException $le) {
// 	//This is the expected outcome.
// }
// catch (\Throwable $t) {
// 	echo "Wrong exception type thrown: ".get_class($t)." : ".$e->getMessage()."\n";
// }


// echo 'Class with static magic callStatic method should fail'."\n";
// try {
// 	$fn = closure(['MagicCall', 'anything']);
// 	echo "Test failed to fail and return was : ".var_export($test, true)."\n";
// }
// catch (\ReflectionException $le) {
// 	//This is the expected outcome.
// }
// catch (\Throwable $t) {
// 	echo "Wrong exception type thrown: ".get_class($t)." : ".$e->getMessage()."\n";
// }


echo 'Subclass cannot closure over parent private instance method'."\n";
try {
	$subFoo = new SubFoo;
	$subFoo->closePrivateInvalid();
	echo "Test failed to fail, closure is : ".var_export($fn, true)."\n";
}
catch (\ReflectionException $le) {
	//This is the expected outcome.
}
catch (\Throwable $t) {
	echo "Wrong exception type thrown: ".get_class($t)." : ".$e->getMessage()."\n";
}

echo 'Subclass cannot closure over parant private static method'."\n";
try {
	$subFoo = new SubFoo;
	$fn = $subFoo->closePrivateStaticInvalid();
	echo "Test failed to fail, closure is : ".var_export($fn, true)."\n";
}
catch (\ReflectionException $le) {
	//This is the expected outcome.
}
catch (\Throwable $t) {
	echo "Wrong exception type thrown: ".get_class($t)." : ".$e->getMessage()."\n";
}

echo 'Function scope cannot closure over protected instance method'."\n";
try {
	$fn = functionAccessProtected();
	echo "Test failed to fail, closure is : ".var_export($fn, true)."\n";
}
catch (\ReflectionException $le) {
	//This is the expected outcome.
}
catch (\Throwable $t) {
	echo "Wrong exception type thrown: ".get_class($t)." : ".$e->getMessage()."\n";
}

echo 'Function scope cannot closure over private instance method'."\n";
try {
	$fn = functionAccessPrivate();
	echo "Test failed to fail, closure is : ".var_export($fn, true)."\n";
}
catch (\ReflectionException $le) {
	//This is the expected outcome.
}
catch (\Throwable $t) {
	echo "Wrong exception type thrown: ".get_class($t)." : ".$e->getMessage()."\n";
}

echo 'Access private instance method of parent object through "self::" to parent method'."\n";
try {
	$foo = new SubFoo;
	$foo->getSelfColonParentPrivateInstanceMethod();
	echo "Test failed to fail, closure is : ".var_export($fn, true)."\n";
}
catch (\ReflectionException $le) {
	//This is the expected outcome.
}
catch (\Throwable $t) {
	echo "Wrong exception type thrown: ".get_class($t)." : ".$e->getMessage()."\n";
}

echo "OK\n";

?>
===DONE===
--EXPECT--

Cannot access privateInstance method statically
Cannot access privateInstance method statically with colon scheme
Cannot access privateInstance method
SubClass cannot access private instance method
Cannot access private static function of instance
Cannot access private static method statically
Cannot access private static method statically with colon scheme
Non-existent method should fail
Non-existent class should fail
Non-existent function should fail
Subclass cannot closure over parent private instance method
Subclass cannot closure over parant private static method
Function scope cannot closure over protected instance method
Function scope cannot closure over private instance method
Access private instance method of parent object through "self::" to parent method
OK
===DONE===
