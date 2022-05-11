@extends('layouts.home')
@section('content')
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
                <form action="{{ route('clientes.update', $bordero['id']) }}" method="post" class="needs-validation"
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
                            <tr>
                                <td>
                                    <label class="strong_title">Valor Total Face Original: </label> R$ <?php $valor = str_replace('.', ',', $dado['totalvlrface']); echo $valor;?>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <input type="hidden" name="totalvlrface" value="{{$dado['totalvlrface']}}">

                    <em class="required_input font-required">DEMONSTRATIVO DE CÁLCULO DA OPERAÇÃO *</em>
                    <?php $i = 1; ?>
                    @foreach($operacoes as $operacao)
                    <div class="novo-titulo">
                        <h6><strong>Título <?php echo $i; ?> </strong></h6>
                        <input type="hidden" name="operacao_id[]" value="{{$operacao->id}}">
                        <div class="form-row top-padding">
                            <div class="col-md-6">
                                <div class="position-relative form-group"><label for="razaosocial[]"
                                        class="">Razão
                                        Social/Nome
                                        <span class="required_input">*</span></label><input
                                        name="razaosocial[]" id="razaosocial[]"
                                        placeholder="Digite aqui a razão social" type="text" class="form-control"
                                        value="{{ old('razaosocial[]', $operacao->razaosocial) }}" required autofocus
                                        >
                                    <div class="invalid-feedback">
                                        Por favor, informe a Razão Social/Nome .
                                    </div>
                                    <div class="valid-feedback">
                                        Parece OK!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group"><label for="cnpjsacado[]" class="">CNPJ do
                                        Sacado
                                        <span class="required_input">*</span></label><input name="cnpjsacado[]"
                                        id="cnpjsacado[]" placeholder="Digite aqui o CNPJ do Sacado" type="text"
                                        class="form-control" value="{{ old('cnpjsacado[]', $operacao->cnpjsacado) }}"
                                        required>
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
                                        type="number" class="form-control"
                                        value="{{ old('numero[]', $operacao->numero) }}" required >
                                    <div class="invalid-feedback">
                                        Por favor, o número do título.
                                    </div>
                                    <div class="valid-feedback">
                                        Parece OK!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group"><label for="tipotitulo[]"
                                        class="">Tipo do título<span class="required_input">*</span></label>
                                    <select class="form-control combobox" id="tipotitulo[]"
                                        name="tipotitulo[]" required autofocus>
                                        <option value="{{ old('tipotitulo[]') }}" disabled selected>Selecionar tipo do título
                                        </option>
                                        <option value="Cartão de Crédito">Cartão de Crédito</option>
                                        <option value="Cédula de Crédito Bancário">Cédula de Crédito Bancário</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="Contrato Futuro">Contrato Futuro</option>
                                        <option value="Cédula de Produto Rural">Cédula de Produto Rural</option>
                                        <option value="Conhecimento de Transporte">Conhecimento de Transporte</option>
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
                                <div class="position-relative form-group"><label for="vcto[]"
                                        class="">Vencimento
                                        <span class="required_input">*</span></label><input name="vcto[]"
                                        id="vcto[]" placeholder="Digite aqui o vencimento" autofocus
                                        type="date" class="form-control"
                                        value="{{ old('vcto[]', $operacao->vcto )}}" required
                                        >
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
                                        <input name="vlrface[]"
                                        id="vlrface[]" placeholder="Digite o valor face"
                                        type="text" class="form-control vlrface numeric"
                                        <?php $operacao->vlrface = str_replace('.', ',', $operacao->vlrface);?>
                                        value="{{ old('vlrface[]', $operacao->vlrface) }}" required >
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
                                        id="qtdetitulo[]" placeholder="Digite aqui a quantidade"
                                        type="number" class="form-control"
                                        value="{{ old('qtdetitulo[]', $operacao->qtdetitulo) }}"  required>
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
                            <div class="col-md-12">
                                <div class="position-relative form-group"><label for="endop[]"
                                        class="">Endereço<span class="required_input">*</span></label><input
                                        name="endop[]" id="endop[]" placeholder="Digite o endereço"
                                        type="text" class="form-control"
                                        value="{{ old('endop[]', $operacao->endop . ' , ' . $operacao->bairro. ' , ' . $operacao->cidade. ' , ' . $operacao->estado. ' , ' . $operacao->cep ) }}" required>
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
                            <div class="col-md-12">
                                <div class="position-relative form-group"><label for="emailoperacao" class="">E-mail<span
                                            class="required_input">*</span></label><input name="emailoperacao[]" id="emailoperacao"
                                        placeholder="Digite o endereço de E-mail" type="email" class="form-control"
                                        value="{{ old('emailoperacao[]'), $operacao->emailoperacao }}" required>
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
                            <div class="col-md-6">
                                <div class="position-relative form-group"><label for="ddd[]" class="">DDD<span
                                            class="required_input">*</span></label><input name="ddd[]"
                                        id="ddd[]" placeholder="Digite o DDD" type="number"
                                        class="form-control" value="{{ old('ddd[]', $operacao->ddd) }}"
                                        required>
                                    <div class="invalid-feedback">
                                        Por favor, informe o DDD
                                    </div>
                                    <div class="valid-feedback">
                                        Parece OK!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group"><label for="telefone[]"
                                        class="">Telefone
                                        <span class="required_input">*</span></label><input name="telefone[]"
                                        id="telefone[]" placeholder="Digite aqui o número do telefone"
                                        type="text" class="form-control"
                                        value="{{ old('telefone[]', $operacao->telefone) }}" required>
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
                    <?php  $i++; ?>
                    @endforeach
                    <div class="form-row">
                        <table>
                            <tr>
                                <td>
                                    <label class="strong_title">Valor Total Face Original: </label> R$ <?php $valor = str_replace('.', ',', $dado['totalvlrface']); echo $valor;?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="strong_title" style="color:red;">Valor Total Face Modificado: </label><span id="vlrfacemod"> R$  </span>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <input type="hidden" id="mod_totalvlrface" name="mod_totalvlrface" value="">
       
                    <!--<a class="mt-2 btn btn-secondary" href="{{ old('back_to') ?: url()->previous() }}">Cancelar</a>-->
                    <button class="mt-2 btn btn-primary">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                style="display: none;">
                                                @csrf
</form>

<script src="{{ asset('assets/js/edit_titulo.js') }}"></script>
@endsection
