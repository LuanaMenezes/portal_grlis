@extends('layouts.home')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="{{ asset('assets/js/jquery.json.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.span_razao').on('change', function(e) {
            var elem = $(this);
            var id_name =  elem.attr('id');

            var parts = id_name.split(/[[\]]{1,2}/);
            parts.length--; // the last entry is dummy, need to take it out
            var id= parts[1]; // [id] 

            var razao_id = document.getElementById(id_name).value;
            var getUrl = window.location;
            var baseUrl2 = getUrl .protocol + "//" + getUrl.host + "/" ;

            if (razao_id) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                        type: 'POST',
                        url: baseUrl2+'bordero/getSacadoData',
                        data: "razao_id=" + razao_id,
                    })
                      .done(function(sacado) {
    
                        var strArray = sacado.split("$");
                        document.getElementById('cnpjsacado' + '['+id+']').value = strArray[0];
                        document.getElementById('cep' + '['+id+']').value = strArray[1];
                        document.getElementById('logradouro' + '['+id+']').value = strArray[2];
                        document.getElementById('bairro' + '['+id+']').value = strArray[3];
                        document.getElementById('cidade' + '['+id+']').value = strArray[4];
                        document.getElementById('estado' + '['+id+']').value = strArray[5];
                        document.getElementById('emailoperacao' + '['+id+']').value = strArray[6];
                        document.getElementById('ddd' + '['+id+']').value = strArray[7];
                        document.getElementById('telefone' + '['+id+']').value = strArray[8];
                    });
            }
        });
    });
</script>

<style>
    .swal-modal {
        width: 450px !important;
    }
</style>
<div id="banner-message">
    <div id="displayDiv"></div>
</div>
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-user text-info">
                </i>
            </div>
            <div><strong>Editar Dados dos Títulos</strong>
                <!--Registration closes in <span id="time">05:00</span> minutes!-->
            </div>
        </div>
    </div>
    @if($errors->any())
    <div class="card-footer">
        @foreach ($errors->all() as $error)
        <div class="alert alert-danger"><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
            <strong>Erro!</strong>
            <p>{{ $error }}</p>
        </div>
        @endforeach
    </div>
    @endif
    @if(session('mensagem'))
    <script>
        swal({
              title: 'Atenção',
              text: 'Não foi possível salvar o(s) arquivo(s) na pasta.\n Contate o administrador do sistema.',
              icon: 'warning',
          });
    </script>
    @endif
    @if(session('mensagem2'))
    <script>
        swal({
              title: 'Atenção',
              text: 'Tipo do arquivo pode estar inválido.\n Tente novamente ou contate o administrador do sistema.',
              icon: 'warning',
          });
    </script>
    @endif
</div>
<div class="alert alert-warning"><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
    <strong>Atenção!</strong>
    <p>Preencha todos os dados complementares para que as informações não sejam perdidas.</p>
</div>
<div class="tab-content">
    <em class="required_input font-required">Campo Obrigatório *</em>
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <form action="{{ route('borderos.update', $bordero['id']) }}" id="updateForm" method="post" class="needs-validation"
                    novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="back_to" value="{{ old('back_to') ?: url()->previous() }}">
                    <div class="form-row">
                        <table>
                            <tr>
                                <td>
                                    <em class="required_input font-required">DADOS DO BORDERÔ *</em>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="strong_title">Nome: </label> {{ $bordero->nome}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="strong_title">CNPJ Crédito: </label> {{ $bordero->cnpjcredito}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="strong_title">Data da Operação: </label>
                                    <?php
                                        $original_date = $bordero->dataop;
                                        // Creating timestamp from given date
                                        $timestamp = strtotime($original_date);
                                        // Creating new date format from that timestamp
                                        $new_date = date("d-m-Y", $timestamp);
                                        echo $new_date; // Outputs: 31-03-2019
                                    ?>
                                </td>
                            </tr>
                            <!--<tr>
                                <td>
                                    <label class="strong_title">Valor Total Face: </label> <span class="vlrfacemod"> R$
                                    </span>
                                </td>
                            </tr>-->
                        </table>
                    </div>

                    <em class="required_input font-required">DEMONSTRATIVO DE CÁLCULO DA OPERAÇÃO *</em>

                    <div class="novo-titulo">
                        <div class="element" id="luana">
                            <div class="form-row top-padding">
                                <div class="col-md-6">
                                    <h6><strong>Título</strong></h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="button" style="text-align: right;">
                                        <a class="btn remove btn-danger disabled" href="#">
                                            <i class="fas fa-minus-circle"></i> Remover Título
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="operacao_id[]" value="{{ old('operacao_id[]')}}">
                            <div class="form-row">
                                <br>
                                <div class="col-md-6">
                                    <div class="position-relative form-group"><label for="razaosocial[]" class="">Razão Social/Nome
                                            <span class="required_input">*</span></label>
                                        <select class="form-control span_razao" id="razaosocial[1]" name="razaosocial[]">
                                            <option value="{{ old('razaosocial[]') }}" disabled selected>Selecione a razão social</option>
                                            @foreach($sacados as $sacado)
                                            <option value="{{$sacado->id}}" data-codigo="{{$sacado->id}}">{{$sacado->razao_social}}</option>
                                            @endforeach
                                        </select>
        
                                        <div class="invalid-feedback">
                                            Por favor, informe o nome da razão social
                                        </div>
                                        <div class="valid-feedback">
                                            Parece OK!
                                        </div>
                                    </div>
                                </div>
                                <!--<div class="col-md-6">
                                    <div class="position-relative form-group"><label for="razaosocial[]" class="">Razão
                                            Social/Nome
                                            <span class="required_input">*</span></label><input name="razaosocial[]"
                                            id="razaosocial[]" placeholder="Digite aqui a razão social" autofocus
                                            type="text" class="form-control" value="{{ old('razaosocial[]') }}"
                                            required>
                                        <div class="invalid-feedback">
                                            Por favor, informe a Razão Social/Nome .
                                        </div>
                                        <div class="valid-feedback">
                                            Parece OK!
                                        </div>
                                    </div>
                                </div>-->
                                <div class="col-md-6">
                                    <div class="position-relative form-group"><label for="cnpjsacado[]" class="">CNPJ do
                                            Sacado
                                            <span class="required_input">*</span></label><input name="cnpjsacado[]"
                                            id="cnpjsacado[1]" placeholder="Digite aqui o CNPJ do Sacado" type="text" readonly
                                            class="form-control" value="{{ old('cnpjsacado[]') }}" required>
                                        <div class="invalid-feedback">
                                            Por favor, o CNPJ do sacado.
                                        </div>
                                        <div class="valid-feedback">
                                            Parece OK!
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group"><label for="numero[]" class="">Nº do
                                            título
                                            <span class="required_input">*</span></label><input name="numero[]"
                                            id="numero[]" placeholder="Digite aqui o número do título" autofocus
                                            type="number" class="form-control" value="{{ old('numero[]') }}" required>
                                        <div class="invalid-feedback">
                                            Por favor, o número do título.
                                        </div>
                                        <div class="valid-feedback">
                                            Parece OK!
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group"><label for="tipotitulo[]" class="">Tipo do
                                            título<span class="required_input">*</span></label>
                                        <select class="form-control" id="tipotitulo[]" name="tipotitulo[]"
                                            required>
                                            <option value="{{ old('tipotitulo[]') }}" disabled selected>Selecione
                                            </option>
                                            <option value="Cartão de Crédito">Cartão de Crédito</option>
                                            <option value="Cédula de Crédito Bancário">Cédula de Crédito Bancário
                                            </option>
                                            <option value="Cheque">Cheque</option>
                                            <option value="Contrato Futuro">Contrato Futuro</option>
                                            <option value="Cédula de Produto Rural">Cédula de Produto Rural</option>
                                            <option value="Conhecimento de Transporte">Conhecimento de Transporte
                                            </option>
                                            <option value="Duplicata Mercantil">Duplicata Mercantil</option>
                                            <option value="Duplicata de Serviço">Duplicata de Serviço</option>
                                            <option value="Nota Promissória">Nota Promissória</option>
                                            <option value="Parcela de Contrato">Parcela de Contrato</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Por favor, informe o tipo do título
                                        </div>
                                        <div class="valid-feedback">
                                            Parece OK!
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group"><label for="vcto[]" class="">Vencimento
                                            <span class="required_input">*</span></label><input name="vcto[]"
                                            id="vcto[]" placeholder="Digite aqui o vencimento" autofocus type="date"
                                            class="form-control" value="{{ old('vcto[]')}}" required>
                                        <div class="invalid-feedback">
                                            Por favor, informe o vencimento
                                        </div>
                                        <div class="valid-feedback">
                                            Parece OK!
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group"><label for="vlrface[]" class="">Valor
                                            Face<span class="required_input">*</span></label>
                                        <input name="vlrface[]" id="vlrface[]" placeholder="Digite o valor face"
                                            type="text" class="form-control vlrface numeric"
                                            value="{{ old('vlrface[]') }}" required>
                                        <div class="invalid-feedback">
                                            Por favor, informe o valor face
                                        </div>
                                        <div class="valid-feedback">
                                            Parece OK!
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group"><label for="qtdetitulo[]"
                                            class="">Quantidade
                                            <span class="required_input">*</span></label><input name="qtdetitulo[]"
                                            id="qtdetitulo[]" placeholder="Digite aqui a quantidade" type="number"
                                            class="form-control numeric" value="{{ old('qtdetitulo[]') }}" required>
                                        <div class="invalid-feedback">
                                            Por favor, informe a quantidade
                                        </div>
                                        <div class="valid-feedback">
                                            Parece OK!
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="position-relative form-group"><label for="cep[]"
                                            class="">CEP<span class="required_input">*</span></label><input
                                            name="cep[]" id="cep[1]" placeholder="Digite o CEP" type="text"
                                            class="form-control" value="{{ old('cep[]') }}" required>
                                        <div class="invalid-feedback">
                                            Por favor, informe o CEP
                                        </div>
                                        <div class="valid-feedback">
                                            Parece OK!
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="position-relative form-group"><label for="logradouro[]"
                                            class="">Endereço<span class="required_input">*</span></label><input
                                            name="logradouro[]" id="logradouro[1]" placeholder="Digite o endereço" type="text"
                                            class="form-control" value="{{ old('logradouro[]') }}" required>
                                        <div class="invalid-feedback">
                                            Por favor, informe o endereço
                                        </div>
                                        <div class="valid-feedback">
                                            Parece OK!
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="position-relative form-group"><label for="bairro[]"
                                            class="">Bairro<span class="required_input">*</span></label><input
                                            name="bairro[]" id="bairro[1]" placeholder="Digite o bairro" type="text"
                                            class="form-control" value="{{ old('bairro[]') }}" required>
                                        <div class="invalid-feedback">
                                            Por favor, informe o bairro
                                        </div>
                                        <div class="valid-feedback">
                                            Parece OK!
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group"><label for="cidade[]"
                                            class="">Cidade<span class="required_input">*</span></label><input
                                            name="cidade[]" id="cidade[1]" placeholder="Digite o cidade" type="text"
                                            class="form-control" value="{{ old('cidade[]') }}" required>
                                        <div class="invalid-feedback">
                                            Por favor, informe o cidade
                                        </div>
                                        <div class="valid-feedback">
                                            Parece OK!
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group"><label for="estado[]"
                                            class="">Estado<span class="required_input">*</span></label>
                                            <select class="form-control" id="estado[1]" name="estado[]" required>
                                                <option value="{{ old('estado[]') }}" disabled selected>Selecione
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
                                                <option value="EX">Estrangeiro</option>
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
                                    <div class="position-relative form-group"><label for="emailoperacao"
                                            class="">E-mail<span class="required_input">*</span></label><input
                                            name="emailoperacao[]" id="emailoperacao[1]"
                                            placeholder="Digite o endereço de E-mail" type="email" class="form-control"
                                            value="{{ old('emailoperacao[]')}}" required>
                                        <div class="invalid-feedback">
                                            Por favor, informe o endereço de e-mail
                                        </div>
                                        <div class="valid-feedback">
                                            Parece OK!
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group"><label for="ddd[]" class="">DDD<span
                                                class="required_input">*</span></label><input name="ddd[]" id="ddd[1]"
                                            placeholder="Digite o DDD" type="number" class="form-control numeric"
                                            value="{{ old('ddd[]') }}" required>
                                        <div class="invalid-feedback">
                                            Por favor, informe o DDD
                                        </div>
                                        <div class="valid-feedback">
                                            Parece OK!
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group"><label for="telefone[]" class="">Telefone
                                            <span class="required_input">*</span></label><input name="telefone[]"
                                            id="telefone[1]" placeholder="Digite aqui o número do telefone" type="text"
                                            class="form-control numeric" value="{{ old('telefone[]') }}" required>
                                        <div class="invalid-feedback">
                                            Por favor, informe o telefone
                                        </div>
                                        <div class="valid-feedback">
                                            Parece OK!
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="results"></div>
                        <!--<a class="clone btn btn-warning" href="#">
                            <i class="fas fa-plus-circle"></i> Novo Título
                        </a>-->
                        <div class="button btn-titulo" style="margin-top:10px; margin-bottom:20px;">
                            <a class="clone btn btn-warning" href="#" style="color:white;">
                                <i class="fas fa-plus-circle"></i> Novo Título
                            </a>
                        </div>
                    </div>

                    <!-- <div class="button btn-titulo" style="margin-top:10px; margin-bottom:20px;">
                        <a class="clone btn btn-warning" href="#">
                            <i class="fas fa-plus-circle"></i> Novo Título
                        </a>
                    </div>-->
                    <div class="form-row">
                        <table>
                            <tr>
                                <td>
                                    <label class="strong_title">Quantidade de títulos: </label><span id="totaltitulos"> 
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="form-row">
                        <table>
                            <tr>
                                <td>
                                    <label class="strong_title">Valor Total Face: </label><span id="vlrfacemod"> R$
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="form-group row">
                        <div class="center">
                            {!! NoCaptcha::renderJs() !!}
                            {!! NoCaptcha::display() !!}
                        </div>
                    </div>

                    <input type="hidden" id="mod_totalvlrface" name="mod_totalvlrface" value="">
                    
                    <button class="mt-2 btn btn-primary update-confirm">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<script src="{{ asset('assets/js/edit_titulo.js') }}"></script>
<script src="{{ asset('assets/js/create_bordero.js') }}"></script>
@endsection