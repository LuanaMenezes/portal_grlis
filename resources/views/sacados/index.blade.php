@extends('layouts.home')
@section('content')
<link href="{{ asset('assets/css/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

<script src="{{ asset('assets/js/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/dataTables.bootstrap4.min.js') }}"></script>

<script src="{{ asset('assets/js/datatable/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/jszip.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/js/datatable/buttons.html5.min.js') }}"></script>
<link href="{{ asset('assets/css/datatable/buttons.dataTables.min.css') }}" rel="stylesheet">
<div style="margin-bottom: 30px;">
    <a href="{{route('sacados.create')}}" class="btn btn-primary"><i class="pe-7s-add-user" style="font-size: 19px;">
        </i> Adicionar Sacado</a>
</div>
<style>
    a:disabled {
  cursor: not-allowed;
  pointer-events: all !important;
}
    </style>
@if(session('mensagem'))
<script>
    swal({
          title: 'Sucesso',
          text: 'Sacado  atualizado!',
          icon: 'success',
      });
</script>
@endif
@if(session('mensagem2'))
<div class="alert alert-success"><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
    <strong>Sucesso!</strong>
    <p>{{session('mensagem2')}}</p>
</div>
@endif
<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Lista de Sacados</h5>
                @if(count($sacados)>0)
                <table id="cliente" class="mb-0 table table-striped">
                    <thead>
                        <tr>
                            <th>Razão Social</th>
                            <th>CNPJ</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sacados as $sacado)
                        <tr>
                            <td> {{ $sacado->razao_social }} </td>
                            <td> {{ $sacado->cnpj }} </td>
                            <td> {{ $sacado->email }} </td>
                            <td> {{ $sacado->ddd.$sacado->telefone }} </td>
                            <td class="text-center">
                                @if ($sacado->ativo == '0')
                                <div class="badge badge-danger">Inativo</div>
                                @else
                                <div class="badge badge-success">Ativo</div>
                                @endif
                            </td>
                            <td>
                                <a <?php if($sacado->ativo == 0) {?> href="javascript:void(0)" class="disabled" <?php }else{ ?>
                                    href="{{ route ('sacados.edit', $sacado->id ) }}" class="" <?php } ?> title="Editar"><i
                                        class="fas fa-edit" style="color:rgb(66, 62, 62);"></i></a>
                                <a data-id="{{$sacado->ativo}}" href="/sacado/updateStatus/{{$sacado->id}}"
                                    title="{{ $sacado->ativo == '0' ? 'Ativar' : 'Inativar' }}"
                                    class="cancel-confirm {{ $sacado->ativo == '0' ? 'activate' : 'inactivate' }}">
                                    <?php if($sacado->ativo == '0') { ?> <i class="fas fa-power-off"
                                        style="color:green;"></i>
                                    <?php }else{ ?> <i class="fas fa-power-off" style="color:red;"></i>
                                    <?php } ?>
                                </a>
                                <!--<a href="/cliente/delete/{{$sacado->id}}" title="Apagar" class="delete-confirm required_input"><i
                    class="fas fa-trash"></i></a>-->
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/index_sacados.js') }}"></script>
@endsection