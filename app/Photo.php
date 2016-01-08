<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Image; //Image Facade | Intervention

class Photo extends Model
{
  protected $table = 'flyer_photos';

  protected $fillable = ['path', 'name', 'thumbnail_path'];

  protected $file;

  /**
   * When a photo is created, prepare a thumbnail, too.
   * @return void
   */
  protected static function boot() {
    static::creating(function ($photo) {
      return $photo->upload();
    });
  }

  /**
   * A photo belongs to a flyer.
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function flyer() {
    return $this->belongsTo('App\Flyer');
  }

  public static function fromFile(UploadedFile $file) {
    $photo = new static;

    $photo->file = $file;

    return $photo->fill([
      'name' => $photo->fileName(),
      'path' => $photo->filePath(),
      'thumbnail_path' => $photo->thumbnailPath()
    ]);
  }

  /**
   * Get the base directory for photo uploads
   * @return string§
   */
  public function baseDir() {
    return 'flyers_uploads/photos';
  }

  /**
   * Get the file name for the photo
   * @return string
   */
  public function fileName() {
    $name = sha1(
      time() . $this->file->getClientOriginalName()
    );

    $extension = $this->file->getClientOriginalExtension();

    return "{$name}.{$extension}";
  }

  /**
   * Get the path to the photo.
   * @return string
   */
  public function filePath() {
    return $this->baseDir() . '/' . $this->fileName();
  }

  /**
   * Get the path to the photo´s thumbnail.
   * @return string
   */
  public function thumbnailPath() {
    return $this->baseDir() . '/tn-' . $this->fileName();
  }


  /**
   * Move the photo to the proper folder.
   * @return self
   */
  public function upload() {
    $this->file->move($this->baseDir(), $this->fileName());

    $this->makeThumbail();

    return $this;
  }

  /**
   * Create a thumbnail for the photo.
   * @return void
   */
  public function makeThumbail() {
    Image::make($this->filePath())
      ->fit(200)
      ->save($this->thumbnailPath());
  }
}
