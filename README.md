# PHP Retina CSS Sprites Generator

A quick and dirty CSS sprites generator. Built to be used with Laravel models.

This tool uses **ImageMagick** library to generate a single sprite image based on a (database) collection entries.

In order this to work, you'll need to provide a collection of objects including id and *icon* fields.

> :warning: The downscaling part is not implemented yet.

## Usage

```php
use Sprites\SpritesHandler;
$handler = new SpritesHandler();
$handler->generate($collection);
```

## Examples

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

## Output

**CSS**

```css
.sample-items-sprite {
    display: inline-block;
    background-image: url('../images/sample-items.png');
    background-repeat: no-repeat;
    width: 100px;
    height: 100px;
}

.sample-items-sprite-1 { background-position: 0px 0px }
.sample-items-sprite-2 { background-position: -100px 0px }
```

**HTML** (Optional)

```html
<html>
<head>
    
<link rel="stylesheet" href="css/sample-items.css"></head><body>

<div class="sample-items-sprite sample-items-sprite-1" title="Item"></div>
<div class="sample-items-sprite sample-items-sprite-2" title="Another Item"></div>

</body></html>
```

Sample icons are from [icons8.com](https://icons8.com).
