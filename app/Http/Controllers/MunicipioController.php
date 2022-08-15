<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Municipio;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\MunicipioFormRequest;
use Illuminate\Support\Facades\DB;

class MunicipioController extends Controller
{
    public function __Construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){

    	if($request){
    		$query=trim($request->get('searchText'));
    		$municipios=DB::table('municipio')
    		->where('nome','LIKE', '%'.$query.'%')
    		->orderBy('idmunicipio','desc')
    		->get();
    		return view('tabela.municipio.index', [
    			"municipio"=>$municipios, "searchText"=>$query
    		]);

    	}
    }

    public function create(){
        return view("tabela.municipio.create");
    }
 
    public function store(MunicipioFormRequest $request){
        $municipio = new municipio;
        $municipio->cep=$request->get('cep');
        $municipio->nome=$request->get('nome');
        $municipio->bairro=$request->get('bairro');
        $municipio->localidade=$request->get('localidade');
        $municipio->uf=$request->get('uf');
        $municipio->save();
        return Redirect::to('tabela/municipio');
    }


    public function show($id){
    	return view("tabela/municipio.show",
    		["municipio"=>municipio::findOrFail($id)]);
    }

    public function edit($id){
    	return view("tabela/municipio.edit",
    		["municipio"=>municipio::findOrFail($id)]);
    }            


    public function update(MunicipioFormRequest $request, $id){
    	$municipio=municipio::findOrFail($id);
        $municipio->cep=$request->get('cep');
        $municipio->nome=$request->get('nome');
        $municipio->bairro=$request->get('bairro');
        $municipio->localidade=$request->get('localidade');
        $municipio->uf=$request->get('uf');
    	$municipio->update();
    	return Redirect::to('tabela/municipio');
    }


    public function destroy($id){
    	$municipio=municipio::findOrFail($id);
    	$municipio->delete();
    	return Redirect::to('tabela/municipio');
    }    
}
