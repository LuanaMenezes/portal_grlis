<?php

namespace App\Http\Controllers;

use App\Sacado;
use App\Contratante;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SacadoController extends Controller
{
      /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sacados = DB::table('sacados')->where('cedentecodigo', '=', Auth::user()->cedentecodigo)->get();
        return view ('sacados.index', compact('sacados'));
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('sacados.create');
    }

    public static function limpaCPF_CNPJ($valor){
        $valor = trim($valor);
        $valor = str_replace(".", "", $valor);
        $valor = str_replace(",", "", $valor);
        $valor = str_replace("-", "", $valor);
        $valor = str_replace("/", "", $valor);
        return $valor;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $mensagens = [];

       // SacadoController::limpaCPF_CNPJ($request->input('cnpj'));

        $request->validate([
            'razao_social' => 'required|max:255',
            'cep' => 'required|string|max:8',
            'endereco' => 'required|max:255',
            'bairro' => 'required|max:255',
            'cidade' => 'required|max:255',
            'estado' => 'required|max:2',
            'email' => 'required|max:255',
            'ddd' => 'required|max:2',
            'telefone' => 'required|max:20',
            'cnpj' => ['required', 'unique:sacados,cnpj,NULL,id,cedentecodigo,'.Auth::user()->cedentecodigo],

        ], $mensagens);

        $mensagens2 = [];

        $request->validate([
            'cnpj' => 'required|string|max:18|cnpj',  
        ], $mensagens2);


        $sacado = new Sacado();
        $sacado->razao_social = $request->input('razao_social');
        $sacado->cnpj = $request->input('cnpj');
        $sacado->cep = $request->input('cep');
        $sacado->endereco = $request->input('endereco');
        $sacado->bairro = $request->input('bairro');
        $sacado->cidade = $request->input('cidade');
        $sacado->estado = $request->input('estado');
        $sacado->email = $request->input('email');
        $sacado->ddd = $request->input('ddd');
        $sacado->telefone = $request->input('telefone');
        $sacado->cedentecodigo = Auth::user()->cedentecodigo;
        $sacado->save();
        return redirect()->route('sacados.create')->with('mensagem', 'Sacado cadastrado!');
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!$sacado = Sacado::find($id))
            return redirect()->back();

        return view ('sacados.edit', compact('sacado'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!$sacado = Sacado::find($id))
            return redirect()->back();

            $mensagens = [];

            $request->validate([
                'razao_social' => 'required|max:255',
                'cep' => 'required|string|max:8',
                'endereco' => 'required|max:255',
                'bairro' => 'required|max:255',
                'cidade' => 'required|max:255',
                'estado' => 'required|max:255',
                'email' => 'required|max:255',
                'ddd' => 'required|max:2',
                'telefone' => 'required|max:20',
                'cnpj' => ['required', 'unique:sacados,cedentecodigo,'.$id.',id,cedentecodigo,'.Auth::user()->cedentecodigo]
    
            ], $mensagens);

        $sacado->update($request->all());

        return redirect()->route('sacados.index')->with('mensagem', 'Sacado atualizado!');
    }

    public function updateStatus($id) {

        $sacado = Sacado::find($id);
 
         if($sacado->ativo == '1')
         {
             $sacado->ativo = '0';
             $msg = 'Sacado inativado';
         }
         else
         {
             $sacado->ativo = '1';
             $msg = 'Sacado ativado';
         }
 
         $sacado->save();
         
         return redirect()->route('sacados.index')->with('mensagem2', $msg);
     }
}
