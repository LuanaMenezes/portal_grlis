<?php

namespace App\Http\Controllers;

use App\Bordero;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Cliente;
use App\Operacao;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $userId = Auth::id();

        /* Apagando os borderôs sem operações*/
        $operacoes = DB::table('operacoes')->where('tipotitulo', '=', NULL)->get();
        $qtd_operacoes = count($operacoes);
        if ($qtd_operacoes > 0)
        {
            foreach($operacoes as $operacao)
            {
                Operacao::where('bordero_id', '=', $operacao->bordero_id)->delete();
                Bordero::where('id', '=', $operacao->bordero_id)->delete();
            } 
        }

        /*"DB::transaction(function()
        {
            DB::statement('  DELETE c
            FROM borderos c
            left join operacoes p ON p.bordero_id = c.id
            WHERE p.bordero_id IS NULL');
        });*/


          /* Apagando os terceiros sem arquivos*/
          DB::transaction(function()
          {
              DB::statement('DELETE FROM terceiros 
              WHERE NOT EXISTS(SELECT NULL
                                 FROM arquivo_terceiros at
                                WHERE at.terceiro_id = terceiros.id)');
          });
        
        $borderos = DB::table('borderos')
            ->select('id', 'created_at', 'totalvlrface', 'qtddigitada', 'nome', 'contratante')
            ->where('user_id', '=', $userId)
            ->orderBy('id', 'desc')
            ->paginate(15);

        $operacoes = DB::table('operacoes')
            ->join('borderos','operacoes.bordero_id','=','borderos.id')
            ->select('bordero_id','operacoes.id', 'razaosocial', 'operacoes.numero', 'vcto', 'vlrface','status')
            ->where('user_id', '=', $userId)
            ->orderBy('bordero_id', 'desc')
            ->get();

        return view ('home', compact('borderos' , 'operacoes'));
    }
}
