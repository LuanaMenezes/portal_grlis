@extends('layouts.admin')
@section('content')
<style>
    .swal-modal {
        width: 350px !important;
    }
</style>
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-user text-info">
                </i>
            </div>
            <div><strong>Novo Cliente</strong>
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
              text: 'Cliente cadastrado!',
              icon: 'success',
          });
    </script>
    @endif
</div>
<div class="tab-content">
    <em class="required_input font-required">Campo Obrigatório *</em>
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <form action="{{ route('clientes.store') }}" method="post" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="back_to" value="{{ old('back_to') ?: url()->previous() }}">
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="nome" class="">Nome/Razão
                                    Social <span class="required_input">*</span></label><input name="nome" id="nome"
                                    placeholder="Digite aqui o nome" autofocus type="text" class="form-control"
                                    value="{{ old('nome') }}" required>
                                <div class="invalid-feedback">
                                    Por favor, informe o nome/razão social.
                                </div>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="cnpj" class="">CNPJ <span
                                        class="required_input">*</span></label><input name="cnpj" id="cnpj"
                                    placeholder="Digite aqui o CNPJ" type="text" class="form-control" required
                                    value="{{ old('cnpj') }}">
                                <div class="invalid-feedback">
                                    Por favor, informe o CNPJ.
                                </div>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="senha" class="">Senha <span
                                        class="required_input">*</span></label><input name="senha" id="senha"
                                    placeholder="Digite aqui a senha" type="password" class="form-control"
                                    value="{{ old('senha') }}" required>
                                <div class="invalid-feedback">
                                    Por favor, informe a senha.
                                </div>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="cga" class="">CGA/Inscrição
                                    Municipal <span class="required_input">*</span></label><input name="cga" id="cga"
                                    id="Digite aqui o CGA" placeholder="Informe o CGA" value="{{ old('cga') }}"
                                    type="text" class="form-control" required>
                                <div class="invalid-feedback">
                                    Por favor, informe o CGA/Inscrição Municipal.
                                </div>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="uniprofissional"
                                    class="">Uniprofissional? <span class="required_input">*</span></label><select
                                    class="form-control" id="uniprofissional" name="uniprofissional" required>
                                    <option value="N" @if (old('uniprofissional')=="N" ) {{ 'selected' }} @endif>Não
                                    </option>
                                    <option value="S" @if (old('uniprofissional')=="S" ) {{ 'selected' }} @endif>Sim
                                    </option>
                                </select>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" id="socios" style="display: none;">
                            <div class="position-relative form-group"><label for="qtd_socios" class="">Quantidade de
                                    sócios</label><input name="qtd_socios" id="qtd_socios" type="number"
                                    value="{{ old('qtd_socios') }}" class="form-control"
                                    placeholder="Digite aqui a quantidade de sócios">
                                <div class="invalid-feedback">
                                    Por favor, informe a quantidade de sócios.
                                </div>
                                <div class="valid-feedback">
                                    Parece OK!
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="mt-2 btn btn-secondary" href="{{ old('back_to') ?: url()->previous() }}">Cancelar</a>
                    <button class="mt-2 btn btn-primary">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
  $(cnpj).inputmask("99.999.999/9999-99");  //static mask
  $(cga).inputmask("999.999/999-99");
});
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();

$( "#uniprofissional" ).change(function() {
  var valor = $("#uniprofissional" ).val();
  if(valor == 'N')
  {
    $("#socios").css("display", "none");
    $("#qtd_socios").val("");
    $('#qtd_socios').attr('required', false);
  }
  else
  {
    $("#socios").css("display", "inline");
    $('#qtd_socios').attr('required', true);
  }
});

$( window ).load(function() {
    var qtd_socios = $("#qtd_socios" ).val();

  if(qtd_socios == '')
  {
    $("#socios").css("display", "none");
    $("#qtd_socios").val("");
    $('#qtd_socios').attr('required', false);
  }
  else
  {
    $("#socios").css("display", "inline");
    $('#qtd_socios').attr('required', true);
  }

});

</script>
@endsection