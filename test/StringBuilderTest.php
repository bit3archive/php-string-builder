<?php

/**
 * StringBuilder for PHP
 *
 * PHP Version 5.3
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @license    MIT
 */

/**
 * StringBuilder Tests
 */
class StringBuilderTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Initial string for create new instance of StringBuilder.
	 * The § sign differ in UTF-8 and ISO-8859-1 and is used to check encoding manipulation.
	 *
	 * @var string
	 */
	protected $initialString = 'Hello § World!';

	/**
	 * @var StringBuilder
	 */
	protected $stringBuilder;

	public function setUp()
	{
		$this->stringBuilder = new StringBuilder($this->initialString);
	}

	public function testOutOfBoundsExceptionCharAtBeginning()
	{
		$this->setExpectedException('OutOfBoundsException');
		$this->stringBuilder->charAt(-1);
	}

	public function testOutOfBoundsExceptionCharAtEnding()
	{
		$this->setExpectedException('OutOfBoundsException');
		$this->stringBuilder->charAt(14);
	}

	public function testOutOfBoundsExceptionIndexOfBeginning()
	{
		$this->setExpectedException('OutOfBoundsException');
		$this->stringBuilder->indexOf('Hello', -1);
	}

	public function testOutOfBoundsExceptionIndexOfEnding()
	{
		$this->setExpectedException('OutOfBoundsException');
		$this->stringBuilder->indexOf('Hello', 14);
	}

	public function testOutOfBoundsExceptionLastIndexOfBeginning()
	{
		$this->setExpectedException('OutOfBoundsException');
		$this->stringBuilder->lastIndexOf('Hello', -1);
	}

	public function testOutOfBoundsExceptionLastIndexOfEnding()
	{
		$this->setExpectedException('OutOfBoundsException');
		$this->stringBuilder->lastIndexOf('Hello', 14);
	}

	public function testOutOfBoundsExceptionSubstringStartBeginning()
	{
		$this->setExpectedException('OutOfBoundsException');
		$this->stringBuilder->substring(-1);
	}

	public function testOutOfBoundsExceptionSubstringStartEnding()
	{
		$this->setExpectedException('OutOfBoundsException');
		$this->stringBuilder->substring(14);
	}

	public function testOutOfBoundsExceptionSubstringEndBeginning()
	{
		$this->setExpectedException('OutOfBoundsException');
		$this->stringBuilder->substring(0, -1);
	}

	public function testOutOfBoundsExceptionSubstringEndEnding()
	{
		$this->setExpectedException('OutOfBoundsException');
		$this->stringBuilder->substring(0, 14);
	}

	public function testOutOfBoundsExceptionSubstring()
	{
		$this->setExpectedException('OutOfBoundsException');
		$this->stringBuilder->substring(-1, 14);
	}

	public function testOutOfBoundsExceptionInsertBeginning()
	{
		$this->setExpectedException('OutOfBoundsException');
		$this->stringBuilder->insert(-1, 'Hello');
	}

	public function testOutOfBoundsExceptionInsertEnding()
	{
		$this->setExpectedException('OutOfBoundsException');
		$this->stringBuilder->insert(14, 'Hello');
	}

	public function testOutOfBoundsExceptionReplaceStartBeginning()
	{
		$this->setExpectedException('OutOfBoundsException');
		$this->stringBuilder->replace(-1, 0, 'Hello');
	}

	public function testOutOfBoundsExceptionReplaceStartEnding()
	{
		$this->setExpectedException('OutOfBoundsException');
		$this->stringBuilder->replace(14, 0, 'Hello');
	}

	public function testOutOfBoundsExceptionReplaceEndBeginning()
	{
		$this->setExpectedException('OutOfBoundsException');
		$this->stringBuilder->replace(0, -1, 'Hello');
	}

	public function testOutOfBoundsExceptionReplaceEndEnding()
	{
		$this->setExpectedException('OutOfBoundsException');
		$this->stringBuilder->replace(0, 14, 'Hello');
	}

	public function testOutOfBoundsExceptionReplace()
	{
		$this->setExpectedException('OutOfBoundsException');
		$this->stringBuilder->replace(-1, 14, 'Hello');
	}

	public function testOutOfBoundsExceptionDeleteStartBeginning()
	{
		$this->setExpectedException('OutOfBoundsException');
		$this->stringBuilder->delete(-1, 6);
	}

	public function testOutOfBoundsExceptionDeleteStartEnding()
	{
		$this->setExpectedException('OutOfBoundsException');
		$this->stringBuilder->delete(14, 6);
	}

	public function testOutOfBoundsExceptionDeleteEndBeginning()
	{
		$this->setExpectedException('OutOfBoundsException');
		$this->stringBuilder->delete(6, -1);
	}

	public function testOutOfBoundsExceptionDeleteEndEnding()
	{
		$this->setExpectedException('OutOfBoundsException');
		$this->stringBuilder->delete(6, 14);
	}

	public function testOutOfBoundsExceptionDelete()
	{
		$this->setExpectedException('OutOfBoundsException');
		$this->stringBuilder->delete(-1, 14);
	}

	public function testOutOfBoundsExceptionDeleteCharAtBeginning()
	{
		$this->setExpectedException('OutOfBoundsException');
		$this->stringBuilder->deleteCharAt(-1);
	}

	public function testOutOfBoundsExceptionDeleteCharAtEnding()
	{
		$this->setExpectedException('OutOfBoundsException');
		$this->stringBuilder->deleteCharAt(14);
	}

	public function testDefaultEncoding()
	{
		$this->assertEquals('UTF-8', $this->stringBuilder->getEncoding());
	}

	public function testInitialEncoding()
	{
		$this->stringBuilder = new StringBuilder($this->initialString, 'ISO-8859-1');
		$this->assertEquals('ISO-8859-1', $this->stringBuilder->getEncoding());
		$this->assertNotEquals($this->initialString, $this->stringBuilder->__toString());
	}

	public function testSetEncoding()
	{
		$this->stringBuilder->setEncoding('ISO-8859-1');
		$this->assertEquals('ISO-8859-1', $this->stringBuilder->getEncoding());
		$this->assertEquals($this->initialString, $this->stringBuilder->__toString());
	}

	public function testChangeEncoding()
	{
		$this->stringBuilder->changeEncoding('ISO-8859-1');
		$this->assertEquals('ISO-8859-1', $this->stringBuilder->getEncoding());
		$this->assertNotEquals($this->initialString, $this->stringBuilder->__toString());
		$this->assertEquals(iconv('UTF-8', 'ISO-8859-1', $this->initialString), $this->stringBuilder->__toString());
	}

	public function testStartsWith()
	{
		$this->assertTrue($this->stringBuilder->startsWith('Hello'));
		$this->assertFalse($this->stringBuilder->startsWith('World!'));
	}

	public function testEndsWith()
	{
		$this->assertFalse($this->stringBuilder->endsWith('Hello'));
		$this->assertTrue($this->stringBuilder->endsWith('World!'));
	}

	public function testCharAt()
	{
		$this->assertEquals('§', $this->stringBuilder->charAt(6));
		$this->assertNotEquals('§', $this->stringBuilder->charAt(5));
		$this->assertNotEquals('§', $this->stringBuilder->charAt(7));
	}

	public function testIndexOf()
	{
		$this->assertEquals(4, $this->stringBuilder->indexOf('o'));
		$this->assertFalse($this->stringBuilder->indexOf('Foo'));
	}

	public function testLastIndexOf()
	{
		$this->assertEquals(9, $this->stringBuilder->lastIndexOf('o'));
		$this->assertFalse($this->stringBuilder->lastIndexOf('Bar'));
	}

	public function testContains()
	{
		$this->assertTrue($this->stringBuilder->contains('o § W'));
		$this->assertFalse($this->stringBuilder->contains('Zap'));
	}

	public function testSubstring()
	{
		$this->assertEquals(new StringBuilder("World!"), $this->stringBuilder->substring(8));
		$this->assertEquals(new StringBuilder("Hello"), $this->stringBuilder->substring(0, 4));
	}

	public function testLength()
	{
		$this->assertEquals(14, $this->stringBuilder->length());
		$this->stringBuilder->changeEncoding('ISO-8859-1');
		$this->assertEquals(14, $this->stringBuilder->length());
	}

	public function testAppend()
	{
		$this->stringBuilder->append(' The end is near!');
		$this->assertEquals('Hello § World! The end is near!', $this->stringBuilder->__toString());
	}

	public function testPrepend()
	{
		$this->stringBuilder->insert(0, 'The end is near! ');
		$this->assertEquals('The end is near! Hello § World!', $this->stringBuilder->__toString());
	}

	public function testInsert()
	{
		$this->stringBuilder->insert(6, 'The end is near! ');
		$this->assertEquals('Hello The end is near! § World!', $this->stringBuilder->__toString());
	}

	public function testReplace()
	{
		$this->stringBuilder->replace(6, 7, 'The end is near! ');
		$this->assertEquals('Hello The end is near! World!', $this->stringBuilder->__toString());
	}

	public function testDelete()
	{
		$this->stringBuilder->delete(5, 7);
		$this->assertEquals('HelloWorld!', $this->stringBuilder->__toString());
	}

	public function testDeleteCharAt()
	{
		$this->stringBuilder->deleteCharAt(6);
		$this->assertEquals('Hello  World!', $this->stringBuilder->__toString());
	}

	public function testReverse()
	{
		$this->stringBuilder->reverse();
		$this->assertEquals('!dlroW § olleH', $this->stringBuilder->__toString());
	}

	public function testShorten()
	{
		$this->stringBuilder->setLength(7);
		$this->assertEquals('Hello §', $this->stringBuilder->__toString());
	}

	public function testExpand()
	{
		$this->stringBuilder->setLength(15);
		$this->assertEquals('Hello § World! ', $this->stringBuilder->__toString());
	}

	public function testExpandWithPadding()
	{
		$this->stringBuilder->setLength(15, '123');
		$this->assertEquals('Hello § World!1', $this->stringBuilder->__toString());
	}

	public function testTrim()
	{
		$this->stringBuilder->trim('H!');
		$this->assertEquals('ello § World', $this->stringBuilder->__toString());
	}

	public function testLeftTrim()
	{
		$this->stringBuilder->trimLeft('H!');
		$this->assertEquals('ello § World!', $this->stringBuilder->__toString());
	}

	public function testRightTrim()
	{
		$this->stringBuilder->trimRight('H!');
		$this->assertEquals('Hello § World', $this->stringBuilder->__toString());
	}
}
