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
		$info = ['__call'];
		$info[] = $name;
		$info = array_merge($info, $arguments);
		return implode(',', $info);
	}

	public static function __callStatic($name, $arguments)
	{
		$info = ['__callStatic'];
		$info[] = $name;
		$info = array_merge($info, $arguments);
		return implode(',', $info);
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

?>