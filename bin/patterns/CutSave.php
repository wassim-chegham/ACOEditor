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
require_once (BINPATH . "Cut.php");
require_once (BINPATH . "ConcreteMementoCutCopyPaste.php");

/**
 * Execute and save the cut command
 *
 * @author wassim Chegham & hugo Marchadour
 * @package Memento
 * @category Originator
 * @version 0.1
 */

class CutSave implements Command
{
	/**
	 * @var Cut $_cut The cut command to be executed
	 * @access private
	 */
	private $_cut;

	/**
	 * @var Caretaker $_caretaker The caretaker
	 * @access private
	 */
	private $_caretaker;

	/**
	 * The constructor of the CopySave
	 * 
	 * @param Insert &$cut The refrence of the insert command
	 * @param Caretaker &$caretaker The reference of the caretaker 
	 * @return void
	 */
	public function __construct(&$cut, &$caretaker)
	{
		$this->_cut = $cut;
		$this->_caretaker = $caretaker;
	}

	/**
	 * (non-PHPdoc)
	 * @see bin/patterns/Command#execute()
	 */
	public function execute()
	{
		$this->_caretaker->save($this);
		$this->_cut->execute();
	}

	/**
	 * Get the reference of the current cut command
	 * @return Cut The cut command
	 */
	public function &getCommand()
	{
		return $this->_cut;
	}

	/**
	 * Get the reference of the current memento
	 * @return Memento The current memento
	 */	
	public function &getMemento()
	{
		$attrs = array();
		$attrs['selStart'] = $this->_cut->getReceiver()->getSelectionStart();
		$attrs['selEnd'] = $this->_cut->getReceiver()->getSelectionEnd();

		//		var_dump($this->_cut->getReceiver()->getSelectionStart(), $this->_cut->getReceiver()->getSelectionEnd());
		//		exit;

		$mem = new ConcreteMementoCutCopyPaste($this, $attrs);
		return $mem;
	}
	
	/**
	 * Set the new state of the cut command
	 * @param ConcreteMementoCutCopyPaste &$mem The reference of the memento
	 * @return void
	 */
	public function setMemento(&$mem)
	{
		$buffer =& $this->_cut->getReceiver();

		// update the current state of the buffer
		$buffer->setSelection($mem->getSelectionStart(), $mem->getSelectionEnd());
	}

}