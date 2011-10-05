<?php

/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

require_once (BINPATH . "Memento.php");

/**
 * The concrete Memento of the Buffer
 *
 * @author wassim Chegham & hugo Marchadour
 * @package Memento
 * @version 0.1
 */

class ConcreteMementoBuffer implements Memento
{
	/**
	 * @var String $_text The current text
	 * @access private
	 */
	private $_text;

	/**
	 * @var Clipboard $_clipboard The current clipboard object
	 * @access private
	 */
	private $_clipboard;

	/**
	 * @var Integer $_selectionStart The value of the beginning of the selection
	 * @access private
	 */
	private $_selectionStart;

	/**
	 * @var Integer $_selectionEnd The value of the end of the selection
	 * @access private
	 */
	private $_selectionEnd;

	/**
	 * @var Buffer $_buffer The current buffer object
	 * @access private
	 */
	private $_buffer;

	/**
	 * The constructor of the ConcreteMementoBuffer
	 * @param Buffer &$buffer The reference of the used Buffer
	 * @param Mixed[] $attrs An array containing the current text, the used clipboard, the beginning and the end of the selection
	 * @return void
	 */
	public function __construct(&$buffer, $attrs)
	{
		$this->_text = $attrs['text'];
		$this->_clipboard = $attrs['clipboard'];
		$this->_selectionStart = $attrs['selStart'];
		$this->_selectionEnd = $attrs['selEnd'];
		$this->_buffer = $buffer;
	}

	/**
	 * Get the beginning of the selection
	 * @return Integer The value of the beginning of the selection
	 */
	public function getSelectionStart()
	{
		return $this->_selectionStart;
	}

	/**
	 * Get the end of the selection
	 * @return Integer The value of the end of the selection
	 */
	public function getSelectionEnd()
	{
		return $this->_selectionEnd;
	}

	/**
	 * Get the current text
	 * @return String The value of the current text
	 */
	public function getText()
	{
		return $this->_text;
	}

	/**
	 * Get the used clipboard
	 * @return Clipboard The the used clipboard
	 */
	public function getClipboard()
	{
		return $this->_clipboard;
	}

	/**
	 * Redo the previous state of the Buffer
	 * @return void
	 */
	public function redo()
	{
		$this->_buffer->setMemento($this);
	}

}

?>