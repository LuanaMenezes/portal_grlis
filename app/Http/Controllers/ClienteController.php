<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Bordero;
use App\Bancos;
use App\Cliente;
use App\Contratante;
use App\Municipios;
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
     * Remove as pastas vazias
     *
     * @return bool
     */
    public static function RemoveEmptySubFolders($path)
    {
        $empty = true;
        foreach (glob($path . DIRECTORY_SEPARATOR . "*") as $file) {
            $empty &= is_dir($file) && ClienteController::RemoveEmptySubFolders($file);
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
        return view ('clientes.index', compact('clientes'));

    }
    
     /**
     * Valida o codigo do cedente e a data de emissão. Compara dados do banco com o XML
     *
     * @return name
     */
    public static function validatorCedente($files) 
    {
          foreach ($files as $file)
          {

                        $cnpjsacado_array = [];
                        $dataEmissao_array = [];

                        $phpArray = simplexml_load_file($file);
                        $xml_name = $phpArray->getName();
            
                        $dom = new \DOMDocument();
                        $dom->load($file);
                    
                        $nfe = 0;
                        $infCte = 0;
                        $cte = 0;
                        $nfe = 0;
                        $listaNfse = 0;
                        $nfse = 0;

                        if($xml_name == 'nfeProc')
                        {
                            $nfe = $dom->getElementsByTagName('NFe');
                        }
                        else if($xml_name == 'CTe')
                        {
                            $cte = $dom->getElementsByTagName('infCte');
                        }
                        else if($xml_name == 'cteProc')
                        {
                             $infCte = $dom->getElementsByTagName('infCte');
                        }
                        else if($xml_name == 'GerarNfseResposta')
                        {
                             $listaNfse = $dom->getElementsByTagName('ListaNfse');;
                        }
                        else if($xml_name == 'CompNfse')
                        {
                             $nfse = $dom->getElementsByTagName('Nfse');
                        }
                        else
                        {
                           return redirect()->route('clientes.create')->with('mensagem2', 'Borderô criado!');
                        }
          

                        if(is_object($nfe))
                        {
                            $qtddup = $dom->getElementsByTagName('dup');
                            $qtddup = count($qtddup);

                            if($qtddup > 0)
                            {
                                for ($i =0; $i<$qtddup; $i++){

                                    $cnpjsacado =  $phpArray->NFe->infNFe->dest[0]->CNPJ;
                                    array_push ($cnpjsacado_array, $cnpjsacado);
                                    $dataEmissao = $phpArray->NFe->infNFe->ide[0]->dhEmi;
                                    array_push($dataEmissao_array,$dataEmissao);
                                }
                            }
                            else
                            {
                                    $cnpjsacado =  $phpArray->NFe->infNFe->dest[0]->CNPJ;
                                    array_push ($cnpjsacado_array, $cnpjsacado);
                                    $dataEmissao = $phpArray->NFe->infNFe->ide[0]->dhEmi;
                                    array_push($dataEmissao_array,$dataEmissao);
                            }
                        }

                        else if(is_object($infCte))
                        {
                            $toma3 = $dom->getElementsByTagName('toma3');
                            $toma3 = count($toma3);
                            $idtoma4 = $dom->getElementsByTagName('toma4');
                            $idtoma4 = count($idtoma4);
                        
                            $dataEmissao = $phpArray->CTe->infCte->ide[0]->dhEmi;
                            array_push($dataEmissao_array,$dataEmissao);

                            if($toma3 > 0)
                            {
                                $id = $phpArray->CTe->infCte->ide->toma3[0]->toma;
                                if($id == 0)
                                {
                                    $cnpjsacado =  $phpArray->CTe->infCte->rem[0]->CNPJ;
                                    array_push ($cnpjsacado_array, $cnpjsacado);
                                }
                                else if ($id == 1)
                                {
                                    $cnpjsacado =  $phpArray->CTe->infCte->exped[0]->CNPJ;
                                    array_push ($cnpjsacado_array, $cnpjsacado);
                                }
                                else if( $id == 2)
                                {
                                    $cnpjsacado =  $phpArray->CTe->infCte->receb[0]->CNPJ;
                                    array_push ($cnpjsacado_array, $cnpjsacado);
                                }
                                else if($id == 3)
                                {
                                    $cnpjsacado =  $phpArray->CTe->infCte->dest[0]->CNPJ;
                                    array_push ($cnpjsacado_array, $cnpjsacado);
                                }
                            }
                            else if ($idtoma4 > 0)
                            {
                                $cnpjsacado =  $phpArray->CTe->infCte->ide->toma4[0]->CNPJ;
                                array_push ($cnpjsacado_array, $cnpjsacado);
                            }
                            else
                            {
                                $cnpjsacado =  $phpArray->CTe->infCte->dest[0]->CNPJ;
                                array_push ($cnpjsacado_array, $cnpjsacado);
                            }
                        }

                        else if(is_object($cte))
                        {
                            $toma3 = $dom->getElementsByTagName('toma3');
                            $toma3 = count($toma3);
                            $idtoma4 = $dom->getElementsByTagName('toma4');
                            $idtoma4 = count($idtoma4);
                           
                            $dataEmissao = $phpArray->infCte->ide[0]->dhEmi;
                            array_push($dataEmissao_array,$dataEmissao);

                            if($toma3 > 0)
                            {
                                $id = $phpArray->infCte->ide->toma3[0]->toma;
                                if($id == 0)
                                {
                                    $cnpjsacado =  $phpArray->infCte->rem[0]->CNPJ;
                                    array_push ($cnpjsacado_array, $cnpjsacado);
                                }
                                else if ($id == 1)
                                {
                                    $cnpjsacado =  $phpArray->infCte->exped[0]->CNPJ;
                                    array_push ($cnpjsacado_array, $cnpjsacado);
                                }
                                else if( $id == 2)
                                {
                                    $cnpjsacado =  $phpArray->infCte->receb[0]->CNPJ;
                                    array_push ($cnpjsacado_array, $cnpjsacado);
                                }
                                else if($id == 3)
                                {
                                    $cnpjsacado =  $phpArray->infCte->dest[0]->CNPJ;
                                    array_push ($cnpjsacado_array, $cnpjsacado);
                                }
                            }
                            else if ($idtoma4 > 0)
                            {
                                $cnpjsacado =  $phpArray->infCte->ide->toma4[0]->CNPJ;
                                array_push ($cnpjsacado_array, $cnpjsacado);
                            }
                            else
                            {
                                $cnpjsacado =  $phpArray->infCte->dest[0]->CNPJ;
                                array_push ($cnpjsacado_array, $cnpjsacado);
                            }

                           
                        }
                        else if(is_object($listaNfse))
                        {
                            $cnpjsacado = $phpArray->ListaNfse->CompNfse->Nfse->InfNfse->DeclaracaoPrestacaoServico->InfDeclaracaoPrestacaoServico->Tomador->IdentificacaoTomador->CpfCnpj[0]->Cnpj;
                            array_push ($cnpjsacado_array, $cnpjsacado);
                            $dataEmissao = $phpArray->ListaNfse->CompNfse->Nfse->InfNfse[0]->DataEmissao;
                            array_push($dataEmissao_array,$dataEmissao);
                        }

                        else if(is_object($nfse))
                        {
                            $cnpjsacado =  $phpArray->Nfse->InfNfse->DeclaracaoPrestacaoServico->InfDeclaracaoPrestacaoServico->Tomador->IdentificacaoTomador->CpfCnpj[0]->Cnpj;
                            array_push ($cnpjsacado_array, $cnpjsacado);
                            $dataEmissao = $phpArray->Nfse->InfNfse[0]->DataEmissao;
                            array_push($dataEmissao_array,$dataEmissao);
                        }
            }
          
           /* foreach ($cnpjsacado_array as $cnpjsacado) {
                $requestcnpj = new Request([
                    'cnpjsacado' => $cnpjsacado
                ]);
                Validator::validate($requestcnpj->all(), ['cnpjsacado' => new CodigoCedenteEqual]);
            }*/

            foreach ($dataEmissao_array as $dataEmissao) {
                $dataEmissao = new Request([
                    'dataEmissao' => $dataEmissao
                ]);
                Validator::validate($dataEmissao->all(), ['dataEmissao' => new DataEmissaoXML]);
            }
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
            ClienteController::RemoveEmptySubFolders($destiny);
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
                        ClienteController::RemoveEmptySubFolders($destiny);
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

        return view ('clientes.create', compact('bancos', 'contratante'));

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
            'arquivo' =>'required',
            'pdf' =>'required',

        ], $mensagens);

        if($request->hasFile('arquivo'))
        {
            $files = $request->file('arquivo');
            array_push ($files_array, $files);
            ClienteController::validatorCedente($files);
        }

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

                            $phpArray = simplexml_load_file($file);
                            $xml_name = $phpArray->getName();
                            $dom = new \DOMDocument();
                            $dom->load($file);

                            $nfe = 0;
                            $infCte = 0;
                            $cte = 0;
                            $nfe = 0;
                            $listaNfse = 0;
                            $nfse = 0;    

                            if($xml_name == 'nfeProc')
                            {
                                $nfe = $dom->getElementsByTagName('NFe');
                            }
                            else if($xml_name == 'CTe')
                            {
                                $cte = $dom->getElementsByTagName('infCte');
                            }
                            else if($xml_name == 'cteProc')
                            {
                                $infCte = $dom->getElementsByTagName('infCte');
                            }
                            else if($xml_name == 'GerarNfseResposta')
                            {
                                $listaNfse = $dom->getElementsByTagName('ListaNfse');;
                            }
                            else if($xml_name == 'CompNfse')
                            {
                                $nfse = $dom->getElementsByTagName('Nfse');
                            }
                            else
                            {
                            return redirect()->route('clientes.create')->with('mensagem2', 'Borderô criado!');
                            }
          
                            $operacao = new Operacao();
                            $operacao->bordero_id = $bordero->id;

                            if(is_object($nfe))
                            {
                                $qtddup = $dom->getElementsByTagName('dup');
                                $qtddup = count($qtddup);

                                if($qtddup > 0)
                                {
                                    for ($i =0; $i<$qtddup; $i++){

                                        $data[] = array(
                                        'bordero_id' => $bordero->id,
                                        'created_at' => new \DateTime(),
                                        'updated_at' => new \DateTime(),
                                        'vcto' => $phpArray->NFe->infNFe->cobr->dup[$i]->dVenc,
                                        'razaosocial' => $phpArray->NFe->infNFe->dest[0]->xNome,
                                        'cnpjsacado' =>  $phpArray->NFe->infNFe->dest[0]->CNPJ,
                                        'numero' => $phpArray->NFe->infNFe->ide[0]->nNF,
                                        'vlrface' => $phpArray->NFe->infNFe->cobr->dup[$i]->vDup,
                                        'endop' => $phpArray->NFe->infNFe->dest->enderDest[0]->xLgr,
                                        'bairro' => $phpArray->NFe->infNFe->dest->enderDest[0]->xBairro,
                                        'cidade' => $phpArray->NFe->infNFe->dest->enderDest[0]->xMun,
                                        'estado' => $phpArray->NFe->infNFe->dest->enderDest[0]->UF,
                                        'cep' => $phpArray->NFe->infNFe->dest->enderDest[0]->CEP,
                                        'qtdetitulo' => 1,
                                        'status' => 0

                                        );

                                    }
                                }
                                else
                                {
                                    $data[] = array(
                                        'bordero_id' => $bordero->id,
                                        'created_at' => new \DateTime(),
                                        'updated_at' => new \DateTime(),
                                        'vcto' => NULL,
                                        'razaosocial' => $phpArray->NFe->infNFe->dest[0]->xNome,
                                        'cnpjsacado' =>  $phpArray->NFe->infNFe->dest[0]->CNPJ,
                                        'numero' => $phpArray->NFe->infNFe->ide[0]->nNF,
                                        'vlrface' => $phpArray->NFe->infNFe->total->ICMSTot[0]->vNF,
                                        'endop' => $phpArray->NFe->infNFe->dest->enderDest[0]->xLgr,
                                        'bairro' => $phpArray->NFe->infNFe->dest->enderDest[0]->xBairro,
                                        'cidade' => $phpArray->NFe->infNFe->dest->enderDest[0]->xMun,
                                        'estado' => $phpArray->NFe->infNFe->dest->enderDest[0]->UF,
                                        'cep' => $phpArray->NFe->infNFe->dest->enderDest[0]->CEP,
                                        'qtdetitulo' => 1,
                                        'status' => 0
                                    );
                                }

                                DB::table('operacoes')->insert($data);

                            }
                            else if(is_object($cte))
                            {
                                $toma3 = $dom->getElementsByTagName('toma3');
                                $toma3 = count($toma3);
                                $idtoma4 = $dom->getElementsByTagName('toma4');
                                $idtoma4 = count($idtoma4);

                                if($toma3 > 0)
                                {
                                    $id = $phpArray->infCte->ide->toma3[0]->toma;
                                    if($id == 0)
                                    {
                                        $operacao->razaosocial = $phpArray->infCte->rem[0]->xNome;
                                        $operacao->cnpjsacado =  $phpArray->infCte->rem[0]->CNPJ;
                                        $operacao->numero = $phpArray->infCte->ide[0]->nCT;
                                        $operacao->vlrface = $phpArray->infCte->vPrest[0]->vRec;
                                        $operacao->endop = $phpArray->infCte->rem->enderReme[0]->xLgr;
                                        $operacao->bairro = $phpArray->infCte->rem->enderReme[0]->xBairro;
                                        $operacao->cidade = $phpArray->infCte->rem->enderReme[0]->xMun;
                                        $operacao->estado = $phpArray->infCte->rem->enderReme[0]->UF;
                                        $operacao->cep = $phpArray->infCte->rem->enderReme[0]->CEP;
                                        $operacao->qtdetitulo = 1;
                                        $operacao->status = 0;
                                    }
                                    else if ($id == 1)
                                    {
                                        $operacao->razaosocial = $phpArray->infCte->exped[0]->xNome;
                                        $operacao->cnpjsacado =  $phpArray->infCte->exped[0]->CNPJ;
                                        $operacao->numero = $phpArray->infCte->ide[0]->nCT;
                                        $operacao->vlrface = $phpArray->infCte->vPrest[0]->vRec;
                                        $operacao->endop = $phpArray->infCte->exped->enderExped[0]->xLgr;
                                        $operacao->bairro = $phpArray->infCte->exped->enderExped[0]->xBairro;
                                        $operacao->cidade = $phpArray->infCte->exped->enderExped[0]->xMun;
                                        $operacao->estado = $phpArray->infCte->exped->enderExped[0]->UF;
                                        $operacao->cep = $phpArray->infCte->exped->enderExped[0]->CEP;
                                        $operacao->qtdetitulo = 1;
                                        $operacao->status = 0;
                                    }
                                    else if( $id == 2)
                                    {
                                        $operacao->razaosocial = $phpArray->infCte->receb[0]->xNome;
                                        $operacao->cnpjsacado =  $phpArray->infCte->receb[0]->CNPJ;
                                        $operacao->numero = $phpArray->infCte->ide[0]->nCT;
                                        $operacao->vlrface = $phpArray->infCte->vPrest[0]->vRec;
                                        $operacao->endop = $phpArray->infCte->receb->enderReceb[0]->xLgr;
                                        $operacao->bairro = $phpArray->infCte->receb->enderReceb[0]->xBairro;
                                        $operacao->cidade = $phpArray->infCte->receb->enderReceb[0]->xMun;
                                        $operacao->estado = $phpArray->infCte->receb->enderReceb[0]->UF;
                                        $operacao->cep = $phpArray->infCte->receb->enderReceb[0]->CEP;
                                        $operacao->qtdetitulo = 1;
                                        $operacao->status = 0;
                                    }
                                    else if($id == 3)
                                    {

                                        $operacao->razaosocial = $phpArray->infCte->dest[0]->xNome;
                                        $operacao->cnpjsacado =  $phpArray->infCte->dest[0]->CNPJ;
                                        $operacao->numero = $phpArray->infCte->ide[0]->nCT;
                                        $operacao->vlrface = $phpArray->infCte->vPrest[0]->vRec;
                                        $operacao->endop = $phpArray->infCte->dest->enderDest[0]->xLgr;
                                        $operacao->bairro = $phpArray->infCte->dest->enderDest[0]->xBairro;
                                        $operacao->cidade = $phpArray->infCte->dest->enderDest[0]->xMun;
                                        $operacao->estado = $phpArray->infCte->dest->enderDest[0]->UF;
                                        $operacao->cep = $phpArray->infCte->dest->enderDest[0]->CEP;
                                        $operacao->qtdetitulo = 1;
                                        $operacao->status = 0;
                                    }
                                }
                                else if ($idtoma4 > 0)
                                {
                                    $operacao->razaosocial = $phpArray->infCte->ide->toma4[0]->xNome;
                                    $operacao->cnpjsacado =  $phpArray->infCte->ide->toma4[0]->CNPJ;
                                    $operacao->numero = $phpArray->infCte->ide[0]->nCT;
                                    $operacao->vlrface = $phpArray->infCte->vPrest[0]->vRec;
                                    $operacao->endop = $phpArray->infCte->ide->toma4->enderToma[0]->xLgr;
                                    $operacao->bairro = $phpArray->infCte->ide->toma4->enderToma[0]->xBairro;
                                    $operacao->cidade = $phpArray->infCte->ide->toma4->enderToma[0]->xMun;
                                    $operacao->estado = $phpArray->infCte->ide->toma4->enderToma[0]->UF;
                                    $operacao->cep = $phpArray->infCte->ide->toma4->enderToma[0]->CEP;
                                    $operacao->qtdetitulo = 1;
                                    $operacao->status = 0;
                                }
                                else
                                {
                                    $operacao->razaosocial = $phpArray->infCte->dest[0]->xNome;
                                    $operacao->cnpjsacado =  $phpArray->infCte->dest[0]->CNPJ;
                                    $operacao->numero = $phpArray->infCte->ide[0]->nCT;
                                    $operacao->vlrface = $phpArray->infCte->vPrest[0]->vRec;
                                    $operacao->endop = $phpArray->infCte->dest->enderDest[0]->xLgr;
                                    $operacao->bairro = $phpArray->infCte->dest->enderDest[0]->xBairro;
                                    $operacao->cidade = $phpArray->infCte->dest->enderDest[0]->xMun;
                                    $operacao->estado = $phpArray->infCte->dest->enderDest[0]->UF;
                                    $operacao->cep = $phpArray->infCte->dest->enderDest[0]->CEP;
                                    $operacao->qtdetitulo = 1;
                                    $operacao->status = 0;
                                }
                                $operacao->save();
                            }
                            else if(is_object($infCte))
                            {
                                $toma3 = $dom->getElementsByTagName('toma3');
                                $toma3 = count($toma3);
                                $idtoma4 = $dom->getElementsByTagName('toma4');
                                $idtoma4 = count($idtoma4);

                                if($toma3 > 0)
                                {
                                    $id = $phpArray->CTe->infCte->ide->toma3[0]->toma;
                                    if($id == 0)
                                    {
                                        $operacao->razaosocial = $phpArray->CTe->infCte->rem[0]->xNome;
                                        $operacao->cnpjsacado =  $phpArray->CTe->infCte->rem[0]->CNPJ;
                                        $operacao->numero = $phpArray->CTe->infCte->ide[0]->nCT;
                                        $operacao->vlrface = $phpArray->CTe->infCte->vPrest[0]->vRec;
                                        $operacao->endop = $phpArray->CTe->infCte->rem->enderReme[0]->xLgr;
                                        $operacao->bairro = $phpArray->CTe->infCte->rem->enderReme[0]->xBairro;
                                        $operacao->cidade = $phpArray->CTe->infCte->rem->enderReme[0]->xMun;
                                        $operacao->estado = $phpArray->CTe->infCte->rem->enderReme[0]->UF;
                                        $operacao->cep = $phpArray->CTe->infCte->rem->enderReme[0]->CEP;
                                        $operacao->qtdetitulo = 1;
                                        $operacao->status = 0;
                                    }
                                    else if ($id == 1)
                                    {
                                        $operacao->razaosocial = $phpArray->CTe->infCte->exped[0]->xNome;
                                        $operacao->cnpjsacado =  $phpArray->CTe->infCte->exped[0]->CNPJ;
                                        $operacao->numero = $phpArray->CTe->infCte->ide[0]->nCT;
                                        $operacao->vlrface = $phpArray->CTe->infCte->vPrest[0]->vRec;
                                        $operacao->endop = $phpArray->CTe->infCte->exped->enderExped[0]->xLgr;
                                        $operacao->bairro = $phpArray->CTe->infCte->exped->enderExped[0]->xBairro;
                                        $operacao->cidade = $phpArray->CTe->infCte->exped->enderExped[0]->xMun;
                                        $operacao->estado = $phpArray->CTe->infCte->exped->enderExped[0]->UF;
                                        $operacao->cep = $phpArray->CTe->infCte->exped->enderExped[0]->CEP;
                                        $operacao->qtdetitulo = 1;
                                        $operacao->status = 0;
                                    }
                                    else if( $id == 2)
                                    {
                                        $operacao->razaosocial = $phpArray->CTe->infCte->receb[0]->xNome;
                                        $operacao->cnpjsacado =  $phpArray->CTe->infCte->receb[0]->CNPJ;
                                        $operacao->numero = $phpArray->CTe->infCte->ide[0]->nCT;
                                        $operacao->vlrface = $phpArray->CTe->infCte->vPrest[0]->vRec;
                                        $operacao->endop = $phpArray->CTe->infCte->receb->enderReceb[0]->xLgr;
                                        $operacao->bairro = $phpArray->CTe->infCte->receb->enderReceb[0]->xBairro;
                                        $operacao->cidade = $phpArray->CTe->infCte->receb->enderReceb[0]->xMun;
                                        $operacao->estado = $phpArray->CTe->infCte->receb->enderReceb[0]->UF;
                                        $operacao->cep = $phpArray->CTe->infCte->receb->enderReceb[0]->CEP;
                                        $operacao->qtdetitulo = 1;
                                        $operacao->status = 0;
                                    }
                                    else if($id == 3)
                                    {

                                        $operacao->razaosocial = $phpArray->CTe->infCte->dest[0]->xNome;
                                        $operacao->cnpjsacado =  $phpArray->CTe->infCte->dest[0]->CNPJ;
                                        $operacao->numero = $phpArray->CTe->infCte->ide[0]->nCT;
                                        $operacao->vlrface = $phpArray->CTe->infCte->vPrest[0]->vRec;
                                        $operacao->endop = $phpArray->CTe->infCte->dest->enderDest[0]->xLgr;
                                        $operacao->bairro = $phpArray->CTe->infCte->dest->enderDest[0]->xBairro;
                                        $operacao->cidade = $phpArray->CTe->infCte->dest->enderDest[0]->xMun;
                                        $operacao->estado = $phpArray->CTe->infCte->dest->enderDest[0]->UF;
                                        $operacao->cep = $phpArray->CTe->infCte->dest->enderDest[0]->CEP;
                                        $operacao->qtdetitulo = 1;
                                        $operacao->status = 0;
                                    }
                                }
                                else if ($idtoma4 > 0)
                                {
                                    $operacao->razaosocial = $phpArray->CTe->infCte->ide->toma4[0]->xNome;
                                    $operacao->cnpjsacado =  $phpArray->CTe->infCte->ide->toma4[0]->CNPJ;
                                    $operacao->numero = $phpArray->CTe->infCte->ide[0]->nCT;
                                    $operacao->vlrface = $phpArray->CTe->infCte->vPrest[0]->vRec;
                                    $operacao->endop = $phpArray->CTe->infCte->ide->toma4->enderToma[0]->xLgr;
                                    $operacao->bairro = $phpArray->CTe->infCte->ide->toma4->enderToma[0]->xBairro;
                                    $operacao->cidade = $phpArray->CTe->infCte->ide->toma4->enderToma[0]->xMun;
                                    $operacao->estado = $phpArray->CTe->infCte->ide->toma4->enderToma[0]->UF;
                                    $operacao->cep = $phpArray->CTe->infCte->ide->toma4->enderToma[0]->CEP;
                                    $operacao->qtdetitulo = 1;
                                    $operacao->status = 0;
                                }
                                else
                                {
                                    $operacao->razaosocial = $phpArray->CTe->infCte->dest[0]->xNome;
                                    $operacao->cnpjsacado =  $phpArray->CTe->infCte->dest[0]->CNPJ;
                                    $operacao->numero = $phpArray->CTe->infCte->ide[0]->nCT;
                                    $operacao->vlrface = $phpArray->CTe->infCte->vPrest[0]->vRec;
                                    $operacao->endop = $phpArray->CTe->infCte->dest->enderDest[0]->xLgr;
                                    $operacao->bairro = $phpArray->CTe->infCte->dest->enderDest[0]->xBairro;
                                    $operacao->cidade = $phpArray->CTe->infCte->dest->enderDest[0]->xMun;
                                    $operacao->estado = $phpArray->CTe->infCte->dest->enderDest[0]->UF;
                                    $operacao->cep = $phpArray->CTe->infCte->dest->enderDest[0]->CEP;
                                    $operacao->qtdetitulo = 1;
                                    $operacao->status = 0;
                                }
                                $operacao->save();
                            }

                            else if(is_object($listaNfse))
                            {
                                $operacao->razaosocial = $phpArray->ListaNfse->CompNfse->Nfse->InfNfse->DeclaracaoPrestacaoServico->InfDeclaracaoPrestacaoServico->Tomador[0]->RazaoSocial;
                                $operacao->cnpjsacado =  $phpArray->ListaNfse->CompNfse->Nfse->InfNfse->DeclaracaoPrestacaoServico->InfDeclaracaoPrestacaoServico->Tomador->IdentificacaoTomador->CpfCnpj[0]->Cnpj;
                                $operacao->numero = $phpArray->ListaNfse->CompNfse->Nfse->InfNfse[0]->Numero;
                                $operacao->vlrface = $phpArray->ListaNfse->CompNfse->Nfse->InfNfse->DeclaracaoPrestacaoServico->InfDeclaracaoPrestacaoServico->Servico->Valores[0]->ValorServicos;
                                $operacao->endop = $phpArray->ListaNfse->CompNfse->Nfse->InfNfse->DeclaracaoPrestacaoServico->InfDeclaracaoPrestacaoServico->Tomador->Endereco[0]->Endereco;
                                $operacao->bairro = $phpArray->ListaNfse->CompNfse->Nfse->InfNfse->DeclaracaoPrestacaoServico->InfDeclaracaoPrestacaoServico->Tomador->Endereco[0]->Bairro;
                                $operacao->cep = $phpArray->ListaNfse->CompNfse->Nfse->InfNfse->DeclaracaoPrestacaoServico->InfDeclaracaoPrestacaoServico->Tomador->Endereco[0]->Cep;
                                $operacao->estado = $phpArray->ListaNfse->CompNfse->Nfse->InfNfse->DeclaracaoPrestacaoServico->InfDeclaracaoPrestacaoServico->Tomador->Endereco[0]->Uf;
                                $cep = $phpArray->ListaNfse->CompNfse->Nfse->InfNfse->DeclaracaoPrestacaoServico->InfDeclaracaoPrestacaoServico->Tomador->Endereco[0]->Cep;
                                $zipCodeInfo = ZipCode::find($cep);
                                $arr = $zipCodeInfo->getArray();
                                $operacao->cidade = $arr['localidade'];
                                $operacao->qtdetitulo = 1;
                                $operacao->status = 0;
                                $operacao->save();
                            }

                            else if(is_object($nfse))
                            {
                                $operacao->razaosocial = $phpArray->Nfse->InfNfse->DeclaracaoPrestacaoServico->InfDeclaracaoPrestacaoServico->Tomador[0]->RazaoSocial;
                                $operacao->cnpjsacado =  $phpArray->Nfse->InfNfse->DeclaracaoPrestacaoServico->InfDeclaracaoPrestacaoServico->Tomador->IdentificacaoTomador->CpfCnpj[0]->Cnpj;
                                $operacao->numero = $phpArray->Nfse->InfNfse[0]->Numero;
                                $operacao->vlrface = $phpArray->Nfse->InfNfse->DeclaracaoPrestacaoServico->InfDeclaracaoPrestacaoServico->Servico->Valores[0]->ValorServicos;
                                $operacao->endop = $phpArray->Nfse->InfNfse->DeclaracaoPrestacaoServico->InfDeclaracaoPrestacaoServico->Tomador->Endereco[0]->Endereco;
                                $operacao->bairro = $phpArray->Nfse->InfNfse->DeclaracaoPrestacaoServico->InfDeclaracaoPrestacaoServico->Tomador->Endereco[0]->Bairro;
                                $codigo_cidade = $phpArray->Nfse->InfNfse->DeclaracaoPrestacaoServico->InfDeclaracaoPrestacaoServico->Tomador->Endereco[0]->CodigoMunicipio;
                                $municipio = DB::table('municipios')->where('codigo_ibge', '=', $codigo_cidade)->get();
                                $operacao->cidade = $municipio[0]->nome;
                                $operacao->estado = $phpArray->Nfse->InfNfse->DeclaracaoPrestacaoServico->InfDeclaracaoPrestacaoServico->Tomador->Endereco[0]->Uf;
                                $operacao->cep = $phpArray->Nfse->InfNfse->DeclaracaoPrestacaoServico->InfDeclaracaoPrestacaoServico->Tomador->Endereco[0]->Cep;
                                $operacao->qtdetitulo = 1;
                                $operacao->status = 0;
                                $operacao->save();
                            }
                        else
                        {
                            return redirect()->route('clientes.create')->with('mensagem2', 'Borderô criado!');
                        }
                }
        }
  
        if($request->hasFile('pdf'))
        {
           $files_pdf = $request->file('pdf');
           array_push ($files_array, $files_pdf);
        }
    
        if(count($files_array)>0)
        {
            ClienteController::moveFileToFolder($files_array, $request->contratante, $bordero->id);
        }

        return redirect()->route('clientes.edit', $bordero->id );
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
        return view ('clientes.edit', compact('bordero', 'operacoes', 'dado'));
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

            ], $mensagens);

        $qtdtitulos = count($request['tipotitulo']);

        for($i = 0; $i<count($request['tipotitulo']); $i++)
        {
            $valorface = str_replace(',', '.',$request['vlrface'][$i]);

            Operacao::where('id', '=', $request['operacao_id'][$i])
            ->update([
                'razaosocial' => $request['razaosocial'][$i],
                'tipotitulo' => $request['tipotitulo'][$i], 
                'emailoperacao' => $request['emailoperacao'][$i],
                'ddd' => $request['ddd'][$i],
                'telefone' => $request['telefone'][$i],
                'vcto' => $request['vcto'][$i],
                'vlrface' => $valorface,
                'updated_at' => new \DateTime()
            ]);
        }
        
        $request->mod_totalvlrface = str_replace(',', '.',$request->mod_totalvlrface);

        if($request->mod_totalvlrface = " ")
        {
            $valorfacefinal =  $valorface;
        }
        else{
            $valorfacefinal = $request->mod_totalvlrface;
        }
        
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


