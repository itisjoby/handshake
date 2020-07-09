
<?php

class ImageCollageMakers {

    function generateColage() {
        $url1 = base_url('uploads/photos/Doc_2019_06_27_10_36010_.jpg');
        $url2 = base_url('uploads/photos/front_20190609225236_.jpg');
        $new  = imagecreatetruecolor(400, 600); // Create our canvas
// Our static positions...
        $pos  = array(array(0, 0), array(200, 0), array(0, 200), array(200, 200), array(0, 400), array(200, 400));

        $height = 200;
        $width  = 200;

        $white = imagecolorallocate($new, 255, 255, 255); // Gives us a white background
// Now, define the source images
        $src[] = $url1;
        $src[] = $url2;
        $src[] = $url1;
        $src[] = $url2;
        $src[] = $url1;
        $src[] = $url2;
        // $src   = array('images/image1.gif', 'images/image2.gif', 'images/image3.gif', 'images/image4.gif', 'images/image5.gif', 'images/image6.gif');

        foreach ($src as $key => $image) {
            $old = imagecreatefromjpeg($image);
            imagecopymerge($new, $old, $pos[$key][0], $pos[$key][1], 0, 0, $width, $height, 100);
        }

        header('Content-Type: image/gif');
        ImageGIF($new);
    }

}

?>