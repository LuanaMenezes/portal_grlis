<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Cliente;
use App\Imports\ClientesImport;
use Facade\FlareClient\Http\Client;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
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
        $userId = Auth::id();
        $clientes = DB::table('clientes')->where('user_id', '=', $userId)->get();
        return view ('clientes.index', compact('clientes'));
    }

      /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('clientes.create');
    }

    public function import()
    {
        return view ('clientes.import');
    }

    public function importFile(Request $request) 
    {
        try
        {
        $request->validate([
            'planilha' => 'required',
        ]);
        
        $file = request()->file('planilha');
        //dd($file);

        Excel::import(new ClientesImport, $file);     
        return redirect()->route('clientes.index')->with('mensagem', 'Clientes cadastrados!');
        } 
        catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            return view('clientes.import', compact('failures'));
         }
    }
     
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userId = Auth::id();

        $mensagens = [
            'required_if' => 'O campo quantidade de sócios é obrigatório quando a empresa é uniprofissional.'
        ];

        $request->validate([
            'nome' => 'required|max:255',
            'cnpj' => 'required|string|max:18|unique:clientes|cnpj',
            'cga' => 'required|string|min:14|max:14|unique:clientes|regex:/^\d{3}\.\d{3}\/\d{3}\-\d{2}$/',
            'senha' => 'required',
            'uniprofissional' => 'required|max:255',
            'qtd_socios' => 'required_if:uniprofissional,==,S',
        ], $mensagens);

        $cipher = "aes-256-cbc";
        $iv = "0123456789012345";
        $key = "870d87ta20685b40";

        $cliente = new Cliente();
        $cliente->nome = $request->input('nome');
        $cliente->dtoperacao = $request->input('dtoperacao');
        $cliente->totalface = $request->input('totalface');
        $cliente->qtddigitada = $request->input('qtddigitada');        
        $cliente->user_id = $userId;
        $cliente->save();
        return redirect()->route('clientes.create')->with('mensagem', 'Bordero cadastrado!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!$client = Cliente::find($id))
            return redirect()->back();

        return view ('clientes.edit', compact('client'));
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
        if(!$client = Cliente::find($id))
            return redirect()->back();

            Validator::make($request->all(), [
                'cnpj' => [
                    'required',
                    'max:18',
                    'cnpj',
                    Rule::unique('clientes')->ignore($client->id),
                ],
                'cga' => [
                    'required',
                    'max:14',
                    'cnpj',
                    Rule::unique('clientes')->ignore($client->id),
                ],
                'nome' => 'required|max:255',
               /* 'senha' => 'required',*/
                'uniprofissional' => 'required|max:255',
                'qtd_socios' => 'required_if:uniprofissional,==,S',
            ]);   

        $client->update($request->all());

        return redirect()->route('clientes.index')->with('mensagem', 'Cliente atualizado!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!$client = Cliente::find($id))
            return redirect()->back();
        
        $client->delete();

        return redirect()->route('clientes.index');
    }

    public function updateStatus($id) {
       
       $client = Cliente::find($id);

        if($client->ativo == 'S')
        {
            $client->ativo = 'N';
            $msg = 'Cliente inativado';
        }
        else
        {
            $client->ativo = 'S';
            $msg = 'Cliente ativado';
        }

        $client->save();
        return redirect()->route('clientes.index')->with('mensagem', $msg);
    }
}
