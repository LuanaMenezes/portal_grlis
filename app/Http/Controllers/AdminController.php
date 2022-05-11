<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bordero;
use App\Operacao;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller

{
    public function __construct()
    {
       $this->middleware('auth:admin');
    }

    public function indexFiltering(Request $request)
    {
      
        $back = 0;
        $mensagens = [
            'required' => 'O campo :attribute deve ser preenchido'
        ];

       $request->validate([
            'data_final' => 'required|date',
            'data_inicial' => 'required|date',
        ], $mensagens);

        $data_ini = $request->input('data_inicial');
        $data_fim = $request->input('data_final');
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

         $operacoes = DB::table('operacoes')
        ->join('borderos','operacoes.bordero_id','=','borderos.id')
        ->select('bordero_id','operacoes.id', 'razaosocial', 'operacoes.numero', 'vcto', 'vlrface','status')
        ->orderBy('bordero_id', 'desc')
        ->get();

   
        $borderos = DB::table('borderos')
        ->join('operacoes', 'borderos.id', '=', 'operacoes.bordero_id')
        ->select('contratante', 'codcedente', DB::raw('count(codcedente) as total_cedente'), 'borderos.id', 'borderos.created_at', 'totalvlrface', 'qtddigitada')
        ->groupBy('contratante', 'codcedente', 'borderos.id', 'borderos.created_at', 'totalvlrface', 'qtddigitada', 'contratante')
        //->whereBetween('operacoes.created_at', [$data_ini, $data_fim])
        ->where('operacoes.created_at', '>=', $data_ini)
        ->where('operacoes.created_at', '<=', $data_fim)
        ->orderBy('borderos.created_at', 'asc')
        ->paginate(15)
        ->appends(request()->query());

        session()->flashInput($request->input());
       
        return view ('admin', compact('borderos' , 'operacoes', 'back'));
    }

    public function index(Request $request)
    {
        $back = 1;
        $data = $request->session()->all();
        if(isset($data['_old_input']))
        {
            unset($data['_old_input']);
        }

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

         $operacoes = DB::table('operacoes')
        ->join('borderos','operacoes.bordero_id','=','borderos.id')
        ->select('bordero_id','operacoes.id', 'razaosocial', 'operacoes.numero', 'vcto', 'vlrface','status')
        ->orderBy('bordero_id', 'desc')
        ->get();

        $borderos = DB::table('borderos')
        ->join('operacoes', 'borderos.id', '=', 'operacoes.bordero_id')
        ->select('contratante', 'codcedente', DB::raw('count(codcedente) as total_cedente'), 'borderos.id', 'borderos.created_at', 'totalvlrface', 'qtddigitada')
        ->groupBy('contratante', 'codcedente', 'borderos.id', 'borderos.created_at', 'totalvlrface', 'qtddigitada', 'contratante')
        ->whereDate('operacoes.created_at', Carbon::today())
        ->orderBy('borderos.created_at', 'asc')
        ->paginate(15);
        return view ('admin', compact('borderos' , 'operacoes', 'back'));
        
    }


}
