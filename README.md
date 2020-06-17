# PHP Responsive Retina CSS Sprites Generator

A responsive and retina ready CSS sprites generator. Built to be used with Laravel models, but can be used separately as well.

This tool:
- generates a single sprite image based on a (database) collection entries by using **ImageMagick** library, 
- prepares a CSS file (including one unique class per icon),
- [optionally] creates a sample HTML file to demonstrate usage. 


It is possible to change CSS width/height values of the icons while keeping the background image obtained from the sprite. 

## Usage
In order this to work, you'll need to provide a collection of objects including id and *icon* fields.

```php
use Sahtepetrucci\SpritesGenerator\SpritesHandler;
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
.items-spr {
	display:inline-block;vertical-align:middle;
	background-image:url('../images/items.png');
	background-repeat:no-repeat;
	background-size:200% 100%;
	width:50px;height:50px;
	line-height:50px;
}

.items-spr-1{background-position:0% 0%}
.items-spr-2{background-position:100% 0%}

/* A total of 2 images are combined here. */
```

**HTML** (Optional)

```html
<html>
<head>
<link rel="stylesheet" href="css/items.css"></head><body>

<i class="items-spr items-spr-1" title="Item"></i>
<i class="items-spr items-spr-2" title="Another Item"></i>
<br /><br />

<i style="width:25px;height:25px" class="items-spr items-spr-1" title="Item"></i>
<i style="width:25px;height:25px" class="items-spr items-spr-2" title="Another Item"></i>

</body></html>
```

Sample icons are from [icons8.com](https://icons8.com).
