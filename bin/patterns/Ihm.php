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

require_once ('Observer.php');
require_once ('Subject.php');
require_once ('Caretaker.php');

require_once ('InsertSave.php');
require_once ('CopySave.php');
require_once ('CutSave.php');
require_once ('PasteSave.php');

require_once ('Reset.php');
require_once ('Replay.php');

require_once ('Undo.php');
require_once ('Redo.php');

/**
 * The Ihm Observer
 *
 * @author wassim Chegham & hugo Marchadour
 * @package Observer
 * @category ConcreteObserver
 * @version 0.1
 */
class Ihm implements Observer, Subject
{

	/**
	 * @var Integer $crtime The current time (used for debugging).
	 * @access public
	 */
	public $crtime;

	/**
	 * @var String $ihm_hash The unique hash of this object (used for debugging).
	 * @access public
	 */
	public $ihm_hash;


	/**
	 * @var String $_text The content of the current text.
	 * @access private
	 */
	private $_text;

	/**
	 * @var Integer $_selectionStart The begining of a selection.
	 * @access private
	 */
	private $_selectionStart;

	/**
	 * @var Integer $_selectionEnd The end of a selection.
	 * @access private
	 */
	private $_selectionEnd;

	/**
	 * @var Clipboard $_clipboard The clipboard object.
	 * @access private
	 */
	private $_clipboard;

	/**
	 * @var Observer[] $_observers The array which contains all observers.
	 * @access private
	 */
	private $_observers;

	/**
	 * @var Character $_current_char The current character.
	 * @access private
	 */
	private $_current_char;

	/**
	 * @var Command[] $_commands The arrau which contains all commands.
	 * @access private
	 */
	private $_commands;
	
	private $_caretaker;

	/**
	 * The constructor of the Ihm class
	 *
	 * @param Buffer $buffer The reference of the buffer
	 */
	public function __construct(&$buffer)
	{
		$this->ihm_hash = spl_object_hash($this);
		$this->_commands = array();
		$this->_observers = array();


		$this->_text = "";
		$this->_current_char = '#';
		$this->_selectionStart = 0;
		$this->_selectionEnd = 0;
		$this->crtime = strftime("%T", time());

		$this->_commands['insert'] = new Insert($this, $buffer);
		$this->_commands['copy'] = new Copy($buffer);
		$this->_commands['cut'] = new Cut($buffer);
		$this->_commands['paste'] = new Paste($buffer);

		$careTaker = new Caretaker($buffer);
		$this->_commands['insertSave'] = new InsertSave($this->_commands['insert'], $careTaker);
		$this->_commands['copySave'] = new CopySave($this->_commands['copy'], $careTaker);
		$this->_commands['cutSave'] = new CutSave($this->_commands['cut'], $careTaker);
		$this->_commands['pasteSave'] = new PasteSave($this->_commands['paste'], $careTaker);

		$replay = new Replay($careTaker);

		$this->_commands['replay'] = $replay;
		$this->_commands['reset'] = new Reset($buffer, $replay);

		$this->_commands['undo'] = new Undo($replay, $careTaker);
		$this->_commands['redo'] = new Redo($replay, $careTaker);
		$this->_caretaker = $careTaker;
	}

	/**
	 * Get the current character
	 * @return Character The current character
	 * @access public
	 */
	public function getChar()
	{
		return $this->_current_char;
	}

	/**
	 * Set the current character
	 * @param Character $char The current character
	 * @access public
	 */
	public function setChar($char)
	{
		$this->_current_char = $char;
	}

	/**
	 * Get the begining of the selection
	 * @return int the position of the begining of the selection
	 * @access public
	 */
	public function getSelectionStart()
	{
		return $this->_selectionStart;
	}

	/**
	 * Set the end of the selection
	 * @return int the position of the begining of the selection
	 * @access public
	 */
	public function getSelectionEnd()
	{
		return $this->_selectionEnd;
	}

	/**
	 * Get the current text
	 * @return the current text
	 * @access public
	 */
	public function getText()
	{
		return $this->_text;
	}

	/**
	 * Set the content of the current text
	 * @return void
	 * @param String $text the new content
	 * @access public
	 */
	public function setText($text)
	{
		$this->_text = $text;
	}

	/**
	 * Set the begining of the selection
	 * @return void
	 * @param Integer $val the begining of the selection
	 * @access public
	 */
	public function setSelectionStart($val)
	{
		$this->_selectionStart = $val;
		$this->notify();
	}

	/**
	 * Set the end of the selection
	 * @return void
	 * @param Integer $val the end of the selection
	 * @access public
	 */
	public function setSelectionEnd($val)
	{
		$this->_selectionEnd = $val;
		$this->notify();
	}

	/**
	 * Update the current selection
	 * @param Inetger $selStart The begining of the selection
	 * @param Integer $selEnd The end of the selection
	 * @return void
	 * @access public
	 */
	public function updateSelection($selStart, $selEnd)
	{
		$this->setSelectionStart($selStart);
		$this->setSelectionEnd($selEnd);
		$this->notify();
	}

	/**
	 * Insert a new character into the buffer
	 * @return void
	 * @access public
	 */
	public function insert()
	{
		$this->_commands['insertSave']->execute();
	}

	/**
	 * Copy the selected text into the buffer
	 * @return void
	 * @access public
	 */
	public function copy()
	{
		$this->_commands['copySave']->execute();
	}

	/**
	 * Cut the selected text into the buffer
	 * @return void
	 * @access public
	 */
	public function cut()
	{
		$this->_commands['cutSave']->execute();
	}

	/**
	 * Paste the text from the clipboard into the buffer
	 * @return void
	 * @access public
	 */
	public function paste()
	{
		$this->_commands['pasteSave']->execute();
	}

	/**
	 * Replay all saved commands
	 * @return void
	 * @access public
	 */
	public function replay()
	{
		$this->_commands['replay']->execute();
	}

	public function undo()
	{
		$this->_commands['undo']->execute();
	}

	public function redo()
	{
		$this->_commands['redo']->execute();
	}

	/**
	 * Is there an other commands to play
	 * @return Boolean True if there is another command to play
	 * @access public
	 */
	public function canReplay()
	{
		return ! $this->_commands['replay']->isDone();
	}

	public function canUndo()
	{
		return $this->_caretaker->getCurrent()>1;
	}

	/**
	 * Reset all states of Ihm and Buffer
	 * @return void
	 * @access public
	 */
	public function reset()
	{
		$this->_commands['reset']->execute();
	}

	/**
	 * Attach an observer to this concrete subject
	 * @return void
	 * @param Observer $o The reference of the observer of this concrete subject
	 */
	public function attach(&$o)
	{
		if(!in_array($o, $this->_observers))
		{
			$this->_observers[] = $o;
		}
	}

	/**
	 * Detach an observer from this concrete subject
	 * @return void
	 * @param Observer $o The reference of the observer to be detached
	 */
	public function detach(&$o)
	{
		$index = array_search($o, $this->_observers);
		if($index !== false)
		{
			unset($this->_observers[$index]);
		}
	}

	/**
	 * Notify the observers of this concrete subject
	 * @return void
	 * @access public
	 */
	public function notify()
	{
		foreach($this->_observers as $k => $o)
		{
			$o->update($this);
		}
	}

	/**
	 * Update the current IHM's state
	 * @param Buffer $s The reference of the subject
	 */
	public function update(&$s)
	{
		$this->_selectionStart = $s->getSelectionStart();
		$this->_selectionEnd = $s->getSelectionEnd();
		$this->_text = $s->getText();
	}
}

?>
