<?php

namespace Sahtepetrucci\SpritesGenerator;

class SpritesHandler {

    public $inputDir;
    public $outputDir;
    public $iconWidth;
    public $iconHeight;
    public $itemsPerRow;

    public $itemCount;
    public $columnCount;
    public $rowCount;

    public function __construct() {
        $this->name = "items";
        $this->keyword = "spr";

        $this->inputDir = __DIR__ . '/../samples/input';
        $this->outputDir = __DIR__ . '/../samples/output';
        $this->iconWidth = 100;
        $this->iconHeight = 100;
        $this->itemsPerRow = 10;
    }

    /**
     * Throws an exception in case any file in the collection cannot be found in the filesystem 
     * @param object $collection
     * @return void
     */
    public function checkExistance($collection)
    {
        foreach ($collection as $item):
            $path = $this->inputDir . '/' . $item->icon;
            if (!file_exists($path)) {
                error_log('File not found. Check the path: ' . $path . ' for item with id = ' . $item->id);
            }
        endforeach;
    }

    /**
     * Combines a collection's icons into an Imagick stack
     * @param object $collection
     * @return object $stack
     */
    public function combine($collection)
    {
        $stack = new \Imagick();
        $stack->setBackgroundColor(new \ImagickPixel('transparent'));

        foreach ($collection as $item):
            if (!$item->icon || !file_exists($this->inputDir . '/' . $item->icon)) {
                continue;
            }
            
            $icon = new \Imagick($this->inputDir . '/' . $item->icon);
            $icon->stripImage();

            $width = $icon->getImageWidth ();
            $height = $icon->getImageHeight ();

            if ($width != $this->iconWidth or $height != $this->iconHeight) {
                //$icon->scaleImage($this->iconWidth, $this->iconHeight, true);
                $icon->resizeImage($this->iconWidth, $this->iconHeight, \Imagick::FILTER_BOX, 1); 
            }

            $stack->addImage($tempIcon ?? $icon);

        endforeach;

        return $stack;
    } 

    /**
     * Generates a sprite by using icons in a collection
     * @param object $collection
     * @return bool (true if successful)
     */
    public function generate($collection) {
        $dir = $this->outputDir . '/images';
        $this->makeDir($dir);

        $this->checkExistance($collection);
        $stack = $this->combine($collection);

        $this->itemCount = is_countable($collection) ? count($collection) : count((array) $collection);
        $this->columnCount = $this->itemCount >= $this->itemsPerRow ? $this->itemsPerRow : $this->itemCount;
        $this->rowCount = ceil($this->itemCount / $this->itemsPerRow);

        $imageDimensions = $this->iconWidth . "x" . $this->iconHeight;
        $imageGrid = $this->columnCount."x".$this->rowCount;

        $montage = $stack->montageImage(new \ImagickDraw(), $imageGrid, $imageDimensions, 0, 0);
        $montage->writeImage($dir . '/' . $this->name . '-2x.png');

        $bigImage = new \Imagick($dir . '/' . $this->name . '-2x.png');
        $smallImage = clone $bigImage;
        $smallImage->resizeImage(
            $bigImage->getImageWidth()/2, 
            $bigImage->getImageHeight()/2, \Imagick::FILTER_BOX, 1, true
        );
        $smallImage->writeImage($dir . '/' . $this->name . '-1x.png');

        $this->writeCss($collection);
    }

    /**
     * Writes down the CSS needed by the sprite elements
     * @param object $collection
     * @return void
     */
    public function writeCss($collection) {
        $dir = $this->outputDir . '/css';
        $this->makeDir($dir);

        $backgroundSizeX = $this->columnCount * 100;
        $backgroundSizeY = $this->rowCount * 100;

        // Here we got some help from: https://coderwall.com/p/jlrerg/hd-retina-display-media-queries

        $cssContent = "/* Normal Resolution CSS /*/\n";
        $cssContent.= "." . $this->name . "-" . $this->keyword . " {\n";
        $cssContent.= "\tdisplay:inline-block;vertical-align:middle;\n";
        $cssContent.= "\tbackground-image:url('../images/" . $this->name . "-1x.png?t=" . time() . "');\n";
        $cssContent.= "\tbackground-repeat:no-repeat;\n";
        $cssContent.= "\tbackground-size:" . $backgroundSizeX . "% " . $backgroundSizeY  ."%;\n";
        $cssContent.="\twidth:" . $this->iconWidth/2 . "px;height:" . $this->iconHeight/2 . "px;\n";        
        $cssContent.="\tline-height:" . $this->iconHeight/2 . "px;";
        $cssContent.="\n}\n\n";

        $cssContent.= "/* HD/Retina CSS /*/\n";
        $cssContent.= "@media\n";
        $cssContent.="only screen and (-webkit-min-device-pixel-ratio: 1.25),";
        $cssContent.="only screen and ( min--moz-device-pixel-ratio: 1.25),\n";
        $cssContent.="only screen and ( -o-min-device-pixel-ratio: 1.25/1),\n";
        $cssContent.="only screen and ( min-device-pixel-ratio: 1.25),\n";
        $cssContent.="only screen and ( min-resolution: 200dpi),\n";
        $cssContent.="only screen and ( min-resolution: 1.25dppx)\n";
        $cssContent.="{\n";
        $cssContent.= "    ." . $this->name . "-" . $this->keyword . " {\n";
        $cssContent.= "       \tbackground-image:url('../images/" . $this->name . "-2x.png?t=" . time() . "');\n";
        $cssContent.= "    }\n";
        $cssContent.="}\n";

        $i = 0;
        foreach ($collection as $item):
            $i++;
            $className = "." . $this->name . '-' . $this->keyword . '-' . $item->id;
            $cssContent.="\n" . $className . "{background-position:" . $this->getPosition($i) . "}";
        endforeach;

        $cssContent.= "\n\n/* A total of " . $i . " images are combined here. */";

        file_put_contents($dir . '/' . $this->name . '.css', $cssContent);

    }

    /**
     * Checks whether a dir exists, create it if not
     * @param string $path
     * @return bool
     */
    public function makeDir($path)
    {
        return is_dir($path) || mkdir($path, 0775, true);
    }

    /**
     * Gets percentage position of element $i within the sprite map
     * @param int $i
     * @param bool $inPixels
     * @return string
     */
    public function getPosition($i, $inPixels = false) {
        $itemColumn = $i % $this->itemsPerRow; 
        if ($itemColumn == 0) { 
            $itemColumn = $this->itemsPerRow; 
        }
        $itemRow = ceil($i / $this->itemsPerRow);

        $itemPositionX = 0 - ($itemColumn-1)*$this->iconWidth;
        $itemPositionY = 0 - ($itemRow-1)*$this->iconHeight;

        if ($inPixels) {
            return $itemPositionX . 'px ' . $itemPositionY . 'px';
        }

        $fullWidth = $this->iconWidth * $this->columnCount;
        $fullHeight = $this->iconHeight * $this->rowCount;

        //I've applied the solution mentioned here: https://stackoverflow.com/a/23419418/8868758
        $dividerX = $fullWidth - $this->iconWidth ? $fullWidth - $this->iconWidth : 1;
        $dividerY = $fullHeight - $this->iconHeight ? $fullHeight - $this->iconHeight : 1;

        $itemPositionXPerc = (abs($itemPositionX) / $dividerX) * 100;
        $itemPositionYPerc = (abs($itemPositionY) / $dividerY) * 100;

        return $itemPositionXPerc . '% ' . $itemPositionYPerc . '%';

    }

    /**
     * Generates sample HTML to showcase sprites
     * @param object $collection
     * @return void
     */
    public function createSampleHtml($collection) {
        $dir = $this->outputDir;
        $content = "<html>\n<head>\n";
        $content.= "<link rel=\"stylesheet\" href=\"css/" . $this->name . ".css\">";
        $content.= "</head><body>\n\n";

        foreach ($collection as $item):
            $className = $this->name.'-'.$this->keyword.' '.$this->name.'-'.$this->keyword.'-' . $item->id;
            $content.= "<i class=\"" .$className . "\" title=\"" . $item->name . "\"></i>\n"; 
        endforeach;

        $content.="<br /><br />\n\n";

        foreach ($collection as $item):
            $className = $this->name.'-'.$this->keyword.' '.$this->name.'-'.$this->keyword.'-' . $item->id;
            $style = "width:" . $this->iconWidth/4 . "px;height:" . $this->iconHeight/4 . "px";
            $content.= "<i style=\"" . $style . "\" class=\"" .$className . "\" title=\"" . $item->name . "\"></i>\n"; 
        endforeach;

        $content.= "\n</body></html>";
        file_put_contents($dir . '/' . $this->name . '.html', $content);
    }

}