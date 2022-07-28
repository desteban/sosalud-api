<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class formularioController extends Controller
{

    protected $respuesta = array(
        'status'    =>  'succes',
        'code'      =>  200,
        'message'   =>  'Todo ha salido bien',
        'data'      =>  []
    );

    public function store(Request $request)
    {

        // $validator = Validator::make($request->all(), [
        //     ['archivo' => 'required|mimes:rar,zip']
        // ]);

        // if ($validator->fails()) {

        //     return Redirect::to('/')->withErrors($validator);
        // }
        return response()->json($this->respuesta, 200);
    }
}
