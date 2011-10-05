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

require_once ('Command.php');

/**
 * The Reset command
 *
 * @author wassim Chegham & hugo Marchadour
 * @package Command
 * @version 0.1
 */
class Reset implements Command
{
	
	private $_receiver;
	
	private $_replay;
	
	public function __construct(&$receiver, &$replay)
	{
		$this->_receiver = $receiver;
		$this->_replay = $replay;
	}
	
	public function execute()
	{
		$this->_replay->initStep();
		$this->_receiver->setText("");
		$this->_receiver->setTextIntoClipboard("");
		$this->_receiver->setSelection(0, 0);

		$this->_receiver->notify();
		
	}
	
}

?>