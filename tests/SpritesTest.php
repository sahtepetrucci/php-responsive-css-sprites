<?php
namespace Sprites\Tests;

use Sprites\SpritesHandler;
use PHPUnit\Framework\TestCase;

class SpritesTest extends TestCase
{
    protected $handler;

    public function setUp(): void
    {
        $this->handler = new SpritesHandler();
    }

    public function testResizingImages()
    {
        //TODO: write resizing test
    }
}