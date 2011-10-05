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

require_once (BINPATH . "Command.php" );
require_once (BINPATH . "Copy.php");
require_once (BINPATH . "ConcreteMementoCutCopyPaste.php");

/**
 * Execute and save the copy command
 *
 * @author wassim Chegham & hugo Marchadour
 * @package Memento
 * @category Originator
 * @version 0.1
 */

class CopySave implements Command
{
	/**
	 * @var Copy $_copy The copy command to be executed
	 * @access private
	 */
	private $_copy;

	/**
	 * @var Caretaker $_caretaker The caretaker
	 * @access private
	 */
	private $_caretaker;

	/**
	 * The constructor of the CopySave
	 * 
	 * @param Insert &$insert The refrence of the insert command
	 * @param Caretaker &$caretaker The reference of the caretaker 
	 * @return void
	 */
	public function __construct(&$insert, &$caretaker)
	{
		$this->_copy = $insert;
		$this->_caretaker = $caretaker;
	}

	/**
	 * (non-PHPdoc)
	 * @see bin/patterns/Command#execute()
	 */
	public function execute()
	{
		$this->_copy->execute();
		$this->_caretaker->save($this);
	}

	/**
	 * Get the reference of the current copy command
	 * @return Copy The copy command
	 */
	public function &getCommand()
	{
		return $this->_copy;
	}

	/**
	 * Get the reference of the current memento
	 * @return Memento The current memento
	 */
	public function &getMemento()
	{
		$attrs = array();
		$attrs['selStart'] = $this->_copy->getReceiver()->getSelectionStart();
		$attrs['selEnd'] = $this->_copy->getReceiver()->getSelectionEnd();
		$mem = new ConcreteMementoCutCopyPaste($this, $attrs);
		return $mem;
	}

	/**
	 * Set the new state of the copy command
	 * @param ConcreteMementoCutCopyPaste &$mem The reference of the memento
	 * @return void
	 */
	public function setMemento(&$mem)
	{
		$buffer =& $this->_copy->getReceiver();

		// update the current state of the buffer
		$buffer->setSelection($mem->getSelectionStart(), $mem->getSelectionEnd());
	}

}