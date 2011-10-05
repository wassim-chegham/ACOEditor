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
require_once (BINPATH . "Paste.php");
require_once (BINPATH . "ConcreteMementoCutCopyPaste.php");

/**
 *
 *
 * @author wassim Chegham & hugo Marchadour
 * @package Memento
 * @category Originator
 * @version 0.1
 */

class PasteSave implements Command
{
	/**
	 * @var unknown_type
	 */
	private $_paste, $_caretaker;
	
	public function __construct(& $insert, & $caretaker)
	{
		$this->_paste = $insert;
		$this->_caretaker = $caretaker;
	}

	public function execute()
	{
		$this->_caretaker->save($this);
		$this->_paste->execute();
		
	}

	public function &getCommand()
	{
		return $this->_paste;
	}
	
	public function &getMemento()
	{
		$attrs = array();
      	$attrs['selStart'] = $this->_paste->getReceiver()->getSelectionStart();
      	$attrs['selEnd'] = $this->_paste->getReceiver()->getSelectionEnd();

      	$mem = new ConcreteMementoCutCopyPaste($this, $attrs);
		return $mem;
	}
	
	public function setMemento(&$mem)
	{
		$buffer =& $this->_paste->getReceiver();
		
		// update the current state of the buffer
		$buffer->setSelection($mem->getSelectionStart(), $mem->getSelectionEnd());
	}

}