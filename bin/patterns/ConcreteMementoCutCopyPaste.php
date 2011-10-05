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
 * The concrete Memento of the Copy, Cut and Paste commands
 *
 * @author wassim Chegham & hugo Marchadour
 * @package Memento
 * @version 0.1
 */

class ConcreteMementoCutCopyPaste implements Memento
{
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
	 * @var Command $_saveCommand The command we want to save
	 * @access private
	 */
	private $_saveCommand;

	/**
	 * The constructor of the ConcreteMementoCutCopyPaste Memento 
	 * @param Command &$command The reference of the command
	 * @param Mixed[] $attrs An array containing the beginning and the end of the selection
	 * @return void
	 */
	public function __construct(&$command, $attrs)
	{
		$this->_saveCommand = $command;
		$this->_selectionStart = $attrs['selStart'];
		$this->_selectionEnd = $attrs['selEnd'];
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
	 * Re-execute the previous saved Command
	 * @return void
	 */
	public function redo()
	{
		$this->_saveCommand->setMemento($this);
		$this->_saveCommand->getCommand()->execute();
	}

}

?>