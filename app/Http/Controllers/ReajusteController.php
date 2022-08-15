<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Indice;
use App\Reajuste;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ReajusteFormRequest;
use Illuminate\Support\Facades\DB;


class ReajusteController extends Controller
{
   public function __construct(){
    	$this->middleware('auth');
    }

    public function index(Request $request){

        $empresas=DB::table('empresa as emp')
        ->get();
 
        $indices=DB::table('indice')
        ->get();

    	if($request){
    		$query=trim($request->get('searchText'));
    		$reajustes=DB::table('reajuste as r')
            ->join('indice as i', 'r.idindice', '=', 'i.idindice')
    		->where('mes_ano', 'LIKE', '%'.$query.'%')
            ->orderBy('idreajuste','desc')
    		->get();

    		return view('reajuste.reajuste.index', [
    			"reajustes"=>$reajustes, 
                "indice"=>$indices,
                "empresas"=>$empresas,
                "searchText"=>$query
    			]);
    	}
    }

    public function create(){

         $indices=DB::table('indice')->get();

    	return view("reajuste.reajuste.create",[
    		"indices"=>$indices]);
    }
 
    public function store(ReajusteFormRequest $request){
    	$reajuste = new reajuste;
        $reajuste->idindice=$request->get('idindice');
        $reajuste->mes_ano=$request->get('mes_ano');
        $reajuste->mensal=$request->get('mensal');
        $reajuste->bimestral=$request->get('bimestral');
        $reajuste->trimestral=$request->get('trimestral');
        $reajuste->quadrimestral=$request->get('quadrimestral');
        $reajuste->quintimestral=$request->get('quintimestral');
        $reajuste->semestral=$request->get('semestral');
        $reajuste->anual=$request->get('anual');
        $reajuste->bianual=$request->get('bianual');
    	$reajuste->save();
    	return Redirect::to('reajuste/reajuste');
    }

    public function show($id){
    	return view("reajuste.reajuste.show",
    		["reajuste"=>reajuste::findOrFail($id)]);
    }


 	public function edit($id){

        $indices=DB::table('indice')->get();

        $reajustes=DB::table('reajuste as r')
        ->join('indice as i', 'r.idindice', '=', 'i.idindice')
        ->select('i.idindice','i.nome as nomeind')
        ->where('r.idreajuste','=',$id )
        ->get();

    	return view("reajuste.reajuste.edit", 
    		["reajuste"=>reajuste::findOrFail($id),
    	    "reajustes"=>$reajustes,
        	"indices"=>$indices]);
    }


    public function update(ReajusteFormRequest $request, $id){
    	$reajuste=reajuste::findOrFail($id);
        $reajuste->idindice=$request->get('idindice');
        $reajuste->mes_ano=$request->get('mes_ano');
        $reajuste->mensal=$request->get('mensal');
        $reajuste->bimestral=$request->get('bimestral');
        $reajuste->trimestral=$request->get('trimestral');
        $reajuste->quadrimestral=$request->get('quadrimestral');
        $reajuste->quintimestral=$request->get('quintimestral');
        $reajuste->semestral=$request->get('semestral');
        $reajuste->anual=$request->get('anual');
        $reajuste->bianual=$request->get('bianual');
    	$reajuste->update();
    	return Redirect::to('reajuste/reajuste');
    }

    public function destroy($id){
    	$reajuste=reajuste::findOrFail($id);
    	$reajuste->delete();
    	return Redirect::to('reajuste/reajuste');
    }
}
