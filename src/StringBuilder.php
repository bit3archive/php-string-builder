<?php

/**
 * StringBuilder for PHP
 *
 * PHP Version 5.3
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @license    LGPL-3.0+
 */

/**
 * StringBuilder for PHP provide string manipulation logic in an object.
 */
class StringBuilder
{
	/**
	 * @var string
	 */
	protected $string;

	/**
	 * @var string
	 */
	protected $encoding;

	/**
	 * @param string|null $string   The initial sequence.
	 * @param string|null $encoding The encoding of the sequence.
	 */
	public function __construct($string = null, $encoding = 'UTF-8')
	{
		$this->string   = static::_convertString($string, $encoding);
		$this->encoding = (string) $encoding;
	}

	/**
	 * Set the encoding of this sequence.
	 * Warning: This will not convert an existing sequence!
	 *
	 * @param string $encoding
	 */
	public function setEncoding($encoding)
	{
		$this->encoding = (string) $encoding;
		return $this;
	}

	/**
	 * Convert this sequence into another encoding.
	 *
	 * @param $encoding
	 */
	public function changeEncoding($encoding)
	{
		$encoding       = (string) $encoding;
		$this->string   = iconv($this->encoding, $encoding, $this->string);
		$this->encoding = $encoding;
	}

	/**
	 * Get the encoding of this sequence.
	 *
	 * @return string
	 */
	public function getEncoding()
	{
		return $this->encoding;
	}

	/**
	 * Check if this sequence starts with a given string.
	 *
	 * @param string $string
	 *
	 * @return bool
	 */
	public function startsWith($string)
	{
		$string = static::_convertString($string, $this->encoding);
		return $string === $this->substring(0, mb_strlen($string));
	}

	/**
	 * Check if this sequence ends with a given string.
	 *
	 * @param string $string
	 *
	 * @return bool
	 */
	public function endsWith($string)
	{
		$string = static::_convertString($string, $this->encoding);
		return $string === $this->substring($this->length() - mb_strlen($string));
	}

	/**
	 * Return the character at the given position in the sequence.
	 *
	 * @param int $index
	 *
	 * @return string
	 * @throws OutOfBoundsException
	 */
	public function charAt($index)
	{
		$index = (int) $index;

		if ($index < 0 || $index >= $this->length()) {
			throw new OutOfBoundsException();
		}

		return mb_substr($this->string, $index, 1, $this->encoding);
	}

	/**
	 * Return the first occurrence of a string in the sequence.
	 *
	 * @param string   $string
	 * @param int|null $offset
	 *
	 * @return int
	 * @throws OutOfBoundsException
	 */
	public function indexOf($string, $offset = null)
	{
		$string = static::_convertString($string, $this->encoding);
		$offset = $offset !== null ? (int) $offset : null;

		if ($offset !== null && ($offset < 0 || $offset >= $this->length())) {
			throw new OutOfBoundsException();
		}

		return mb_strpos($this->string, $string, $offset, $this->encoding);
	}

	/**
	 * Return the last occurrence of a string in the sequence.
	 *
	 * @param string   $string
	 * @param int|null $offset
	 *
	 * @return int
	 * @throws OutOfBoundsException
	 */
	public function lastIndexOf($string, $offset = null)
	{
		$string = static::_convertString($string, $this->encoding);
		$offset = $offset !== null ? (int) $offset : null;

		if ($offset !== null && ($offset < 0 || $offset >= $this->length())) {
			throw new OutOfBoundsException();
		}

		return mb_strrpos($this->string, $string, $offset, $this->encoding);
	}

	/**
	 * Check if this sequence contains the given string.
	 *
	 * @param string $string
	 *
	 * @return bool
	 */
	public function contains($string)
	{
		return $this->indexOf($string) !== false;
	}

	/**
	 * Return a substring of this sequence.
	 *
	 * @param int $start
	 * @param int $end
	 *
	 * @return string
	 * @throws OutOfBoundsException
	 */
	public function substring($start, $end = null)
	{
		$start = (int) $start;
		$end   = $end !== null ? (int) $end : null;

		if ($start < 0 || $start >= $this->length() || $end !== null && ($end < 0 || $end >= $this->length())) {
			throw new OutOfBoundsException();
		}

		return new StringBuilder(mb_substr($start, $end !== null ? $end + 1 : null));
	}

	/**
	 * Return the length of this sequence.
	 *
	 * @return int
	 */
	public function length()
	{
		return mb_strlen($this->string, $this->encoding);
	}

	/**
	 * Append a string to the sequence.
	 *
	 * @param string $string
	 *
	 * @return $this
	 */
	public function append($string)
	{
		$string = static::_convertString($string, $this->encoding);
		$this->string .= $string;
		return $this;
	}

	/**
	 * Insert a string into the sequence.
	 *
	 * @param int    $offset
	 * @param string $string
	 *
	 * @return $this
	 * @throws OutOfBoundsException
	 */
	public function insert($offset, $string)
	{
		$offset = (int) $offset;
		$string = static::_convertString($string, $this->encoding);

		if ($offset < 0 || $offset >= $this->length()) {
			throw new OutOfBoundsException();
		}

		$this->string = mb_substr(
				$this->string,
				0,
				$offset,
				$this->encoding
			) .
			$string .
			mb_substr(
				$this->string,
				$offset + 1,
				null,
				$this->encoding
			);
		return $this;
	}

	/**
	 * Replace a substring by another string in this sequence.
	 *
	 * @param int    $start
	 * @param int    $end
	 * @param string $string
	 *
	 * @return $this
	 * @throws OutOfBoundsException
	 */
	public function replace($start, $end, $string)
	{
		$start  = (int) $start;
		$end    = (int) $end;
		$string = static::_convertString($string, $this->encoding);

		if ($start < 0 || $start >= $this->length() || $end < 0 || $end >= $this->length()) {
			throw new OutOfBoundsException();
		}

		$this->string = mb_substr(
				$this->string,
				0,
				$start,
				$this->encoding
			) .
			$string .
			mb_substr(
				$this->string,
				$end + 1,
				null,
				$this->encoding
			);
		return $this;
	}

	/**
	 * Remove the characters in a substring of this sequence.
	 *
	 * @param int $start Start of substring.
	 * @param int $end   End of substring.
	 *
	 * @throws OutOfBoundsException
	 */
	public function delete($start, $end)
	{
		$start = (int) $start;
		$end   = (int) $end;

		if ($start < 0 || $start >= $this->length() || $end < 0 || $end >= $this->length()) {
			throw new OutOfBoundsException();
		}

		$this->string = mb_substr(
				$this->string,
				0,
				$start,
				$this->encoding
			) .
			mb_substr(
				$this->string,
				$end + 1,
				null,
				$this->encoding
			);
		return $this;
	}

	/**
	 * Delete the character at the index, the sequence will be shorten by one.
	 *
	 * @param int $index
	 *
	 * @return $this
	 * @throws OutOfBoundsException
	 */
	public function deleteCharAt($index)
	{
		$index = (int) $index;
		if ($index < 0 || $index >= $this->length()) {
			throw new OutOfBoundsException();
		}

		$this->string = mb_substr(
				$this->string,
				0,
				$index,
				$this->encoding
			) .
			mb_substr(
				$this->string,
				$index + 1,
				null,
				$this->encoding
			);
		return $this;
	}

	/**
	 * Reverse the sequence.
	 *
	 * @return $this
	 */
	public function reverse()
	{
		$length   = $this->length();
		$reversed = '';
		while ($length-- > 0) {
			$reversed .= mb_substr($this->string, $length, 1, $this->encoding);
		}

		$this->string = $reversed;
		return $this;
	}

	/**
	 * Set the length of this sequence.
	 * If the sequence is shorter, than it will be pad with spaces.
	 *
	 * @param int    $newLength
	 * @param string $padding
	 *
	 * @return $this
	 */
	public function setLength($newLength, $padding = ' ')
	{
		$newLength     = (int) $newLength;
		$currentLength = $this->length();

		if ($newLength != $currentLength) {
			while ($newLength > $this->length()) {
				$this->string .= $padding;
			}
			if ($newLength < $this->length()) {
				$this->string = mb_substr($this->string, 0, $newLength);
			}
		}

		return $this;
	}

	/**
	 * Trim characters from the left and right side of this sequence.
	 *
	 * @param string|null $characters
	 *
	 * @return $this
	 */
	public function trim($characters = null)
	{
		$this->string = trim($this->string, $characters);
		return $this;
	}

	/**
	 * Trim characters from the left side of this sequence.
	 *
	 * @param string|null $characters
	 *
	 * @return $this
	 */
	public function trimLeft($characters = null)
	{
		$this->string = ltrim($this->string, $characters);
		return $this;
	}

	/**
	 * Trim characters from the right side of this sequence.
	 *
	 * @param string|null $characters
	 *
	 * @return $this
	 */
	public function trimRight($characters = null)
	{
		$this->string = rtrim($this->string, $characters);
		return $this;
	}

	/**
	 * Return the string representation.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->string;
	}

	static protected function _convertString($string, $outputEncoding)
	{
		if ($string instanceof StringBuilder) {
			$inputEncoding = $string->getEncoding();
		}
		else {
			$inputEncoding = mb_detect_encoding((string) $string);
		}
		$string = (string) $string;
		if ($inputEncoding != $outputEncoding) {
			$string = iconv($inputEncoding, $outputEncoding, $string);
		}
		return $string;
	}
}
