<?php

use PHPUnit\Framework\TestCase;

class GenericTest extends TestCase
{
    protected $handler;

    public function setUp(): void
    {
        $this->handler = new Sahtepetrucci\SpritesGenerator\Handler();
    }

    public function testResizingImages()
    {
        //TODO: write resizing test
    }
}