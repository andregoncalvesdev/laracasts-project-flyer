<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\FlyerRequest; //import flyer request
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Flyer; // import Flyer model
use App\Photo; // import Photo model

class FlyersController extends Controller
{
  public function __construct() {
    // $this->middleware('auth', ['except' => ['show']]);
    $this->middleware('auth');
  }

  public function show($zip, $street) {
    $flyer = Flyer::locatedAt($zip, $street);

    return view('flyers.show', compact('flyer'));
  }

  public function addPhoto($zip, $street, Request $request) {
    $this->validate($request, [
      'photo' => 'required|mimes:jpg,jpeg,png,bmp'
    ]);

    $photo = $this->makePhoto($request->file('photo'));

    Flyer::locatedAt($zip, $street)->addPhoto($photo);

    return 'Done';
  }

  protected function makePhoto (UploadedFile $file) {
    return Photo::named($file->getClientOriginalName())
      ->move($file);
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
