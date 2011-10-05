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

/**
 * The Caretaker that will save all the concrete Mementos
 *
 * @author wassim Chegham & hugo Marchadour
 * @package Memento
 * @version 0.1
 */
class Caretaker
{

	/**
	 * @var Memento[] $_mementos The array where to store the Mementos
	 * @access private
	 */
	private $_mementos;

	/**
	 * @var Integer $_end The highest value of the current Memeno array we should not go beyond 
	 * It is the lenght of the array
	 * @access private
	 */
	private $_end;

	/**
	 * @var Integer $_current The current index in the Memento array
	 * The current Memento
	 */
	private $_current;

	/**
	 * @var Buffer $_buffer The current Buffer that 
	 * @access private
	 */
	private $_buffer;

	/**
	 * The constructor of the Caretaker
	 * 
	 * @param $buffer The reference of the buffer
	 * @return void
	 */
	public function __construct(&$buffer)
	{
		$this->_end = 0;
		$this->_current = 0;
		$this->_mementos = array();
		$this->_buffer = $buffer;
	}

	/**
	 * Save (memorise) the Memento
	 * 
	 * @param $commandSave The reference of the memento we want to save
	 * @return void
	 */
	public function save(&$commandSave){
		if( $this->_current % BUFFER_SAVE_STEP == 0 ){
			$this->_mementos[$this->_current] = & $this->_buffer->getMemento();
			$this->_current++;
		}
		$this->_mementos[$this->_current] = & $commandSave->getMemento();
		$this->_current++;
		// reset the end
		$this->_end = $this->_current;
	}

	/**
	 * Get the current memento
	 * 
	 * @return Memento The current memento
	 */
	public function getCurrent()
	{ return $this->_current; }

	/**
	 * Reset the current Memento
	 * 
	 * @param Memento $current The new memento
	 * @return void
	 */
	public function resetCurrent($current){
		$this->_current = $current;
	}

	/**
	 * Check weither we reached the end of the caretaker or not
	 * 
	 * @return Boolean True if we are at the end of the caretaker
	 */
	public function atTheEnd(){
		return $this->_current >= $this->_end;
	}

	/**
	 * Get the next memento
	 * 
	 * @return Memento The next memento or NULL if there is no next element
	 */
	public function &getNextMemento()
	{
		if( !$this->atTheEnd() ){
			return $this->_mementos[$this->_current++];
		}
		else
		return null;
	}

	/**
	 * Decrement the current index
	 * 
	 * @return void
	 */
	public function decCurrent(){
		if($this->_current > 0){
			$this->_current--;
		}
	}

}


?>