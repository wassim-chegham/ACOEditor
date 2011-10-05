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
 * The copy command
 * 
 * @author wassim Chegham & hugo Marchadour
 * @package Command
 * @version 0.1
 */
class Copy implements Command
{

	/**
	 * @var Buffer $_receiver The receiver object of the Copy command (the Buffer)
	 * @access protected
	 */
	protected $_receiver;

	/**
	 * The constructor of the Copy command
	 * @param Buffer &$receiver The reference of the receiver object
	 * @return void
	 */
	public function __construct(&$receiver)
	{
		$this->_receiver = $receiver;
	}
	
	/**
	 * Execute the copy action
	 * @return void
	 */
	public function execute()
	{
		$this->_receiver->copyText();
	}
	
	/**
	 * Get the current receiver
	 * @return Buffer The current receiver of the Insert command
	 * @access public
	 */
	public function &getReceiver() {
		return $this->_receiver;
	}
}

?>
