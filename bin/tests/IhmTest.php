<?php

include_once(BINPATH . 'Ihm.php');
include_once(BINPATH . 'Buffer.php');

class IhmTest extends PHPUnit_Framework_TestCase
{

	public $ihm, $b;

	public function setUp()
	{
		$this->b = new Buffer();
		$this->ihm = new Ihm($this->b);

		$this->b->attach($this->ihm);
		$this->ihm->attach($this->b);
	}

	public function tearDown()
	{
	}

	public function testConstructeur()
	{
		$ihm = new Ihm($this->b);
		$this->assertTrue($this->ihm !== null);
		$this->assertNotSame($this->ihm, $ihm);
	}

	public function testgetChar(){
		$this->assertEquals("#", $this->ihm->getChar());
	}

	public function testSetChar(){
		$this->ihm->setChar("H");
		$this->assertEquals("H", $this->ihm->getChar());
	}

	public function testgetText(){
		$this->assertEquals("", $this->ihm->getText());
	}

	public function testSetText(){
		$this->ihm->setText("Hello");
		$this->assertEquals("Hello", $this->ihm->getText());
	}

	public function testgetSelectionStart(){
		$this->assertEquals(0, $this->ihm->getSelectionStart());

		$this->ihm->setSelectionStart(2);
		$this->assertEquals(2, $this->ihm->getSelectionStart());

	}

	public function testgetSelectionEnd(){
		$this->assertEquals(0, $this->ihm->getSelectionEnd());

		$this->ihm->setSelectionEnd(2);
		$this->assertEquals(2, $this->ihm->getSelectionEnd());

	}

	public function testupdateSelection()
	{
		// execute
		$this->ihm->updateSelection(2, 5);

		// buffer
		$this->assertEquals(2, $this->b->getSelectionStart());
		$this->assertEquals(5, $this->b->getSelectionEnd());

		// ihm
		$this->assertEquals(2, $this->ihm->getSelectionStart());
		$this->assertEquals(5, $this->ihm->getSelectionEnd());

		// execute
		$this->ihm->updateSelection(2, 2);

		// buffer
		$this->assertEquals(2, $this->b->getSelectionStart());
		$this->assertEquals(2, $this->b->getSelectionEnd());

		// ihm
		$this->assertEquals(2, $this->ihm->getSelectionStart());
		$this->assertEquals(2, $this->ihm->getSelectionEnd());


	}

	public function testinsert(){
		$this->ihm->setChar('H');
		$this->ihm->insert();
		$this->assertEquals("H", $this->b->getText());

		$this->ihm->setChar('e');
		$this->ihm->insert();
		$this->assertEquals("He", $this->b->getText());

		$this->ihm->setChar('l');
		$this->ihm->insert();
		$this->assertEquals("Hel", $this->b->getText());

		$this->ihm->setChar('l');
		$this->ihm->insert();
		$this->assertEquals("Hell", $this->b->getText());

		$this->ihm->setChar('o');
		$this->ihm->insert();
		$this->assertEquals("Hello", $this->b->getText());

	}

	public function testcopy(){

		$this->ihm->setText("Hello");
		$this->ihm->setSelectionStart(0);
		$this->ihm->setSelectionEnd(3);

		$this->b->setText("Hello");
		$this->b->setSelectionStart(0);
		$this->b->setSelectionEnd(3);

		$this->ihm->copy();

		// buffer
		$this->assertEquals(0, $this->b->getSelectionStart());
		$this->assertEquals(3, $this->b->getSelectionEnd());
		$this->assertEquals("Hel", $this->b->getTextFromClipBoard());
		$this->assertEquals("Hello", $this->b->getText());

		// ihm
		$this->assertEquals("Hello", $this->ihm->getText());

	}

	public function testcut(){

		$this->ihm->setText("Hello");
		$this->ihm->setSelectionStart(0);
		$this->ihm->setSelectionEnd(3);
		$this->b->setText("Hello");
		$this->b->setSelectionStart(0);
		$this->b->setSelectionEnd(3);

		$this->ihm->cut();

		// buffer
		$this->assertEquals(0, $this->b->getSelectionStart());
		$this->assertEquals(0, $this->b->getSelectionEnd());
		$this->assertEquals("Hel", $this->b->getTextFromClipBoard());
		$this->assertEquals("lo", $this->b->getText());

		// ihm
		$this->assertEquals(0, $this->ihm->getSelectionStart());
		$this->assertEquals(0, $this->ihm->getSelectionEnd());
		$this->assertEquals("lo", $this->ihm->getText());

	}

	public function testpaste(){

		// init
		$this->ihm->setText("Hello");
		$this->ihm->setSelectionStart(0);
		$this->ihm->setSelectionEnd(3);
		$this->b->setText("Hello");
		$this->b->setSelectionStart(0);
		$this->b->setSelectionEnd(3);

		// execute
		$this->ihm->copy();

		$this->b->setSelectionStart(4);
		$this->b->setSelectionEnd(4);

		$this->ihm->setSelectionStart(4);
		$this->ihm->setSelectionEnd(4);

		$this->ihm->paste();

		// buffer
		$this->assertEquals(7, $this->b->getSelectionStart());
		$this->assertEquals(7, $this->b->getSelectionEnd());
		$this->assertEquals("Hel", $this->b->getTextFromClipBoard());
		$this->assertEquals("HellHelo", $this->b->getText());

		// ihm
		$this->assertEquals(7, $this->ihm->getSelectionStart());
		$this->assertEquals(7, $this->ihm->getSelectionEnd());
		$this->assertEquals("HellHelo", $this->ihm->getText());


		// init
		$this->ihm->setText("Hello");
		$this->ihm->setSelectionStart(2);
		$this->ihm->setSelectionEnd(3);
		$this->b->setText("Hello");
		$this->b->setSelectionStart(2);
		$this->b->setSelectionEnd(3);

		$this->b->setTextIntoClipBoard("###");
		$this->ihm->paste();

		// buffer
		$this->assertEquals(5, $this->b->getSelectionStart());
		$this->assertEquals(5, $this->b->getSelectionEnd());
		$this->assertEquals("###", $this->b->getTextFromClipBoard());
		$this->assertEquals("He###lo", $this->b->getText());

		// ihm
		$this->assertEquals(5, $this->ihm->getSelectionStart());
		$this->assertEquals(5, $this->ihm->getSelectionEnd());
		$this->assertEquals("He###lo", $this->ihm->getText());


	}

}
?>
