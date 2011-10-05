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

/**
 *
 *
 * @author wassim Chegham & hugo Marchadour
 * @package Memento
 * @version 0.1
 */

class Redo implements Command
{
	/**
	 * @var unknown_type
	 */
	private $_replay;
	private $_caretaker;

	public function __construct(& $replay, &$caretaker)
	{
		$this->_replay = $replay;
		$this->_caretaker = $caretaker;
	}

	public function execute()
	{
		$pointer = $this->_caretaker->getCurrent();
		if(($pointer%BUFFER_SAVE_STEP)==0){
			$this->_replay->execute();
		}
		$this->_replay->execute();
	}
}