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
                <i class="pe-7s-news-paper text-info">
                </i>
            </div>
            <div><strong>Pesquisar Borderô</strong>
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
              text: 'Solicitação efetuada com sucesso',
              icon: 'success',
          });
    </script>
    @endif
</div>
<div class="col-md-12">
        <div class="main-card card">
            <div class="card-header">
                <!-- Search form -->
                <div class="input-group md-form form-sm form-2 pl-0">
                    <input class="form-control my-0 py-1 amber-border" autofocus type="text" placeholder="Pesquisar"
                        aria-label="Search" id="mySearchText">
                    <div class="input-group-append">
                        <span class="input-group-text amber lighten-3" id="mySearchButton"><i
                                class="fas fa-search text-grey" aria-hidden="true">
                            </i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <!-- Search form -->

</script>
@endsection