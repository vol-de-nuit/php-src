--TEST--
Testing closure() functionality
--FILE--
<?php

function bar($param1)
{
	return $param1;
}


$closure = function($param1) {
	return $param1;
};

function test($fn)
{
	static $count = 0;
	$input = "foo".$count;
	$count++;

	$output = $fn($input);
	return $input === $output;
}

class Foo
{
	public static function publicStaticFunction($param1)
	{
		return $param1;
	}
	
	private static function privateStaticFunction($param1)
	{
		return $param1;
	}

	protected static function protectedStaticFunction($param1)
	{
		return $param1;
	}

	private function privateInstanceFunc($param1)
	{
		return $param1;
	}
	
	protected function protectedInstanceFunc($param1)
	{
		return $param1;
	}
	
	
	public function publicInstanceFunc($param1)
	{
		return $param1;
	}
	
	public function closePrivateStatic()
	{
		return closure([__CLASS__, 'privateStaticFunction']);
	}
	
	public function bar($param1)
	{
		echo "this is bar\n";
	}

	public function getCallable()
	{
		return closure([$this, 'publicInstanceFunc']);
	}

	public function getSelfPublicInstance()
	{
		return closure([$this, 'publicInstanceFunc']);
	}

	public function getSelfColonPublicInstanceMethod()
	{
		return closure('self::publicInstanceFunc');
	}
}



class SubFoo extends Foo {
	
	public function closePrivateStaticInvalid()
	{
		return closure([__CLASS__, 'privateStaticFunction']);
	}
	
	
	public function closePrivateInvalid()
	{
		return closure([$this, 'privateInstanceFunc']);
	}
	
	public function closeProtectdStaticMethod()
	{
		return closure([__CLASS__, 'protectedStaticFunction']);
	}
	
	public function closeProtectedValid()
	{
		return closure([$this, 'protectedInstanceFunc']);
	}

	public function getParentPublicInstanceMethod()
	{
		return closure('parent::publicInstanceFunc');
	}
	
	public function getSelfColonParentPublicInstanceMethod()
	{
		return closure('self::publicInstanceFunc');
	}
	
	
	public function getSelfColonParentProtectedInstanceMethod()
	{
		return closure('self::protectedInstanceFunc');
	}

	public function getSelfColonParentPrivateInstanceMethod()
	{
		return closure('self::privateInstanceFunc');
	}
}


class MagicCall
{
	public function __call($name, $arguments)
	{

	}
	
	public static function __callStatic($name, $arguments)
	{
	}
}



class PublicInvokable
{
	public function __invoke($param1)
	{
		return $param1;
	}
}


function functionAccessProtected()
{
	$foo = new Foo;

	return closure([$foo, 'protectedStaticFunction']);
}

function functionAccessPrivate()
{
	$foo = new Foo;

	return closure([$foo, 'privateStaticFunction']);
}


function functionAccessMethodDoesntExist()
{
	$foo = new Foo;

	return closure([$foo, 'thisDoesNotExist']);
}

$successTests = [
	[
		'Access public static function',
		['Foo', 'publicStaticFunction']
	],
	[
		'Access public static function with different case',
		['fOo', 'publicStaticfUNCTION']
	],
	[   
		'Access public static function with colon scheme',
		'Foo::publicStaticFunction'
	],
	[
		'Access public instance method of object',
		[new Foo, 'publicInstanceFunc']
	],
	[
		'Access public instance method of parent object through parent:: ',
		[new Foo, 'publicInstanceFunc']
	],

	[
		'Function that exists',
		'bar'
	],
	[
		'Function that exists with different spelling',
		'BAR'
	],
	[
		'Closure is already a closure',
		$closure
	],
	[   
		'Class with public invokable',
		new PublicInvokable
	],
];

$exceptionTests = [
	[   
		'Cannot access privateInstance method statically ',
		['Foo', 'privateInstanceFunc'],
	],
	[   
		'Cannot access privateInstance method statically with colon scheme',
		'Foo::privateInstanceFunc',
	],
	[   
		'Cannot access privateInstance method',
		[new Foo, 'privateInstanceFunc'],
	],
	[   
		'SubClass cannot access private instance method',
		[new SubFoo, 'privateInstanceFunc'],
	],
	[   
		'Cannot access private static function of instance',
		[ new Foo, 'privateStaticFunction'],
	],
	[   
		'Cannot access private static method statically',
		['Foo', 'privateStaticFunction'],
	],
	[   
		'Cannot access private static method statically with colon scheme',
		'Foo::privateStaticFunction',
	],
	[   
		'Non-existent method should fail',
		'Foo::nonExistentFunction',
	],
	[   
		'Non-existent class should fail',
		['NonExistentClass', 'foo'],
	],
	[   
		'Non-existent function should fail',
		'thisDoesNotExist',
	],
	[
		'Instance with magic call method should fail',
		[new MagicCall, 'anything']
	],
	[
		'Class with static magic callStatic method should fail',
		['MagicCall', 'anything']
	],
];


$closureSuccessTests = [
	[
		"Instance return private method as callable",
		function () {
			$foo = new Foo;
			return $foo->closePrivateStatic();
		},
	],
	[
		'Instance return private static method',
		function () {
			$subFoo = new SubFoo;
			return $subFoo->closeProtectdStaticMethod();
		}
	],
	[
		'Subclass closure over parent class protected method',
		function () {
			$subFoo = new SubFoo;
			return $subFoo->closeProtectedValid();
		}
	],
	[
		'Subclass closure over parent class static protected method',
		function () {
			$subFoo = new SubFoo;
			return $subFoo->closeProtectdStaticMethod();
		}
	],
	[
		'Access public instance method of parent object through "parent::" ',
		function () {
			$subFoo = new SubFoo;
			return $subFoo->getParentPublicInstanceMethod();
		}
	],

	[
		'Access public instance method of self object through "self::" ',
		function () {
			$foo = new Foo;
			return $foo->getSelfColonPublicInstanceMethod();
		}
	],
	[
		'Access public instance method of parent object through "self::" to parent method',
		function () {
			$foo = new SubFoo;
			return $foo->getSelfColonParentPublicInstanceMethod();
		}
	],
	[
		'Access proteced instance method of parent object through "self::" to parent method',
		function () {
			$foo = new SubFoo;
			return $foo->getSelfColonParentProtectedInstanceMethod();
		}
	],
];


$closureFailureTests = [
	[
		'Subclass cannot closure over parant private instance method',
		function () {
			$subFoo = new SubFoo;
			return $subFoo->closePrivateInvalid();
		}
	],
	[
		'Subclass cannot closure over parant private static method',
		function () {
			$subFoo = new SubFoo;
			return $subFoo->closePrivateStaticInvalid();
		},
	],
	[
		'Function scope cannot closure over protected instance method',
		'functionAccessProtected',
	],
	[
		'Function scope cannot closure over private instance method',
		'functionAccessPrivate'
	],
		[
		'Access private instance method of parent object through "self::" to parent method',
		function () {
			$foo = new SubFoo;
			return $foo->getSelfColonParentPrivateInstanceMethod();
		}
	],
];

$count = 0;
foreach ($successTests as $test) {
	list($description, $callable) = $test;
	try {
		echo $description."\n";
		$fn = closure($callable);

		if (!test($fn)) {
			echo "Test '$description' failed to return expected value.\n";
		}
	}
	catch (\Throwable $e) {
		echo "Test '$description' ".var_export($callable, true)." failed with exception ".$e->getMessage()."\n";
	}
	$count++;
}


foreach ($exceptionTests as $test) {
	list($description, $callable) = $test;
	try {
		echo $description."\n";
		$fn = closure($test);
		echo "Test '$description' failed to fail and return was : ".var_export($test, true)."\n";
	}
	catch (\ReflectionException $le) {
		//This is the expected outcome.
	}
	catch (\Throwable $t) {
		echo "Test '$description' Wrong error type thrown: ".get_class($t)." : ".$e->getMessage()."\n";
	}
}

foreach ($closureSuccessTests as $test) {
	list($description, $closureTest) = $test;
	try {
		echo $description."\n";
		$fn = $closureTest();
		if ($fn == null) {
			echo "Test '$description' returned null.\n";
			continue;
		}
		
		if (!test($fn)) {
			echo "Test '$description' failed to return expected value.\n";
		}
	}
	catch (\Throwable $t) {
		echo "Test '$description' Wrong error type thrown: ".get_class($t)." : ".$t->getMessage()."\n";
	}
}


foreach ($closureFailureTests as $test) {
	list($description, $closureFailureTest) = $test;
	try {
		echo $description."\n";
		$fn = $closureFailureTest();
		echo "Test '$description' failed to fail and return was : ".var_export($test, true)."\n";
	}
	catch (\ReflectionException $e) {
		//this is the expected behaviour.
	}
	catch (\Throwable $t) {
		echo "Test '$description' Wrong error type thrown: ".get_class($t)." : ".$e->getMessage()."\n";
	}
}

echo "OK\n";


?>
===DONE===
--EXPECT--
Access public static function
Access public static function with different case
Access public static function with colon scheme
Access public instance method of object
Access public instance method of parent object through parent:: 
Function that exists
Function that exists with different spelling
Closure is already a closure
Class with public invokable
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
Instance with magic call method should fail
Class with static magic callStatic method should fail
Instance return private method as callable
Instance return private static method
Subclass closure over parent class protected method
Subclass closure over parent class static protected method
Access public instance method of parent object through "parent::" 
Access public instance method of self object through "self::" 
Access public instance method of parent object through "self::" to parent method
Access proteced instance method of parent object through "self::" to parent method
Subclass cannot closure over parant private instance method
Subclass cannot closure over parant private static method
Function scope cannot closure over protected instance method
Function scope cannot closure over private instance method
Access private instance method of parent object through "self::" to parent method
OK
===DONE===
