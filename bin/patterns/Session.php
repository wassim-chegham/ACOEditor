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

require_once (BINPATH . 'Ihm.php');
require_once (BINPATH . 'Buffer.php');

/**
 * This class represents the main entry of the application
 *
 * @author wassim Chegham & hugo Marchadour
 * @package Singleton
 * @version 0.1
 */
class Session
{
  
	/**
	 * @var Integer $allocatedSize The allocated size of this object in Bytes
	 * @access public
	 */
	public $allocatedSize;
	
	
	/**
	 * @var Ihm $ihm The ihm object
	 */
	public $ihm;

  /**
   * @var Session $_instance Represents the instance of the current object
   * @access private
   * @static
   */
  private static $_instance = null;

  /**
   * The constructor of the Session
   * @return void
   */
  private function __construct()
  {

    	$_buffer = new Buffer();
      $this->ihm = new Ihm($_buffer);
      $_buffer->attach($this->ihm);
      $this->ihm->attach($_buffer);

  } 

  /**
   * Override the __destrcutor methode of the Session class
   * @return void
   * @access public
   */
  public function __destruct()
  {
  }

  /**
   * Override the __clone methode of the Session class
   * in order to forbide Object cloning.
   * @return void
   * @access public
   */
  public function __clone()
  {
    trigger_error( 'Clone is not allowed when using a singleton class.', E_USER_ERROR );
  }

  /**
   * Singleton Pattern: keep a single instance of the current object
   * @return the instance of the current object
   * @access public
   */
  public static function &getInstance()
  {
    
  	if( is_null( self::$_instance ) )
    {
	  	$c = __CLASS__;
	  	$mem_before = memory_get_usage(true);
      self::$_instance = new $c();
      $mem_after = memory_get_usage(true);
      self::$_instance->allocatedSize = ($mem_after - $mem_before);
      
    }
    return self::$_instance;
  }
}

/**
 * Call this function to get the current instance of the Session object
 */
function &getInstance()
{
	$c = "Session";//get_class($o);
	if ( !isset($_SESSION[ $c ]) ) 
	{
		$o =& Session::getInstance();
		$_SESSION[ $c ] =& $o;
	}
	return $_SESSION[ $c ];
}

?>
