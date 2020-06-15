<?php

namespace Sprites;

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
        $this->name = "sample-items";

        $this->inputDir = "samples/input";
        $this->outputDir = "samples/output";
        $this->iconWidth = 100;
        $this->iconHeight = 100;
        $this->itemsPerRow = 10;
    }

    public function checkExistance($collection)
    {
        foreach ($collection as $item):
            $path = __DIR__.'/../' . $this->inputDir . '/' . $item->icon;
            if (!file_exists($path)) {
                throw new \Exception ('File not found. Check the path: ' . $path);
            }
        endforeach;
    }

    public function combine($collection)
    {
        $dir = __DIR__.'/../' . $this->inputDir;
        $stack = new \Imagick();
        $stack->setBackgroundColor(new \ImagickPixel('transparent'));

        foreach ($collection as $item):
            $stack->addImage(new \Imagick($dir . '/' . $item->icon));
        endforeach;

        return $stack;
    } 

    /**
     * Generate a sprite by using icons in a collection
     * @param object $collection
     * @return bool (true if successful)
     */

    public function generate($collection) {
        $dir = __DIR__.'/../' . $this->outputDir . '/images';
        $this->makeDir($dir);

        $this->checkExistance($collection);
        $stack = $this->combine($collection);

        $this->itemCount = count((array) $collection);
        $this->columnCount = $this->itemCount >= $this->itemsPerRow ? $this->itemsPerRow : $this->itemCount;
        $this->rowCount = ceil($this->itemCount / $this->itemsPerRow);

        $imageDimensions = $this->iconWidth . "x" . $this->iconHeight;
        $imageGrid = $this->columnCount."x".$this->rowCount;

        $montage = $stack->montageImage(new \ImagickDraw(), $imageGrid, $imageDimensions, 0, 0);
        $montage->writeImage($dir . '/' . $this->name . '.png');

        $this->writeCss($collection);

    }

    public function writeCss($collection) {
        $dir = __DIR__.'/../' . $this->outputDir . '/css';
        $this->makeDir($dir);

        $cssContent = "." . $this->name . "-sprite {\n";
        $cssContent.= "display:inline-block;background-image: url('../images/" . $this->name . ".png');\nbackground-repeat:no-repeat;\n";

        $cssContent.="width:" . $this->iconWidth . "px;height:" . $this->iconHeight . "px;";
        $cssContent.="\n}\n";

        $i = 0;
        foreach ($collection as $item):
            $i++;
            $className = "." . $this->name . '-sprite-' . $item->id;
            $cssContent.="\n" . $className . "{background-position:" . $this->getPosition($i) . "}";
        endforeach;

        file_put_contents($dir . '/' . $this->name . '.css', $cssContent);

    }

    public function makeDir($path)
    {
         return is_dir($path) || mkdir($path);
    }

    public function getPosition($i) {
        $itemColumn = $i % $this->itemsPerRow; 
        if ($itemColumn == 0) { 
            $itemColumn = $this->itemsPerRow; 
        }
        $itemRow = ceil($i / $this->itemsPerRow);

        $itemPositionX = 0 - ($itemColumn-1)*$this->iconWidth;
        $itemPositionY = 0 - ($itemRow-1)*$this->iconHeight;

        return $itemPositionX . 'px ' . $itemPositionY . 'px';
    }

    public function createSampleHtml($collection) {
        $dir = __DIR__.'/../' . $this->outputDir;
        $content = "<html>\n<head>\n";
        $content.= "<link rel=\"stylesheet\" href=\"css/" . $this->name . ".css\">";
        $content.= "</head><body>\n\n";


        foreach ($collection as $item):
            $className = $this->name . "-sprite " . $this->name . '-sprite-' . $item->id;
            $content.= "<div class=\"" .$className . "\" title=\"" . $item->name . "\"></div>\n"; 
        endforeach;

        $content.= "\n\n</body></html>";
        file_put_contents($dir . '/' . $this->name . '.html', $content);
    }

}