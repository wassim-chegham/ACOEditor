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
 * The InsertChar command
 *
 * @author wassim Chegham & hugo Marchadour
 * @package Command
 * @version 0.1
 */
class Insert implements Command
{

	/**
	 * @var Buffer $_receiver The object receiver of the Cut command (the Buffer)
	 * @access protected
	 */
	protected $_receiver;

	/**
	 * @var Ihm $_sender The object sender of the Cut command (the Ihm)
	 * @access protected
	 */
	protected $_sender;

	/**
	 * @var Integer $crtime The time when this object is created
	 * @access public
	 */
	public $crtime;

	/**
	 * @var String $insert_hash The unique hash of this object
	 * @access public
	 */
	public $insert_hash;

	/**
	 * @var String $insert_hash The unique hash of the receiver object
	 * @access public
	 */
	public $receiver_hash;

	/**
	 * @var String $insert_hash The unique hash of the sender object
	 * @access public
	 */
	public $sender_hash;

	/**
	 * @var Character $_current_char The current character to be inserted
	 * @access private
	 */
	//protected $_current_char;

	/**
	 * The constructor of the Insert command
	 * @param Ihm $sender The Ihm object from where to get the character to be inserted
	 * @param Buffer $receiver The Buffer object where to insert the current character
	 */
	public function __construct(&$sender, &$receiver)
	{
		$this->_receiver = $receiver;
		$this->_sender = $sender;

		//$this->_current_char = '';
		$this->crtime = strftime("%T", time());
		$this->insert_hash = spl_object_hash ($this);

		$this->sender_hash = $sender->ihm_hash;
		$this->receiver_hash = $receiver->buffer_hash;

	}

	/**
	 * Perfome the insert action
	 * @return void
	 * @access public
	 */
	public function execute()
	{
		$char = $this->_sender->getChar();
		$this->_current_char = $char;
		$this->_receiver->insert($char);
	}

	/**
	 * Get the current char
	 * @return Char The current char
	 * @access public
	 
	public function getCurrentChar()
	{
		return $this->_current_char;
	}*/

	/**
	 * 
	 * @param $c
	 * @return unknown_type
	 */
	/*public function setCurrentChar()
	{
		$this->_current_char = $c;
	}*/


	/**
	 * Get the current receiver
	 * @return Buffer The current receiver of the Insert command
	 * @access public
	 */
	public function &getReceiver() {
		return $this->_receiver;
	}

	/**
	 * Get the current sender
	 * @return Ihm The current sender of the Insert command
	 * @access public
	 */
	public function &getSender() {
		return $this->_sender;
	}
	

}

?>
