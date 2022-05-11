<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Bordero;
use App\Bancos;
use App\Cliente;
use App\Contratante;
use App\Sacado;
use App\Imports\ClientesImport;
use App\Operacao;
use App\Rules\CodigoCedenteEqual;
use App\Rules\DataEmissaoXML;
use Facade\FlareClient\Http\Client;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Canducci\ZipCode\Facades\ZipCode;
use SimpleXMLElement;
use Input;

class BorderoController extends Controller
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
     * Remove as pastas vazias
     *
     * @return bool
     */
    public static function RemoveEmptySubFolders($path)
    {
        $empty = true;
        foreach (glob($path . DIRECTORY_SEPARATOR . "*") as $file) {
            $empty &= is_dir($file) && BorderoController::RemoveEmptySubFolders($file);
        }
        return $empty && (is_readable($path) && count(scandir($path)) == 2) && rmdir($path);
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
        return view ('borderos.index', compact('clientes'));

    }

      /**
     * Move os arquivos para pastas especificas
     *
     * @return bool
     */
    public static function moveFileToFolder($files, $nomeContratante, $borderoId) 
    {
        date_default_timezone_set('America/Bahia');
        setlocale(LC_ALL, 'pt_BR');
       
        //criando a pasta
        $ano = date("Y");
        $mes_int = date('m');
        $date = date('d-m-Y');
        $mes_str = strtoupper(strftime('%B', strtotime($date)));

        $destiny = 'Y:\\SUPORTE COMERCIAL\\BORDERO DIARIO\\'.$ano.'\\'.$mes_int.'-'.$mes_str.'\\'.$date.'\\'.$nomeContratante;
        
        $dirs =  glob($destiny . '/*' , GLOB_ONLYDIR);
        $qtd_dir = count($dirs);
        $count_op = 0;

        if($qtd_dir > 0)
        {
            BorderoController::RemoveEmptySubFolders($destiny);
            $dirs =  glob($destiny . '/*' , GLOB_ONLYDIR);
            $qtd_dir = count($dirs);
            $j = 0;
            for($i=0; $i<$qtd_dir; $i++)
            {
                $word = 'OP ';
                $s1 = strtolower($dirs[$i]);
                $s2 = strtolower($word); 

                if (str_contains($s1, $s2)){
                    $j++; 
                }
                
            }
            $count_op = $j+1;
        }
        else
        {
            $count_op = 1;
        }
        
        $operacao = 'OP '.$count_op;
        $destiny_final = 'Y:\\SUPORTE COMERCIAL\\BORDERO DIARIO\\'.$ano.'\\'.$mes_int.'-'.$mes_str.'\\'.$date.'\\'.$nomeContratante.'\\'.$operacao;
        foreach ($files as $data)
        {
            if(is_array($data))
            {
               foreach($data as $file)
               {
                    try
                    {
                        $nameFile = $file->getClientOriginalName();
                        $nameFile = str_replace( array( '\'', '/',
                        '*' , ':', '?', '"', ">","<" ), ' ', $nameFile);


                        $path = storage_path().'\\app\\public\\Bordero\\'.$ano.'\\'.$mes_int.'-'.$mes_str.'\\'.$date.'\\'.$nomeContratante;

                        if (!File::exists($path)) {
                            File::makeDirectory($path,0777,true);
                        }

                        if (!file_exists($destiny_final)) {
                            mkdir($destiny_final, 0777, true);
                        }

                    $file->storeAs('public/Bordero/'.$ano.'/'.$mes_int.'-'.$mes_str.'/'.$date.'/'.$nomeContratante, $nameFile);
                        copy(storage_path().'/app/public/Bordero/'.$ano.'/'.$mes_int.'-'.$mes_str.'/'.$date.'/'.$nomeContratante.'/'.$nameFile, $destiny_final.'/'.$nameFile);
                        BorderoController::RemoveEmptySubFolders($destiny);
                    }
                    catch (\Exception $e) {
                        return redirect()->route('clientes.edit', $borderoId )->with('mensagem', 'Não foi possível criar a pasta. Contate o administrador do sistema.');
                    }
                }
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bancos = Bancos::select('Codigo', 'Descricao')
            ->orderBy('Descricao', 'asc')
            ->get();

        $contratante = Contratante::select('nome', 'cnpj')
            ->where('cedentecodigo', '=', Auth::user()->cedentecodigo)
            ->get();

        return view ('borderos.create', compact('bancos', 'contratante'));

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
        $files_array=[];

        $mensagens = [
            'required' => 'O campo :attribute deve ser preenchido'
        ];

        $request->validate([
            'codcedente' => 'max:255',
            'dataop' => 'required|date',
            'qtddigitada' => 'required|integer',
            'contratofomento' => 'max:255',
            'contratante' => 'required|max:255',
            'cnpjcontratante' => 'required|string|cnpj',
            'assinatura' => 'date',
            'nomebanco' => 'required|max:255',
            'numbanco' => 'required', 'numeric', 'gt:0',
            'agencia' => 'required', 'numeric', 'gt:0',
            'contacorrente' => 'required', 'numeric', 'gt:0',
            'cnpjcredito' => 'required|string|cnpj',
            'nome' => 'required|max:250',
            'proposta' => 'max:250',
            'status' => 'max:250',
            'statusbordero' => 'max:250',
            'bancooperacao' => 'max:250', 'text',
            'endop' => 'max:250',
            'pixtipo' => 'max:255','string',
            'pixchave' => 'max:255','string',

        ], $mensagens);

        $files = $request->file('arquivo');
        $bordero = new Bordero();
        $bordero->codcedente = Auth::user()->cedentecodigo;
        $bordero->dataop = $request->input('dataop');
        $bordero->totalvlrface = 0;
        $bordero->qtddigitada = $request->input('qtddigitada');
        $bordero->contratofomento = $request->input('contratofomento');
        $bordero->contratante = $request->input('contratante');
        $bordero->assinatura = $request->input('assinatura');
        $bordero->operacao = $request->input('operacao');
        $bordero->nomebanco = $request->input('nomebanco');
        $bordero->numbanco = $request->input('numbanco');
        $bordero->agencia = $request->input('agencia');
        $bordero->contacorrente = $request->input('contacorrente');
        $bordero->cnpjcredito = $request->input('cnpjcredito');
        $bordero->cnpjcontratante = $request->input('cnpjcontratante');
        $bordero->nome = $request->input('nome');
        $bordero->statusbordero = $request->input('statusbordero');
        $bordero->proposta = $request->input('proposta');
        $bordero->pixtipo = $request->input('pixtipo');
        $bordero->pixchave = $request->input('pixchave');
        $bordero->observacoes = $request->input('obs');
        $bordero->user_id = $userId;
        $bordero->save();

        if($request->hasFile('arquivo'))
        {
            foreach ($files as $file)
            {
                $extension = $file->extension();

                        if( $extension == 'exe')
                        {
                            return redirect()->route('borderos.create')->with('mensagem2', 'Borderô criado!');
                        }
                        else
                        {
                            array_push ($files_array, $files); 
    
                            if(count($files_array)>0)
                            {
                                BorderoController::moveFileToFolder($files_array, $request->contratante, $bordero->id);
                            }
                        }
                }
        }

        return redirect()->route('borderos.edit', $bordero->id );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!$bordero = Bordero::find($id))
            return redirect()->back();

        /* Apagando as duplicatas*/
        DB::transaction(function()
        {
            DB::statement('WITH cte AS (
                SELECT[created_at], [razaosocial], [vcto], [vlrface], [numero],
                   row_number() OVER(PARTITION BY [created_at], [razaosocial], [vcto], [vlrface], [numero] ORDER BY razaosocial) AS [rn]
                FROM operacoes
              )
              DELETE cte WHERE [rn] > 1');
        });

        $operacoes = DB::table('operacoes')->where('bordero_id', '=', $id)->get();
        $dado = [];
        $totalvlrface = DB::table('operacoes')
        ->where('bordero_id', '=', $id)
        ->sum('vlrface');
        $dado=['totalvlrface'=>$totalvlrface];

        $sacados = DB::table('sacados')
               ->where('cedentecodigo', '=', Auth::user()->cedentecodigo)
               ->where('ativo', '=', 1)
               ->orderBy('razao_social')
               ->get();

        return view ('borderos.edit', compact('bordero', 'operacoes', 'dado', 'sacados'));
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
         if(!$bordero = Bordero::find($id))
            return redirect()->back();

        $mensagens = ['tipotitulo.*.required' => 'O campo tipo do título deve ser preenchido',
                      'emailoperacao.*.required' => 'O campo email deve ser preenchido',
                      'ddd.*.required' => 'O campo ddd deve ser preenchido',
                      'telefone.*.required' => 'O campo telefone deve ser preenchido',
                      'vcto.*.required' => 'O campo vencimento deve ser preenchido',
                      'razaosocial.*.required' => 'O campo razão social deve ser preenchido',
                      'cnpjsacado.*.required' => 'O campo cnpj deve ser preenchido',
                      'numero.*.required' => 'O campo número deve ser preenchido',
                      'vlrface.*.required' => 'O campo valor face deve ser preenchido',
                      'qtdetitulo.*.required' => 'O campo quantidade de títulos deve ser preenchido',
                      'endop.*.required' => 'O campo endereço deve ser preenchido',
                      'emailoperacao.*.email' => 'O campo email deve ser um endereço de e-mail válido.',
                    ];

        $request->validate([
                'tipotitulo.*' => 'required',
                'emailoperacao.*' => 'required|email',
                'ddd.*' => 'required',
                'telefone.*' => 'required',
                'vcto.*' => 'required',
                'razaosocial.*' => 'required',
                'cnpjsacado.*' => 'required',
                'numero.*' => 'required',
                'vlrface.*' => 'required',
                'qtdetitulo.*' => 'required',
                'endop.*' => 'required',
                'g-recaptcha-response' => 'required|captcha',

            ], $mensagens);

        $qtdtitulos = count($request['tipotitulo']);

        for($i = 0; $i<$qtdtitulos; $i++)
        {
            $valorface = str_replace(',', '.',$request['vlrface'][$i]);

            $operacao = new Operacao();
            $operacao->cnpjsacado = $request['cnpjsacado'][$i];
            $operacao->razaosocial = $request['razaosocial'][$i];
            $operacao->tipotitulo = $request['tipotitulo'][$i];
            $operacao->emailoperacao = $request['emailoperacao'][$i];
            $operacao->ddd = $request['ddd'][$i];
            $operacao->telefone = $request['telefone'][$i];
            $operacao->vcto = $request['vcto'][$i];
            $operacao->vlrface = $valorface;
            $operacao->numero = $request['numero'][$i];
            $operacao->endop = $request['logradouro'][$i];
            $operacao->bairro = $request['bairro'][$i];
            $operacao->cidade = $request['cidade'][$i];
            $operacao->estado = $request['estado'][$i];
            $operacao->cep = $request['cep'][$i];
            $operacao->qtdetitulo = 1;
            $operacao->status = 0;
            $operacao->bordero_id = $bordero->id;
            $operacao->save();
        }
        
        $request->mod_totalvlrface = str_replace(',', '.',$request->mod_totalvlrface);

        $valorfacefinal = $request->mod_totalvlrface;
          
        Bordero::where('id', '=', $bordero->id)
        ->update([
            'qtddigitada' => $qtdtitulos,
            'totalvlrface' => $valorfacefinal,
            'concluido' => 1]);

        return redirect()->route('home')->with('mensagem', 'Borderô criado!');
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

        return redirect()->route('borderos.index');
    }

    public function show($id){ }

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
        return redirect()->route('borderos.index')->with('mensagem', $msg);
    }

    public function getSacadoData(Request $request) 
    {
         $razao_id = $request->razao_id;

         $sacado = DB::table('sacados')
               ->where('id', '=', $razao_id)
               ->get();
        
        if(count($sacado) > 0)
        {
            foreach($sacado as $s)
            {
                $cnpj = $s->cnpj;
                $cep = $s->cep;
                $endereco = $s->endereco;
                $bairro = $s->bairro;
                $cidade = $s->cidade;
                $estado = $s->estado;
                $email = $s->email;
                $ddd = $s->ddd;
                $telefone = $s->telefone;

                $final = $cnpj."$".$cep."$".$endereco."$".$bairro."$".$cidade."$".$estado."$".$email."$".$ddd."$".$telefone;

                echo $final;
            }
        }
    }
}


