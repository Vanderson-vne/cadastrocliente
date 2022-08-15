<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Indice;
use App\Lacto_Indice;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Lacto_IndiceFormRequest;
use Illuminate\Support\Facades\DB;

class Lacto_IndiceController extends Controller
{
     public function __construct(){
    	$this->middleware('auth');
    }

    public function index(Request $request){

        $empresas=DB::table('empresa as emp')
        ->get();

        if($request){
    		$query=trim($request->get('searchText'));
    		$lacto_indices=DB::table('lacto_indice as l')
    		->join('indice as i', 'l.idindice', '=', 'i.idindice')
            ->where('mes_ano', 'LIKE', '%'.$query.'%')
            ->orwhere('i.nome', 'LIKE', '%'.$query.'%')
            ->orderBy('idlacto_indice','desc')
    		->get();
    		return view('reajuste.lacto_indice.index', [
                "lacto_indices"=>$lacto_indices, 
                "empresas"=>$empresas,
                "searchText"=>$query
    			]);
    	}
    }

    public function create(){

         $indices=DB::table('indice')->get();

    	return view("reajuste.lacto_indice.create",[
    		"indices"=>$indices]);
    }
 
    public function store(Lacto_IndiceFormRequest $request){
    	$lacto_indice = new lacto_indice;
        $lacto_indice->idindice=$request->get('idindice');
        $lacto_indice->mes_ano=$request->get('mes_ano');
        $lacto_indice->valor=$request->get('valor');
    	$lacto_indice->save();
    	return Redirect::to('reajuste/lacto_indice');
    }

    public function show($id){
    	return view("reajuste.indice.show",
    		["lacto_indice"=>lacto_indice::findOrFail($id)]);
    }


 	public function edit($id){

         $indices=DB::table('indice')->get();

         $lacto_indices=DB::table('lacto_indice as l')
        ->join('indice as i', 'l.idindice', '=', 'i.idindice')
        ->select('i.idindice','i.nome as nomeind')
        ->where('l.idlacto_indice','=',$id )
        ->get();

    	return view("reajuste.lacto_indice.edit", 
    		["lacto_indice"=>lacto_indice::findOrFail($id),
    		"lacto_indices"=>$lacto_indices,
            "indices"=>$indices]);
    }


    public function update(Lacto_IndiceFormRequest $request, $id){
    	$lacto_indice=lacto_indice::findOrFail($id);
        $lacto_indice->idindice=$request->get('idindice');
        $lacto_indice->mes_ano=$request->get('mes_ano');
        $lacto_indice->valor=$request->get('valor');
    	$lacto_indice->update();
    	return Redirect::to('reajuste/lacto_indice');
    }

    public function destroy($id){
    	$lacto_indice=lacto_indice::findOrFail($id);
    	$lacto_indice->delete();
    	return Redirect::to('reajuste/lacto_indice');
    }
}
