<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CEO;
use App\Http\Resources\CEOResource;
use Illuminate\Support\Facades\Validator;


class CEOcontroller extends Controller
{
    public function index(){
        $ceos = CEO::all();
        return response(['ceos' => CEOResource::collection($ceos), 'message' => 'retrived successfully'], 200);
    }

    public function store(Request $request){

        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'year' => 'required|max:255',
            'company_headquaters' => 'required|max:255',
            'what_company_does' => 'required'
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'validation Error']);
        }

        $ceo = CEO::create($data);
        return response(['ceo' => new CEOResource($ceo), 'message' => 'created successfully'], 200);
    }

    public function show(CEO $ceo){
        return response(['ceo' => new CEOResource($ceo), 'message' => 'retrived successfully']);
    }

    public function update(Request $request, CEO $ceo){
        $ceo->update($request->all());

        return response(['ceo' => new CEOResource($ceo), 'message' => 'retrived successfully']);
    }

    public function destroy(CEO $ceo){
        $ceo->delete();
        ([ 'message' => 'Deleted successfully']);
    }
}
