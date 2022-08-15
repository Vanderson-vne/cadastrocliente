<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Indice;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\IndiceFormRequest;
use Illuminate\Support\Facades\DB;


class IndiceController extends Controller
{
   public function __construct(){
    	$this->middleware('auth');
    }

    public function index(Request $request){

        $empresas=DB::table('empresa as emp')
        ->get();

    	if($request){
    		$query=trim($request->get('searchText'));
    		$indices=DB::table('indice')
    		->where('nome', 'LIKE', '%'.$query.'%')
            ->orderBy('idindice','desc')
    		->get();
    		return view('reajuste.indice.index', [
                "indice"=>$indices,
                "empresas"=>$empresas,
                "searchText"=>$query
    			]);
    	}
    }

    public function create(){
        return view("reajuste.indice.create");
    }
 
    public function store(IndiceFormRequest $request){
        $indice = new indice;
        $indice->nome=$request->get('nome');
        $indice->save();
        return Redirect::to('reajuste/indice');
    }


    public function show($id){
    	return view("reajuste/indice.show",
    		["indice"=>indice::findOrFail($id)]);
    }

    public function edit($id){
    	return view("reajuste/indice.edit",
    		["indice"=>indice::findOrFail($id)]);
    }            


    public function update(IndiceFormRequest $request, $id){
        $indice=indice::findOrFail($id);
        $indice->nome=$request->get('nome');
    	$indice->update();
    	return Redirect::to('reajuste/indice');
    }


    public function destroy($id){
    	$indice=Indice::findOrFail($id);
    	$indice->delete();
    	return Redirect::to('reajuste/indice');
    }
}
