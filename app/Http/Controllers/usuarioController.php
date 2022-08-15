<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\usuarioFormRequest;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class usuarioController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){

        $users = User::all();
        return view('seguranca.usuario.index', compact('users'));
    }

public function create(){
        $roles = Role::all();
        return view('seguranca.usuario.create',compact('roles'));
    }

    public function store(usuarioFormRequest $request){

        $role = Role::where('id',$request->rol)->first();

        $usuario = new User;

        $usuario->name = $request->get('name');
        $usuario->email= $request->get('email');
        $usuario->password = bcrypt($request->get('password'));
        $usuario->status = "";

        $usuario->save();

        $usuario->assignRole($role->name);

        return Redirect::to('seguranca/usuario');
    }


    public function show($id){
        return view("seguranca/usuario.show",
            ["usuario"=>user::findOrFail($id)]);
    }

    public function edit($id){
        $user = User::where('id',$id)->first();
        $roles = Role::all();
        return view('seguranca.usuario.edit',compact('user','roles'));
    }


    public function update(Request $request, $id){
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'required|confirmed|min:8'
        ]);

        User::where('id',$id)->update([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> bcrypt($request->password),
        ]);

        return Redirect::to('seguranca/usuario');
    }


    public function destroy($id){
        $user = User::where('id',$id)->first();

        if($user->hasRole('Admin'))
        {
            return back()->with(
                'danger', 'Este usuario Admin não pode ser Apagado'
            );
        }

        $user->delete();

        return Redirect::to('seguranca/usuario');
    }

}
