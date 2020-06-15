# PHP Retina CSS Sprites Generator

A quick and dirty CSS sprites generator. Built to be used with Laravel models.

This tool uses **ImageMagick** library to generate a single sprite image based on a (database) collection entries.

In order this to work, you'll need to provide a collection of objects including id and *icon* fields.
***

> :warning: The downscaling (retina) part is not implemented yet.

> :warning: **The package is not tested in production**.

## Usage

```php
use Sprites\SpritesHandler;
$handler = new SpritesHandler();
$handler->generate($collection);
```

#### Examples

```php
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
```

Sample icons are from [icons8.com](https://icons8.com).
