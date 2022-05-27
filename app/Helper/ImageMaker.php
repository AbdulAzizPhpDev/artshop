<?php

namespace App\Helper;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class ImageMaker
{
    private $width, $height, $name = null, $path = '/default/';

    public function __construct($width = 736, $height = 1000)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function makeImage($image, $path = null, $name = null)
    {

        if ($path) {
//            $this->path = 'products/' . $product->getCategoryByProduct()->get()[0]->slug . '/' . $product->slug . '/';
//            $this->name = date_format($product->created_at, 'Y_m_d_H_i_s') . '_product_' . $product->id . '.jpg';
            $this->path = $path;
            $this->name = $name;
        } else {
            $this->name = date('Y_m_d_H_i_s') . '.jpg';
        }

        if (!Storage::disk('public')->exists($this->path)) {
            Storage::disk('public')->makeDirectory($this->path);
        }
        list($width, $height, $type, $attr) = getimagesize($image);
        if ($width < $this->width && $height < $this->height) {
            $img = Image::canvas($this->width, $this->height, '#fff');
            $img->insert(Image::make($image), 'center');
            $img->save('storage/' . $this->path . $this->name);
            return $this->path . $this->name;
        } elseif ($width > $this->width && $height <= $this->height) {
            $img = Image::canvas($this->width, $this->height, '#fff');
            $img->insert(Image::make($image)->resize($this->width, null), 'center');
            $img->save('storage/' . $this->path . $this->name);
            return $this->path . $this->name;

        } elseif ($width < $this->width && $height > $this->height) {
            $img = Image::canvas($this->width, $this->height, '#fff');
            $img->insert(Image::make($image)->resize(null, $this->height), 'center');
            $img->save('storage/' . $this->path . $this->name);
            return $this->path . $this->name;

        } else {

            $img = Image::make($image)->resize($this->width, $this->height);
            $img->save('storage/' . $this->path . $this->name);

            return $this->path . $this->name;
        }

    }
}
