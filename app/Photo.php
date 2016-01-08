<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Image; //Image Facade | Intervention

class Photo extends Model
{
  protected $table = 'flyer_photos';

  protected $fillable = ['path', 'name', 'thumbnail_path'];

  protected $baseDir = 'flyers_uploads/photos';

  public function flyer() {
    return $this->belongsTo('App\Flyer');
  }

  /**
   * Build a news photo instance from a file upload.
   */

  public static function named($name) {
    return (new static)->saveAs($name);
  }

  public function saveAs($name) {
    $this->name = sprintf("%s-%s", time(), $name);
    $this->path = sprintf("%s/%s", $this->baseDir, $this->name);
    $this->thumbnail_path = sprintf("%s/tn-%s", $this->baseDir, $this->name);

    return $this;
  }

  public function move(UploadedFile $file) {
    $file->move($this->baseDir, $this->name);

    $this->makeThumbail();

    return $this;
  }

  public function makeThumbail() {
    Image::make($this->path)
      ->fit(200)
      ->save($this->thumbnail_path);
  }
}
