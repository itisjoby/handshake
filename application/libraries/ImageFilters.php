<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(FCPATH . 'vendor\autoload.php');

use Jaguar\Canvas,
    Jaguar\Transformation,
    Jaguar\Action\Color\Grayscale,
    Jaguar\Action\EdgeDetection,
    Jaguar\Action\Color\Boost,
    Jaguar\Action\Blur\SelectiveBlur,
    Jaguar\Action\Posterize,
    Jaguar\Action\Color\Negate;

class ImageFilters {

    public $ci;

    public function __construct() {
        $this->ci = &get_instance();
    }

    function execute($image) {
        $this->ColoredCartoonFilter($image);
    }

    function PencilFilter($image) {

        $canvas = new Canvas($image);
        $canvasTransformation = new Transformation($canvas);
        $canvasTransformation
                ->apply(new Grayscale())
                ->apply(new EdgeDetection(EdgeDetection::LAPLACIAN_FILTER3))
                ->apply(new Negate())
                ->getCanvas()
                ->show(); // send the result to the browser
    }

    function ColoredCartoonFilter($image) {

        $canvas = new Canvas($image);
        $canvasTransformation = new Transformation($canvas);
        $canvasTransformation
                //->apply(new EdgeDetection(EdgeDetection::FINDEDGE))
                ->apply(new Boost())
                ->apply(new SelectiveBlur())
                //->apply(new Posterize(64))
                ->getCanvas()
                ->show(); // send the result to the browser
    }

    function BoostedFilter($image) {

        $canvas = new Canvas($image);
        $canvasTransformation = new Transformation($canvas);
        $canvasTransformation
                ->apply(new Boost())
                ->getCanvas()
                ->show(); // send the result to the browser
    }

    function TextonImage() {
        $image = new Imagick($up_back_file);
        $draw = new ImagickDraw();
        /* Black text */
        $draw->setFillColor('black');

        $draw->setGravity(Imagick::GRAVITY_CENTER);
        /* Font properties */
        //$draw->setFont('Bookman-DemiItalic');
        //$draw->setFillColor('#ffff00'); 
        //$draw->setTextUnderColor('#ff000088'); 
        $draw->setFontSize(30);

        /* Create text */
        $image->annotateImage($draw, 10, 45, 0, 'The quick brown fox jumps over the lazy dog');
    }
    
    function MergedImages(){
        /* Create new imagick object */
$im = new Imagick();

/* create red, green and blue images */
$im->newImage(100, 50, "red");
$im->newImage(100, 50, "green");
$im->newImage(100, 50, "blue");

/* Append the images into one */
$im->resetIterator();
$combined = $im->appendImages(true);

/* Output the image */
$combined->setImageFormat("png");
header("Content-Type: image/png");
echo $combined;
    }
    //forces all pixels below the threshold into black while leaving all pixels above the threshold unchanged.
    function BlackThreshould(){
        $imagick->blackthresholdimage($thresholdColor);
        
    }
    function nightSimulate(){
        $image->blueShiftImage();
    }
function bluring(){
    $image->motionBlurImage(2,9,0);
    //$image->enhanceImage (  );
}
function Border(){
    $image->borderImage('red', '10', '10');
} 
function borderThreeD(){
       $image->frameimage(
        'red',
        20,
        20,
        10,
        10
    );
}
function flipImage(){
    $image->flipImage();
    //sideways belo
    $image->transverseImage();
    $image->transposeImage();
}
function wavefilter(){
    $image->waveImage(2,40);
}
//lot of image as columns and rows
function textureImage()
{
     $image = new \Imagick();
    $image->newImage(640, 480, new \ImagickPixel('pink'));
    $image->setImageFormat("jpg");
    $texture = new \Imagick(realpath($imagePath));
    $texture->scaleimage($image->getimagewidth() / 4, $image->getimageheight() / 4);
    $image = $image->textureImage($texture);
    header("Content-Type: image/jpg");
    echo $image;
}

//https://phpimagick.com/Imagick/annotateImage
}

