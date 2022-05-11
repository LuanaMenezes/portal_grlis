<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Contratante;
use App\Terceiro;
use App\ArquivoTerceiro;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use PhpParser\Node\Expr\Ternary;

class TerceiroController extends Controller
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

    }

        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $contratante = Contratante::select('nome', 'cnpj')
            ->where('cedentecodigo', '=', Auth::user()->cedentecodigo)
            ->get();       
       
        foreach($contratante as $c)
        {
            $nome = $c->nome;
            $cnpj = $c->cnpj;
        }
                                   
        $terceiro = new Terceiro();
        $terceiro->contratante = $nome;
        $terceiro->cnpjcontratante = $cnpj;
        $terceiro->save();

        $terc = DB::table('terceiros')->latest('id')->first();
        
        $id = $terc->id;

        return view ('terceiros.create', compact('contratante','id'));

    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = $request->input('id');

        $mensagens = [
            'required' => 'O campo :attribute deve ser preenchido'
        ];

        $request->validate([
            'contratante' => 'required|max:255',
            'cnpjcontratante' => 'required|string|cnpj',
            'arquivo' =>'required',
        ], $mensagens);

        Terceiro::where('id', '=', $id)
        ->update([
            'observacoes' => $request->obs,
        ]);

        if($request->hasFile('arquivo'))
        {
            $nomeContratante = $request->input('contratante');
            $files = $request->file('arquivo');

            foreach ($files as $file)
            {

                try
                    {
                        $nameFile = $file->getClientOriginalName();
                        $nameFile = $id.'_'.$nameFile;
                        $nameFile = str_replace( array( '\'', '/',
                        '*' , ':', '?', '"', ">","<" ), ' ', $nameFile);

                        date_default_timezone_set('America/Bahia');
                        setlocale(LC_ALL, 'pt_BR');

                        //criando a pasta
                        $ano = date("Y");
                        $mes_int = date('m');
                        $data = date('d-m-Y');
                        $mes_str = strtoupper(strftime('%B', strtotime($data)));

                        $path = storage_path().'\\app\\public\\Bordero\\'.$ano.'\\'.$mes_int.'-'.$mes_str.'\\'.$data.'\\'.$nomeContratante.'\\'.'Terceiro';

                        if (!File::exists($path)) {
                            File::makeDirectory($path,0777,true);
                        }

                        $destiny = 'Y:\\SUPORTE COMERCIAL\\BORDERO DIARIO\\'.$ano.'\\'.$mes_int.'-'.$mes_str.'\\'.$data.'\\'.$nomeContratante.'\\'.'Terceiro';

                        $nameFile2= 'observacoes.txt';
                        $nameFile2 = $id.'_'.$nameFile2;

                        if (!file_exists($destiny)) {
                            mkdir($destiny, 0777, true);
                        }

                        $file->storeAs('public/Bordero/'.$ano.'/'.$mes_int.'-'.$mes_str.'/'.$data.'/'.$nomeContratante.'/'.'Terceiro', $nameFile);
                        copy(storage_path().'/app/public/Bordero/'.$ano.'/'.$mes_int.'-'.$mes_str.'/'.$data.'/'.$nomeContratante.'/'.'Terceiro'.'/'.$nameFile, $destiny.'/'.$nameFile);
                    

                        $content = $request->obs;
                        $fp = fopen($destiny . "/". $nameFile2,"wb");
                        fwrite($fp,$content);
                        fclose($fp);
                   
                        $arquivo = new ArquivoTerceiro();
                        $arquivo->path_arquivo = $destiny.'/'.$nameFile;
                        $arquivo->terceiro_id = $id;
                        $arquivo->save();
                    }
                    
                    catch (\Exception $e) {
                        return redirect()->route('terceiros.create')->with('mensagem', 'Não foi possível criar a pasta. Contate o administrador do sistema.');
                    }
            }
        }

        return redirect()->route('home')->with('mensagem', 'Pagamento à terceiro criado!');
    }
}
