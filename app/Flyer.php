<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flyer extends Model
{
    /**
     * Fillable fields for a flyer
     * @var [type]
     */
    protected $fillable = [
      'street',
      'city',
      'state',
      'country',
      'zip',
      'price',
      'description'
    ];

    /**
     * Find the flyer at the given address
     * @param  String $zip    [description]
     * @param  String $street [description]
     * @return [type]         [description]
     */
    public function locatedAt($zip, $street) {
      $street = str_replace(['-', '+'], ' ', $street);

      return static::where(compact('zip', 'street'))->firstOrFail();
    }

    /**
     * Format Price
     */

    public function getPriceAttribute ($price) {
      return number_format($price) . '€';
    }

    public function addPhoto(Photo $photo) {
      return $this->photos()->save($photo);
    }

    /**
     * A flyer is composed of many photos
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos() {
      return $this->hasMany('App\Photo');
    }
}
