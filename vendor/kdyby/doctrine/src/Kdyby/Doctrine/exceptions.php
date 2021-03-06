<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Kdyby\Doctrine;

use Doctrine;



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
interface Exception
{

}



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class InvalidStateException extends \RuntimeException implements Exception
{

}



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class InvalidArgumentException extends \InvalidArgumentException implements Exception
{

}



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class NotSupportedException extends \LogicException implements Exception
{

}



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class StaticClassException extends \LogicException implements Exception
{

}



/**
 * The exception that is thrown when a requested method or operation is not implemented.
 */
class NotImplementedException extends \LogicException implements Exception
{

}



/**
 * When class is not found
 */
class MissingClassException extends \LogicException implements Exception
{

}



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class UnexpectedValueException extends \UnexpectedValueException implements Exception
{

	/**
	 * @param mixed $list
	 * @param string|object $class
	 * @param string $property
	 *
	 * @return UnexpectedValueException
	 */
	public static function invalidEventValue($list, $class, $property)
	{
		$class = is_object($class) ? get_class($class) : $class;

		return new static("Property $class::$$property must be array or NULL, " . gettype($list) . " given.");
	}



	/**
	 * @param string|object $class
	 * @param string $property
	 *
	 * @return UnexpectedValueException
	 */
	public static function notACollection($class, $property)
	{
		$class = is_object($class) ? get_class($class) : $class;

		return new static(sprintf("Class property $class::\$$property is not an instance of %s.", \Doctrine\Common\Collections\Collection::class));
	}



	/**
	 * @param string|object $class
	 * @param string $property
	 *
	 * @return UnexpectedValueException
	 */
	public static function collectionCannotBeReplaced($class, $property)
	{
		$class = is_object($class) ? get_class($class) : $class;

		return new static(sprintf(
			"Class property $class::\$$property is an instance of %s. Use add<property>() and remove<property>() methods to manipulate it or declare your own.",
			\Doctrine\Common\Collections\Collection::class
		));
	}

}



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class MemberAccessException extends \LogicException implements Exception
{

	/**
	 * @param string $type
	 * @param string|object $class
	 * @param string $property
	 *
	 * @return MemberAccessException
	 */
	public static function propertyNotWritable($type, $class, $property)
	{
		$class = is_object($class) ? get_class($class) : $class;

		return new static("Cannot write to $type property $class::\$$property.");
	}



	/**
	 * @param string|object $class
	 *
	 * @return MemberAccessException
	 */
	public static function propertyWriteWithoutName($class)
	{
		$class = is_object($class) ? get_class($class) : $class;

		return new static("Cannot write to a class '$class' property without name.");
	}



	/**
	 * @param string $type
	 * @param string|object $class
	 * @param string $property
	 *
	 * @return MemberAccessException
	 */
	public static function propertyNotReadable($type, $class, $property)
	{
		$class = is_object($class) ? get_class($class) : $class;

		return new static("Cannot read $type property $class::\$$property.");
	}



	/**
	 * @param string|object $class
	 *
	 * @return MemberAccessException
	 */
	public static function propertyReadWithoutName($class)
	{
		$class = is_object($class) ? get_class($class) : $class;

		return new static("Cannot read a class '$class' property without name.");
	}



	/**
	 * @param string|object $class
	 *
	 * @return MemberAccessException
	 */
	public static function callWithoutName($class)
	{
		$class = is_object($class) ? get_class($class) : $class;

		return new static("Call to class '$class' method without name.");
	}



	/**
	 * @param object|string $class
	 * @param string $method
	 *
	 * @return MemberAccessException
	 */
	public static function undefinedMethodCall($class, $method)
	{
		$class = is_object($class) ? get_class($class) : $class;

		return new static("Call to undefined method $class::$method().");
	}

}



/**
 * @author Filip Procházka <filip@prochazka.su>
 * @deprecated
 */
class DBALException extends \RuntimeException implements Exception
{

	/**
	 * @var string|NULL
	 */
	public $query;

	/**
	 * @var array
	 */
	public $params = [];

	/**
	 * @var \Doctrine\DBAL\Connection|NULL
	 */
	public $connection;



	/**
	 * @param \Exception|\Throwable $previous
	 * @param string|NULL $query
	 * @param array $params
	 * @param \Doctrine\DBAL\Connection|NULL $connection
	 * @param string|NULL $message
	 */
	public function __construct($previous, $query = NULL, $params = [], Doctrine\DBAL\Connection $connection = NULL, $message = NULL)
	{
		parent::__construct($message ?: $previous->getMessage(), $previous->getCode(), $previous);
		$this->query = $query;
		$this->params = $params;
		$this->connection = $connection;
	}



	/**
	 * This is just a paranoia, hopes no one actually serializes exceptions.
	 *
	 * @return array
	 */
	public function __sleep()
	{
		return ['message', 'code', 'file', 'line', 'errorInfo', 'query', 'params'];
	}

}



/**
 * @author Filip Procházka <filip@prochazka.su>
 * @deprecated
 */
class DuplicateEntryException extends DBALException
{

	/**
	 * @var array
	 */
	public $columns;



	/**
	 * @param \Exception|\Throwable $previous
	 * @param array $columns
	 * @param string $query
	 * @param array $params
	 * @param \Doctrine\DBAL\Connection $connection
	 */
	public function __construct($previous, $columns = [], $query = NULL, $params = [], Doctrine\DBAL\Connection $connection = NULL)
	{
		parent::__construct($previous, $query, $params, $connection);
		$this->columns = $columns;
	}



	/**
	 * @return array
	 */
	public function __sleep()
	{
		return array_merge(parent::__sleep(), ['columns']);
	}

}



/**
 * @author Filip Procházka <filip@prochazka.su>
 * @deprecated
 */
class EmptyValueException extends DBALException
{

	/**
	 * @var string|NULL
	 */
	public $column;



	/**
	 * @param \Exception|\Throwable $previous
	 * @param string|NULL $column
	 * @param string $query
	 * @param array $params
	 * @param \Doctrine\DBAL\Connection $connection
	 */
	public function __construct($previous, $column = NULL, $query = NULL, $params = [], Doctrine\DBAL\Connection $connection = NULL)
	{
		parent::__construct($previous, $query, $params, $connection);
		$this->column = $column;
	}



	/**
	 * @return array
	 */
	public function __sleep()
	{
		return array_merge(parent::__sleep(), ['column']);
	}

}



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class QueryException extends \RuntimeException implements Exception
{

	/**
	 * @var \Doctrine\ORM\AbstractQuery|NULL
	 */
	public $query;



	/**
	 * @param \Exception|\Throwable $previous
	 * @param \Doctrine\ORM\AbstractQuery|NULL $query
	 * @param string|NULL $message
	 */
	public function __construct($previous, Doctrine\ORM\AbstractQuery $query = NULL, $message = NULL)
	{
		parent::__construct($message ?: $previous->getMessage(), 0, $previous);
		$this->query = $query;
	}

}



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class BatchImportException extends \RuntimeException implements Exception
{

}



/**
 * @author Michael Moravec
 */
class ReadOnlyCollectionException extends NotSupportedException
{
	/**
	 * @throws ReadOnlyCollectionException
	 */
	public static function invalidAccess($what)
	{
		return new static('Could not ' . $what . ' read-only collection, write/modify operations are forbidden.');
	}
}
