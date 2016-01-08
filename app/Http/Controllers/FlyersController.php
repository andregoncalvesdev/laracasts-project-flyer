<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Flyer; // import Flyer model
use App\Photo; // import Photo model
use App\Http\Requests\FlyerRequest; //import flyer request
use App\Http\Requests\ChangeFlyerRequest; //import change flyer request
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FlyersController extends Controller
{
  public function __construct() {
    $this->middleware('auth', ['except' => ['show']]);

    parent::__construct();
  }

  public function show($zip, $street) {
    $flyer = Flyer::locatedAt($zip, $street);

    return view('flyers.show', compact('flyer'));
  }

  /**
   * Apply a photo to the referenced flyer
   * @param string             $zip
   * @param string             $street
   * @param ChangeFlyerRequest $request
   */
  public function addPhoto($zip, $street, ChangeFlyerRequest $request) {

    $photo = Photo::fromFile($request->file('photo'));

    Flyer::locatedAt($zip, $street)->addPhoto($photo);
  }

  public function create() {
    return view('flyers.create');
  }

  public function store(FlyerRequest $request) {
    //persist the flyer
    Flyer::create($request->all());

    //flash messaging
    flash()->success('Success', 'Flyer was inserted with success!');

    //redirect to landing page
    return redirect()->back();
  }
}
