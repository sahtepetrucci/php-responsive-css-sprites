# PHP Responsive Retina CSS Sprites Generator

A responsive and retina ready CSS sprites generator. Built to be used with Laravel models, but can be used separately as well.

This tool:
- generates a single sprite image based on a (database) collection entries by using **ImageMagick** library, 
- prepares a CSS file (including one unique class per icon),
- [optionally] creates a sample HTML file to demonstrate usage. 

> :raising_hand: Generated sprites will be downscaled to look good on retina displays. So make sure using twice the size for iconWidth and iconHeight (use 64x64 if you want to display them as 32x32 icons). 

Saying that, it is still possible to change CSS width/height values of the icons on the fly while keeping the background image obtained from the sprite. 

Thus, you can define your own @media rules to use same sprites in different sizes if needed.

## Installation
```shell
composer require sahtepetrucci/responsive-retina-css-sprites
```

## Usage
In order this to work, you'll need to provide a collection of objects including id and *icon* fields.

```php
use Sahtepetrucci\SpritesGenerator\SpritesHandler;
$handler = new SpritesHandler();
$handler->generate($collection);
```

## Example 1

```php
$collection = [
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
/* Normal Resolution CSS /*/
.items-spr {
    display:inline-block;vertical-align:middle;
    background-image:url('../images/items-1x.png?t=1592435357');
    background-repeat:no-repeat;
    background-size:200% 100%;
    width:50px;height:50px;
    line-height:50px;
}

/* HD/Retina CSS /*/
@media
only screen and (-webkit-min-device-pixel-ratio: 1.25),only screen and ( min--moz-device-pixel-ratio: 1.25),
only screen and ( -o-min-device-pixel-ratio: 1.25/1),
only screen and ( min-device-pixel-ratio: 1.25),
only screen and ( min-resolution: 200dpi),
only screen and ( min-resolution: 1.25dppx)
{
    .items-spr {
        background-image:url('../images/items-2x.png?t=1592435357');
    }
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


## Example 2 (Generating sprites in Laravel)
```
use Sahtepetrucci\SpritesGenerator\SpritesHandler;
...

$categories = Category::select('id','icon')->get();

$handler = new SpritesHandler();
$handler->name = 'categories';
$handler->inputDir = storage_path('app/public/images/categories');
$handler->outputDir = storage_path('app/public/sprites/categories');
$handler->iconWidth = 64;
$handler->iconHeight = 64;
$handler->generate($categories);
```

### Using sprites in Blade
```html
<link href="{{ asset('storage/sprites/categories/css/categories.css') }}" rel="stylesheet">

@foreach ($categories as $category)
    <i class="categories-spr categories-spr-{{ $category->id }}" title="{{ $category->name }}"></i> 
    {{ $category->name }}
    <br />
@endforeach
```


Sample icons are from [icons8.com](https://icons8.com).
