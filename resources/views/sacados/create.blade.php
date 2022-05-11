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
            <div><strong>Novo Sacado</strong>
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
              text: 'Sacado  cadastrado!',
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
              text: 'Por favor, verifique se o CEP válido ou se tem somente números!',
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

                    var varErro = "erro";

                    if (resposta.includes(varErro)) 
                    {
                        swal({
                            title: '',
                            text: 'Por favor, informe um CEP válido!',
                            icon: 'warning',
                        });
                        return false;
                    } else 
                    {    
                        //Parse para JSON e me retorna um OBJ JavaScript
                        var resposta = JSON.parse(ajax.responseText);
                        document.getElementById('endereco').value= resposta.logradouro;
                        document.getElementById('bairro').value= resposta.bairro;
                        document.getElementById('cidade').value= resposta.localidade;
                        $("#estado").val(resposta.uf).change();
                    }
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
                <form action="{{ route('sacados.store') }}" method="post" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="back_to" value="{{ old('back_to') ?: url()->previous() }}">
                    <em class="required_input font-required">DADOS DO SACADO *</em>
                    <div class="form-row">
                        <br>
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="contratante" class="">Razão Social:
                                    <span class="required_input">*</span></label><input name="razao_social"
                                    id="razao_social" placeholder="Digite aqui o nome/razão social" type="text"
                                    autofocus class="form-control" required value="{{ old('razao_social')}}">
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
                                    placeholder="Digite aqui o CNPJ" type="number" class="form-control" required
                                    value="{{ old('cnpj')}}">
                                    <small class="form-text text-muted">Somente números</small>
                                <div class="invalid-feedback">
                                    Por favor, informe o CNPJ do sacado ou verifique se preencheu apenas com números.
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
                                    placeholder="Digite aqui o CEP" type="number" class="form-control" maxlength="8"
                                    value="{{ old('cep')}}">
                                    <small class="form-text text-muted">Somente números</small>
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
                                    value="{{ old('endereco') }}" required>
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
                                    value="{{ old('bairro') }}" required>
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
                                    value="{{ old('cidade') }}" required>
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
                                    <option value="{{ old('estado') }}" disabled selected>Selecione
                                    </option>
                                    <option value="AC">Acre</option>
                                    <option value="AL">Alagoas</option>
                                    <option value="AP">Amapá</option>
                                    <option value="AM">Amazonas</option>
                                    <option value="BA">Bahia</option>
                                    <option value="CE">Ceará</option>
                                    <option value="DF">Distrito Federal</option>
                                    <option value="ES">Espírito Santo</option>
                                    <option value="GO">Goiás</option>
                                    <option value="MA">Maranhão</option>
                                    <option value="MT">Mato Grosso</option>
                                    <option value="MS">Mato Grosso do Sul</option>
                                    <option value="MG">Minas Gerais</option>
                                    <option value="PA">Pará</option>
                                    <option value="PB">Paraíba</option>
                                    <option value="PR">Paraná</option>
                                    <option value="PE">Pernambuco</option>
                                    <option value="PI">Piauí</option>
                                    <option value="RJ">Rio de Janeiro</option>
                                    <option value="RN">Rio Grande do Norte</option>
                                    <option value="RS">Rio Grande do Sul</option>
                                    <option value="RO">Rondônia</option>
                                    <option value="RR">Roraima</option>
                                    <option value="SC">Santa Catarina</option>
                                    <option value="SP">São Paulo</option>
                                    <option value="SE">Sergipe</option>
                                    <option value="TO">Tocantins</option>
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
                                    value="{{ old('email') }}" required>
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
                                    value="{{ old('ddd') }}" required>
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
                                    class="form-control" value="{{ old('telefone') }}" required>
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