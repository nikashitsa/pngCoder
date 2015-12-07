<?php

use coder\PngCoder;

class PngCoderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PngCoder
     */
    protected $coder;

    protected function setUp()
    {
        $this->coder = new PngCoder();
    }

    public function testEncode()
    {
        $this->coder->encode('./data/crime.jpg', './tmp/crime.png');
        $this->coder->encode('./data/lorem.txt', './tmp/lorem.png');
        $this->coder->encode('./data/music.mp3', './tmp/music.png');

        $this->assertFileExists('./tmp/crime.png');
        $this->assertFileExists('./tmp/lorem.png');
        $this->assertFileExists('./tmp/music.png');
    }

    public function testDecode()
    {
        $this->coder->decode('./tmp/crime.png', './tmp/crime.jpg');
        $this->coder->decode('./tmp/lorem.png', './tmp/lorem.txt');
        $this->coder->decode('./tmp/music.png', './tmp/music.mp3');

        $this->assertFileEquals('./data/crime.jpg', './tmp/crime.jpg');
        $this->assertFileEquals('./data/lorem.txt', './tmp/lorem.txt');
        $this->assertFileEquals('./data/music.mp3', './tmp/music.mp3');
    }
}