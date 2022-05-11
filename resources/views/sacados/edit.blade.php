@extends('layouts.home')
@section('content')
<style>
    .swal-modal {
        width: 500px !important;
    }
</style>
<div id="banner-message">
    <div id="displayDiv"></div>
</div>
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-news-paper text-info">
                </i>
            </div>
            <div><strong>Editar Sacado</strong>
            </div>
        </div>
    </div>
    @if($errors->any())
    <div class="card-footer">
        @foreach ($errors->all() as $error)
        <div class="alert alert-danger" role="alert">
            {{ $error }}
        </div>
        @endforeach
    </div>
    @endif
    @if(session('mensagem'))
    <script>
        swal({
              title: 'Sucesso',
              text: 'Sacado  atualizado!',
              icon: 'success',
          });
    </script>
    @endif
    <script>
        function buscarcep(){
   
    var cep = document.getElementById("cep").value.replace("/\D/g, ''"),
    ajax = new XMLHttpRequest();
    
    //Validação para saber se apenas números foram digitados
    if( isNaN(cep) || cep.length != 8 ){
        swal({
              title: '',
              text: 'Por favor, informe um CEP válido!',
              icon: 'warning',
          });
        return false;
    }
    
    if(ajax){
    
        ajax.onreadystatechange = function() {
            // Verifico se a requisição do servidor já chegou
            if (ajax.readyState == 4) {
                if(ajax.status == 200 || ajax.status == 304){
                    //alert('Chegou a requisição feita ao servidor');
                    
                    //Pego o retorno da requisição | String sem encode
                    var resposta = ajax.responseText;
                    
                    //Parse para JSON e me retorna um OBJ JavaScript
                    var resposta = JSON.parse(ajax.responseText);
                    
                    document.getElementById('endereco').value= resposta.logradouro;
                    document.getElementById('bairro').value= resposta.bairro;
                    document.getElementById('cidade').value= resposta.localidade;
                    $("#estado").val(resposta.uf).change();
                }
            }
        };
        
        /**
        * Começa a requisição Ajax informando:
        * Método: GET
        * URL: http://cep.correiocontrol.com.br/MEU_CEP.json
        * Assinc: false
        */
        ajax.open( "GET","https://viacep.com.br/ws/"+cep+"/json/",false);
        
        // Não é enviado qualquer tipo de dado ao servidor
        ajax.send( null );
    }
}
    </script>

</div>
<div class="tab-content">
    <em class="required_input font-required">CAMPOS OBRIGATÓRIOS *</em>
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <form action="{{ route('sacados.update', $sacado->id) }}" method="post" class="needs-validation"
                novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="back_to" value="{{ old('back_to') ?: url()->previous() }}">
                    <em class="required_input font-required">DADOS DO SACADO *</em>
                    <div class="form-row">
                        <br>
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="contratante" class="">Razão Social:
                                    <span class="required_input">*</span></label><input name="razao_social"
                                    id="razao_social" placeholder="Digite aqui o nome/razão social" type="text"
                                    autofocus class="form-control" required value="{{old('razao_social', $sacado->razao_social)}}"
                                    ">
                                <div class="invalid-feedback">
                                    Por favor, informe a razão social
                                </div>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="cnpj" class="">CNPJ:
                                    <span class="required_input">*</span></label><input name="cnpj" id="cnpj"
                                    placeholder="Digite aqui o CNPJ" type="text" class="form-control" readonly required
                                    value="{{ $sacado->cnpj}}">
                                <div class="invalid-feedback">
                                    Por favor, informe o CNPJ do sacado.
                                </div>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="position-relative form-group"><label for="totalvlrface" class="">CEP:
                                    <span class="required_input">*</span></label><input name="cep" id="cep"
                                    placeholder="Digite aqui o CEP" type="text" class="form-control"
                                    value="{{old('cep', $sacado->cep)}}">
                                <div class="invalid-feedback">
                                    Por favor, o digite o CEP.
                                </div>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>

                        </div>
                        <div class="col-md-3">
                            <button class="btn  btn-light" type="button" onclick="buscarcep()"
                                style="margin-top:30px;">Procurar Endereço</button>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group"><label for="endereco" class="">Endereço:
                                    <span class="required_input">*</span></label><input name="endereco" id="endereco"
                                    placeholder="Digite aqui o nome da rua, avenida .." type="text" class="form-control"
                                    value="{{old('endereco', $sacado->endereco)}}" required>
                                <div class="invalid-feedback">
                                    Por favor, informe o endereço.
                                </div>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="position-relative form-group"><label for="endereco" class="">Bairro:
                                    <span class="required_input">*</span></label><input name="bairro" id="bairro"
                                    placeholder="Digite aqui o nome do bairro" type="text" class="form-control"
                                    value="{{old('bairro', $sacado->bairro)}}" required>
                                <div class="invalid-feedback">
                                    Por favor, informe o bairro.
                                </div>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group"><label for="endereco" class="">Cidade:
                                    <span class="required_input">*</span></label><input name="cidade" id="cidade"
                                    placeholder="Digite aqui o nome da cidade" type="text" class="form-control"
                                    value="{{old('cidade', $sacado->cidade)}}" required>
                                <div class="invalid-feedback">
                                    Por favor, informe a cidade.
                                </div>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group"><label for="estado" class="">Estado<span
                                        class="required_input">*</span></label>
                                <select class="form-control" id="estado" name="estado" required>
                                    <option disabled selected>Selecione
                                    </option>
                                    <option {{ $sacado->estado == 'AC' ? 'selected' : '' }} value="AC">Acre</option>
                                    <option {{ $sacado->estado == 'AL' ? 'selected' : '' }} value="AL">Alagoas</option>
                                    <option {{ $sacado->estado == 'AP' ? 'selected' : '' }} value="AP">Amapá</option>
                                    <option {{ $sacado->estado == 'AM' ? 'selected' : '' }} value="AM">Amazonas</option>
                                    <option {{ $sacado->estado == 'BA' ? 'selected' : '' }} value="BA">Bahia</option>
                                    <option {{ $sacado->estado == 'CE' ? 'selected' : '' }} value="CE">Ceará</option>
                                    <option {{ $sacado->estado == 'DF' ? 'selected' : '' }} value="DF">Distrito Federal</option>
                                    <option {{ $sacado->estado == 'ES' ? 'selected' : '' }} value="ES">Espírito Santo</option>
                                    <option {{ $sacado->estado == 'GO' ? 'selected' : '' }} value="GO">Goiás</option>
                                    <option {{ $sacado->estado == 'MA' ? 'selected' : '' }} value="MA">Maranhão</option>
                                    <option {{ $sacado->estado == 'MT' ? 'selected' : '' }} value="MT">Mato Grosso</option>
                                    <option {{ $sacado->estado == 'MS' ? 'selected' : '' }} value="MS">Mato Grosso do Sul</option>
                                    <option {{ $sacado->estado == 'MG' ? 'selected' : '' }} value="MG">Minas Gerais</option>
                                    <option {{ $sacado->estado == 'PA' ? 'selected' : '' }} value="PA">Pará</option>
                                    <option {{ $sacado->estado == 'PB' ? 'selected' : '' }} value="PB">Paraíba</option>
                                    <option {{ $sacado->estado == 'PR' ? 'selected' : '' }} value="PR">Paraná</option>
                                    <option {{ $sacado->estado == 'PE' ? 'selected' : '' }} value="PE">Pernambuco</option>
                                    <option {{ $sacado->estado == 'PI' ? 'selected' : '' }} value="PI">Piauí</option>
                                    <option {{ $sacado->estado == 'RJ' ? 'selected' : '' }} value="RJ">Rio de Janeiro</option>
                                    <option {{ $sacado->estado == 'RN' ? 'selected' : '' }} value="RN">Rio Grande do Norte</option>
                                    <option {{ $sacado->estado == 'RS' ? 'selected' : '' }} value="RS">Rio Grande do Sul</option>
                                    <option {{ $sacado->estado == 'RO' ? 'selected' : '' }} value="RO"> Rondônia</option>
                                    <option {{ $sacado->estado == 'RR' ? 'selected' : '' }} value="RR">Roraima</option>
                                    <option {{ $sacado->estado == 'SC' ? 'selected' : '' }} value="SC">Santa Catarina</option>
                                    <option {{ $sacado->estado == 'SP' ? 'selected' : '' }} value="SP">São Paulo</option>
                                    <option {{ $sacado->estado == 'SE' ? 'selected' : '' }} value="SE">Sergipe</option>
                                    <option {{ $sacado->estado == 'TO' ? 'selected' : '' }} value="TO">Tocantins</option>
                                </select>

                                <!--<input
                                    name="estado[]" id="estado[]" placeholder="Digite o estado" type="text"
                                    class="form-control" value="{{ old('estado[]') }}" required>-->
                                <div class="invalid-feedback">
                                    Por favor, informe o estado
                                </div>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group"><label for="email" class="">Email:
                                    <span class="required_input">*</span></label><input name="email" id="email"
                                    placeholder="Digite aqui o endereço de e-mail" type="text" class="form-control"
                                    value="{{old('email', $sacado->email)}}" required>
                                <div class="invalid-feedback">
                                    Por favor, informe o e-mail.
                                </div>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="ddd" class="">DDD<span
                                        class="required_input">*</span></label><input name="ddd" id="ddd"
                                    placeholder="Digite o DDD" type="number" class="form-control"
                                    value="{{old('ddd', $sacado->ddd)}}" required>
                                <div class="invalid-feedback">
                                    Por favor, informe o DDD
                                </div>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="telefone" class="">Telefone
                                    <span class="required_input">*</span></label><input name="telefone"
                                    id="telefone" placeholder="Digite aqui o número do telefone" type="number"
                                    class="form-control"  value="{{old('telefone', $sacado->telefone)}}" required>
                                <div class="invalid-feedback">
                                    Por favor, informe o telefone
                                </div>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="height:30px;"></div>
                    <a class="mt-2 btn btn-secondary" href="{{ old('back_to') ?: url()->previous() }}">Cancelar</a>
                    <button class="mt-2 btn btn-primary">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

@endsection