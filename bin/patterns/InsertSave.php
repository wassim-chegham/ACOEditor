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

require_once (BINPATH . "Command.php");
require_once (BINPATH . "Insert.php");
require_once (BINPATH . "ConcreteMementoInsert.php");

/**
 *
 *
 * @author wassim Chegham & hugo Marchadour
 * @package Memento
 * @category Originator
 * @version 0.1
 */

class InsertSave implements Command
{
	/**
	 * @var unknown_type
	 */
	private $_insert, $_caretaker;

	public function __construct(&$insert, &$caretaker)
	{
		$this->_insert = $insert;
		$this->_caretaker = $caretaker;
	}

	public function execute()
	{
		$this->_caretaker->save($this);
		$this->_insert->execute();
	}

	public function &getMemento()
	{
		$ihm =& $this->_insert->getSender();
		$attrs = array();
		$attrs['char'] = $ihm->getChar();
		$attrs['selStart'] = $this->_insert->getReceiver()->getSelectionStart();
		$attrs['selEnd'] = $this->_insert->getReceiver()->getSelectionEnd();

		$mem = new ConcreteMementoInsert($this, $attrs);
		return $mem;
	}

	public function &getCommand()
	{
		return $this->_insert;
	}
	
	public function setMemento(&$mem)
	{
		$ihm =& $this->_insert->getSender();
		$buffer =& $this->_insert->getReceiver();
		
		// update the current state of the buffer
		//$this->_insert->setCurrentChar($mem->getChar());
		$ihm->setChar($mem->getChar());
		$buffer->setSelection($mem->getSelectionStart(), $mem->getSelectionEnd());
		
	}
	
}