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

require_once (BINPATH . 'Observer.php');
require_once (BINPATH . 'Subject.php');
require_once (BINPATH . 'Clipboard.php');
require_once (BINPATH . 'Ihm.php');
require_once (BINPATH . "Memento.php" );
require_once (BINPATH . "ConcreteMementoBuffer.php" );

/**
 * This class represents a buffer, which contains a temporary text content;
 * This is also the concrete subject of the Obsever Design Pattern
 *
 * @author wassim Chegham & hugo Marchadour
 * @package Observer
 * @category ConcreteSubject
 * @version 0.1
 */
class Buffer implements Observer, Subject, Memento
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
	 * @var Ineteger $_selectionStart The begining of a selection.
	 * @access private
	 */
	private $_selectionStart;

	/**
	 * @var Integer $_selectionEnd The end of a selection.
	 * @access private
	 */
	private $_selectionEnd;

	/**
	 * @var Integer $_clipboard The clipboard object.
	 * @access private
	 */
	private $_clipboard;

	/**
	 * @var Observer[] $_observers The array who contains all observers.
	 * @access private
	 */
	private $_observers;

	/**
	 * The constructor of the Buffer
	 * @return void
	 * @access public
	 */
	public function __construct()
	{
		$this->_observers = array();
		$this->_text = "";
		$this->_selectionStart = 0;
		$this->_selectionEnd = 0;
		$this->_clipboard = new Clipboard();
		$this->crtime = strftime("%T", time());
		$this->buffer_hash = spl_object_hash($this);
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
	 * Set the begining of the selection
	 * @return void
	 * @param Integer $val the begining of the selection
	 * @access public
	 */
	public function setSelectionStart($val)
	{
		$this->_selectionStart = $val;
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
	}

	/**
	 * Set the begining and the end of the selection
	 * @return void
	 * @param Integer $start the begining of the selection
	 * @param Integer $end the end of the selection
	 * @access public
	 */
	public function setSelection($start, $end)
	{

		if ( !is_int($start) || !is_int($end) )
		{
			//throw new Exception( 'Buffer->setSelection(int, int).', E_USER_ERROR );
				
			$start = intVal($start);
			$end = intVal($end);
				
		}

		if( $start <= $end)
		{
			$this->_selectionStart = $start;
			$this->_selectionEnd = $end;
		}
		else
		{
			$this->_selectionStart = $end;
			$this->_selectionEnd = $start;
		}

	}

	/**
	 * Insert a character into the buffer
	 * @return void
	 * @param Character $char the character to be inserted
	 * @access public
	 */
	public function insert($char)
	{
		$tmp_str = $this->_text;

		$this->_text = Buffer::_substr($tmp_str, 0, $this->_selectionStart);
		$this->_text .= $char;
		$this->_text .= Buffer::_substr($tmp_str, $this->_selectionEnd);

		$this->_selectionStart++;

		if($this->_selectionStart !== $this->_selectionEnd)
		{
			$this->_selectionEnd = $this->_selectionStart;
		}

		$this->notify();

	}

	/**
	 * Set a text content into the clipboard
	 * @return void
	 * @access public
	 */
	public function setTextIntoClipBoard($text)
	{
		$this->_clipboard->setText($text);
	}

	/**
	 * Get a text content from the clipboard
	 * @return String the text content from the clipboard
	 * @access public
	 */
	public function getTextFromClipBoard()
	{
		return $this->_clipboard->getText();
	}

	/**
	 * Copy the current selection into clipboard
	 * @return void
	 * @access public
	 */
	public function copyText()
	{
		$text = Buffer::_substr($this->_text, $this->_selectionStart, $this->_selectionEnd);

		if ( $text !== "" )
		$this->setTextIntoClipBoard($text);
	}

	/**
	 * Cut the current selection into clipboard
	 * @return void
	 * @access public
	 */
	public function cutText()
	{

		if( $this->_selectionStart !== $this->_selectionEnd)
		{

			$text_to_be_cut = Buffer::_substr($this->_text, $this->_selectionStart, $this->_selectionEnd);
			$this->setTextIntoClipBoard($text_to_be_cut);

			$tmp_str = Buffer::_substr($this->_text, 0, $this->_selectionStart);
			$tmp_str .= Buffer::_substr($this->_text, $this->_selectionEnd);
				
			// deselection
			$this->_selectionEnd = $this->_selectionStart;

			$this->_text = $tmp_str;

			$this->notify();

		}

	}

	/**
	 * Paste the content of the clipboard into the buffer
	 * @return void
	 * @access public
	 */
	public function pasteText()
	{

		if( $this->_clipboard->getText() !== "")
		{

			$paste = $this->getTextFromClipBoard();

			$tmp_res = Buffer::_substr($this->_text, 0, $this->_selectionStart);
			$tmp_res .= $paste;
			$tmp_res .= Buffer::_substr($this->_text, $this->_selectionEnd);

			$this->_selectionStart += strlen($paste);
			if( $this->_selectionStart !== $this->_selectionEnd )
			{

				// deselection
				$this->_selectionEnd = $this->_selectionStart;

			}

			$this->_text = $tmp_res;

			$this->notify();

		}

	}
	
	/**
	 * Create a new memento with the correct values
	 * @return ConcreteMementoBuffer A new ConcreteMementoBuffer
	 * @access pubic
	 */
	public function &getMemento()
	{
		$attrs = array();
		$attrs['selStart'] = $this->_selectionStart;
		$attrs['selEnd'] = $this->_selectionEnd;
		$attrs['clipboard'] = $this->getTextFromClipBoard();
		$attrs['text'] = $this->_text;
		$mem = new ConcreteMementoBuffer($this, $attrs);
		return $mem;
	}

	/**
	 * Update the current state of the Buffer
	 * @param $mem The ConcreteMementoBuffer that contains the necessary values
	 * @access public 
	 */
	public function setMemento(&$mem)
	{
		$this->_text = $mem->getText();
		$this->setTextIntoClipBoard($mem->getClipboard());
		// update the current state of the buffer
		$this->setSelection($mem->getSelectionStart(), $mem->getSelectionEnd());
		$this->notify();
	}

	/**
	 * Attach an observer to this concrete subject
	 * @return void
	 * @param Observer $o the observer of this concrete subject
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
	 * @param Observer $o the observer to be detached
	 */
	public function detach(&$o)
	{
		$index = array_search($o, $this->_observers);
		if($index)
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
	 * Update the current state of the buffer
	 * @param Ihm $s The reference of the observer of the subject
	 * @access public
	 */
	public function update(&$s)
	{
		$this->_selectionStart = $s->getSelectionStart();
		$this->_selectionEnd = $s->getSelectionEnd();
		$this->_text = $s->getText();
	}

	/**
	 * Get a sub string from another string
	 * @param String $str The string where from where the sub string should be got
	 * @param Integer $selStart The index where to start
	 * @param Integer $selEnd The index where to end
	 * @return String The sub string
	 * @access public
	 * @static
	 */
	private static function _substr($str, $selStart, $selEnd=-1)
	{
		if ( $selEnd === -1 )
		{
			$selEnd = strlen($str);
		}
		else {
			$selEnd -= $selStart;
		}
		return substr( $str, $selStart, $selEnd );

	}
}

?>
