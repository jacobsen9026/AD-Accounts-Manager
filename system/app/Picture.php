<?php

/*
 * The MIT License
 *
 * Copyright 2020 cjacobsen.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace System\App;

/**
 * Description of Picture
 *
 * @author cjacobsen
 */
abstract class Picture
{

    public static function cropSquare($imageResource, $targetDimension)
    {


        $imageWidth = imagesx($imageResource);
        $imageHeight = imagesy($imageResource);


        if ($imageWidth > $imageHeight) {

            $newWidth = ((($targetDimension + 1) / $imageHeight) * $imageWidth) + 1;
        } else {
            $newWidth = $targetDimension + 1;
        }

        $imageResource = imagescale($imageResource, $newWidth);


        $imageWidth = imagesx($imageResource);
        $imageHeight = imagesy($imageResource);


        $xOffset = ($imageWidth - $targetDimension) / 2;
        $yOffset = ($imageHeight - $targetDimension) / 2;
        $rect = ['x' => $xOffset, 'y' => $yOffset, 'width' => $targetDimension, 'height' => $targetDimension];
        $rect = ['x' => $xOffset, 'y' => 0, 'width' => $targetDimension, 'height' => $targetDimension];
        // var_dump($rect);
        $imageResource = imagecrop($imageResource, $rect);

        ob_start();
        imagejpeg($imageResource);
        $rawPicture = ob_get_clean();
        return $rawPicture;
    }

    public static function resize($image, string $newWidth, string $newHeight)
    {
        if (is_string($image)) {
            $image = imagecreatefromstring($image);
        }
        $newImg = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($newImg, false);
        imagesavealpha($newImg, true);
        $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
        imagefilledrectangle($newImg, 0, 0, $newWidth, $newHeight, $transparent);
        $src_w = imagesx($image);
        $src_h = imagesy($image);
        imagecopyresampled($newImg, $image, 0, 0, 0, 0, $newWidth, $newHeight, $src_w, $src_h);
        ob_start();
        imagepng($newImg);
        $rawPicture = ob_get_clean();
        return $rawPicture;

    }

}
