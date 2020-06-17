<?php
require_once('init.php');

use Sahtepetrucci\SpritesGenerator\SpritesHandler;
$handler = new SpritesHandler();

$collection = (object)[
    (object)[
        'id' => 1,
        'name' => 'Item',
        'icon' => 'icons8-airport-100.png'
    ],
    (object)[
        'id' => 2,
        'name' => 'Another Item',
        'icon' => 'icons8-bus-100.png'
    ],
];

$handler->generate($collection);
$handler->createSampleHtml($collection); //optional

echo "Done! See the folder: " . $handler->outputDir."\n";
