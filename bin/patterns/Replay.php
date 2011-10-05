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

/**
 * The replay command
 *
 * @author wassim Chegham & hugo Marchadour
 * @package Command
 * @version 0.1
 */
 class Replay
 {
 	private $_careTaker;
 	
 	public function __construct(&$careTaker)
 	{
 		$this->_careTaker = $careTaker;
 	}
  	
 	public function isDone()
 	{
 		return $this->_careTaker->atTheEnd();
 	}
 	
 	public function initStep($step=0)
 	{
 		$this->_careTaker->resetCurrent($step);
 	}
 	
 	public function execute()
 	{
 		if(!$this->isDone()){
 			$mem =& $this->_careTaker->getNextMemento();
 			$mem->redo();
 		}
 	}
 }