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
                <i class="pe-7s-cash text-info">
                </i>
            </div>
            <div><strong>Pagamentos à Terceiros</strong>
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
              text: 'Solicitação  cadastrada!',
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
                <form action="{{ route('terceiros.store') }}" method="post" class="needs-validation" novalidate
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="back_to" value="{{ old('back_to') ?: url()->previous() }}">
                    <input type="hidden" name="id" id="id" value="{{$id}}">
                    <div class="form-row">
                        <br>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <!--<label for="dataop" class="">Data da Operação:
                                    <span class="required_input">*</span></label><input name="dataop" id="dataop"
                                    placeholder="Digite aqui a data da operação" autofocus type="date"
                                    class="form-control" value="{{ old('dataop') }}" required>-->
                                <div class="w3-section">
                                    <label for="dataop" class="">Data da Operação:<span
                                            class="required_input">*</span></label>
                                    <input class="form-control" value="<?php echo date('Y-m-d');?>" type="date"
                                        name="dataop" id="dataop" required>
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
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group"><label for="obs" class="">Observações:</label>
                                <textarea name="obs" class="text1 form-control"
                                    placeholder="Digite aqui as observações" value="{{ old('obs') }}" rows="3"
                                    style="resize: none;"></textarea>
                                <small class="form-text text-muted"> </small>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                    </div>
                    <label>Selecione o(s) arquivo(s) :</label><span class="required_input">*</span>
                    <input type="file" class="multi" name="arquivo[]" multiple="multiple" accept="application/xml" />
                    <div style="height:20px;"></div>
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