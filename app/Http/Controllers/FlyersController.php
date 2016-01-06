<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\FlyerRequest; //import flyer request
use App\Http\Controllers\Controller;
use App\Flyer; // import Flyer model

class FlyersController extends Controller
{
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
