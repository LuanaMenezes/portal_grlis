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
            <div><strong>Novo Borderô</strong>
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
              text: 'Borderô  cadastrado!',
              icon: 'success',
          });
    </script>
    @endif
    @if(session('mensagem2'))
    <script>
        swal({
              title: 'Atenção',
              text: 'O arquivo XML pode estar inválido ou corrompido.\n Tente novamente ou contate o administrador do sistema.',
              icon: 'warning',
          });
    </script>
    @endif
</div>
<div class="tab-content">
    <em class="required_input font-required">CAMPOS OBRIGATÓRIOS *</em>
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <form action="{{ route('clientes.store') }}" method="post" class="needs-validation" novalidate
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="back_to" value="{{ old('back_to') ?: url()->previous() }}">
                    <em class="required_input font-required">DADOS DO BORDERÔ *</em>
                    <div class="form-row">
                        <br>
                        <div class="col-md-6">
                            <div class="position-relative form-group"><!--<label for="dataop" class="">Data da Operação:
                                    <span class="required_input">*</span></label><input name="dataop" id="dataop"
                                    placeholder="Digite aqui a data da operação" autofocus type="date"
                                    class="form-control" value="{{ old('dataop') }}" required>-->
                                    <div class="w3-section">
                                        <label for="dataop" class="">Data da Operação:<span class="required_input">*</span></label>
                                        <input class="form-control" value="<?php echo date('Y-m-d');?>" type="date" name="dataop" id="dataop" required>
                                    </div>
                                <div class="invalid-feedback">
                                    Por favor, informe a data da operação.
                                </div>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="totalvlrface" class="">Total Valor
                                    Face (R$):
                                    <span class="required_input">*</span></label><input name="totalvlrface"
                                    id="totalvlrface" placeholder="Valor total será calculado após inserção de títulos" type="text"
                                    class="form-control" value="{{ old('totalvlrface') , 0}}" readonly>
                                <div class="invalid-feedback">
                                    Por favor, o total valor face.
                                </div>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="qtddigitada" class="">Quantidade de
                                    Títulos:
                                    <span class="required_input">*</span></label><input name="qtddigitada"
                                    id="qtddigitada" placeholder="Digite aqui a quantidade total de títulos" autofocus
                                    type="number" class="form-control" value="{{ old('qtddigitada', 1) }}" required
                                    onkeypress='validate(event)' readonly>
                                <div class="invalid-feedback">
                                    Por favor, informe a quantidade digitada.
                                </div>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <br>
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="contratante" class="">Nome do
                                    Contratante:
                                    @foreach($contratante as $c)
                                    <span class="required_input">*</span></label><input name="contratante"
                                    id="contratante" placeholder="Digite aqui o contratante" type="text"
                                    class="form-control" value="{{ $c->nome }}" required readonly>
                                @endforeach
                                <div class="invalid-feedback">
                                    Por favor, informe o contratante:
                                </div>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="cnpjcontratante" class="">CNPJ do
                                    Contratante:
                                    @foreach($contratante as $c)
                                    <span class="required_input">*</span></label><input name="cnpjcontratante"
                                    id="cnpjcontratante" placeholder="Digite aqui o CNPJ do contratante" type="text"
                                    class="form-control" value="{{ $c->cnpj}}" required readonly>
                                @endforeach
                                <div class="invalid-feedback">
                                    Por favor, informe o CNPJ do contratante:
                                </div>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                    </div>
                    <em class="required_input font-required">CONTA PARA CRÉDITO *</em>

                    <div class="form-row">
                        <br>
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="nomebanco" class="">Banco
                                    <span class="required_input">*</span></label>
                                <select class="form-control combobox" id="banco_id" name="nomebanco">
                                    <option value="{{ old('nomebanco') }}" disabled selected>Selecione um banco</option>
                                    @foreach($bancos as $banco)
                                    <option value="{{$banco->Codigo}}" data-codigo="{{$banco->Codigo}}">{{$banco->Descricao}}</option>
                                    @endforeach
                                </select>

                                <div class="invalid-feedback">
                                    Por favor, informe o nome do banco
                                </div>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="numbanco" class="">Nº do Banco <span
                                        class="required_input">*</span></label><input name="numbanco" id="numbanco"
                                    placeholder="Número do banco" autofocus type="text" readonly class="form-control"
                                    value="{{ old('numbanco') }}" required>
                                <div class="invalid-feedback">
                                    Por favor, informe o numero do banco
                                </div>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="agencia" class="">Agência
                                    <span class="required_input">*</span></label><input name="agencia" id="agencia"
                                    placeholder="Digite aqui a agência bancária" autofocus type="text"
                                    class="form-control" value="{{ old('agencia') }}"  onkeypress='validate(event)' required>
                                <div class="invalid-feedback">
                                    Por favor, informe agência bancária
                                </div>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="contacorrente" class="">C/C<span class="required_input">*</span></label><input
                                    name="contacorrente" id="contacorrente" placeholder="Digite aqui a conta corrente"
                                    autofocus type="text" class="form-control" value="{{ old('contacorrente') }}"
                                    required>
                                <div class="invalid-feedback">
                                    Por favor, informe a conta corrente
                                </div>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="nome" class="">Nome <span
                                        class="required_input">*</span></label><input name="nome" id="nome"
                                    placeholder="Digite aqui o nome" type="text" class="form-control"
                                    value="{{ old('nome') }}" required>
                                <div class="invalid-feedback">
                                    Por favor, informe o nome
                                </div>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="cnpjcredito" class="">CNPJ para
                                    crédito:
                                    <span class="required_input">*</span></label><input name="cnpjcredito"
                                    id="cnpjcredito" placeholder="Digite aqui o CNPJ" type="text" class="form-control"
                                    required value="{{ old('cnpjcredito') }}">
                                <div class="invalid-feedback">
                                    Por favor, informe o CNPJ para creditar a operação:
                                </div>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="pixtipo" class="">Tipo de chave pix: </label>
                                    <select class="form-control combobox" id="pixtipo" name="pixtipo">
                                        <option value="{{ old('pixtipo') }}" disabled selected>Selecionar tipo de Chave Pix
                                        </option>
                                        <option>Aleatória</option>
                                        <option>Celular</option>
                                        <option>CPF/CNPJ</option>
                                        <option>E-mail</option>

                                    </select>
                                    <small class="form-text text-muted">Os nossos pagamentos obrigatoriamente ocorrem através do PIX


                                    </small>
                                <div class="invalid-feedback">
                                    Por favor, informe o tipo de chave
                                </div>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="pixchave" class="">Chave pix:</label><input name="pixchave"
                                    id="pixchave" placeholder="Digite aqui a chave pix" type="text" class="form-control"
                                    value="{{ old('pixchave') }}">
                                <div class="invalid-feedback">
                                    Por favor, informe a chave pix:
                                </div>

                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group"><label for="obs" class="">Observações:</label>
                                <textarea name="obs" class="text1 form-control"
                                    id="obs" placeholder="Digite aqui as observações"
                                    value="{{ old('obs') }}" rows="3" style="resize: none;"></textarea>
                                    <small class="form-text text-muted caracteres"> </small>
                                <span class="count1"></span>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                    </div>
                    <strong>Selecione o(s) arquivo(s) XML:</strong>
                    <input type="file" class="multi" name="arquivo[]" multiple="multiple" accept="application/xml"/>
                    <div style="height:20px;"></div>
                    <strong>Selecione o(s) arquivo(s) PDF:</strong>
                    <input type="file" class="multi" name="pdf[]" multiple="multiple" accept="application/pdf"/>
                    <div style="height:30px;"></div>
                    <a class="mt-2 btn btn-secondary" href="{{ old('back_to') ?: url()->previous() }}">Cancelar</a>
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
<script>
$(document).ready(function(){
  $("#totalvlrface").inputmask('decimal', {
                'alias': 'numeric',
                'groupSeparator': '.',
                'autoGroup': true,
                'digits': 2,
                'radixPoint': ",",
                'digitsOptional': true,
                'allowMinus': false,
                'prefix': '',
                'rightAlign': false,
                'placeholder': ''
    });
  $('.combobox').combobox();

  $('#banco_id').on('change',function(){
    var codigo = $(this).children('option:selected').data('codigo');
    $('#numbanco').val(codigo);
});
});
</script>
@endsection
