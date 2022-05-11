@extends('layouts.admin')
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
<div class="app-page-title">
  <div class="page-title-wrapper">
    <div class="page-title-heading">
      <div class="page-title-icon">
        <i class="pe-7s-news-paper icon-gradient bg-happy-itmeo">
        </i>
      </div>
      <div><strong>Borderôs</strong>
      </div>
    </div>
  </div>
</div>
@if(session('mensagem'))
<div class="alert alert-success"><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
  <strong>Sucesso!</strong>
  <p>{{session('mensagem')}}</p>
</div>
@endif
<div class="row">
  <div class="col-lg-12">
    <div class="main-card mb-3 card">
      <div class="card-body">
        <h5 class="card-title">Meus Borderôs</h5>
        @if(count($clientes)>0)
        <table id="cliente" class="mb-0 table table-striped">
          <thead>
            <tr>
              <th>Nome</th>
              <th>CNPJ</th>
              <th>CGA</th>
              <th>Uniprofissional</th>
              <th>Qtd Sócios</th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($clientes as $cliente)
            <tr>
              <td> {{ $cliente->nome }} </td>
              <td> {{ $cliente->cnpj }} </td>
              <td> {{ $cliente->cga }} </td>
              <td> {{ $cliente->uniprofissional === "N" ? "Não" : "Sim" }} </td>
              <td> {{ $cliente->qtd_socios > 0 ? $cliente->qtd_socios : "-" }} </td>
              <td class="text-center">
                @if ($cliente->ativo == 'N')
                <div class="badge badge-danger">Inativo</div>
                @else
                <div class="badge badge-success">Ativo</div>
                @endif
              </td>
              <td>
                <a class="{{ $cliente->ativo == 'N' ? 'disabled' : '' }}"
                  href="{{ route ('clientes.edit', $cliente->id ) }}" title="Editar"><i class="fas fa-edit"></i></a>
                <a data-id="{{$cliente->ativo}}" href="/cliente/updateStatus/{{$cliente->id}}"
                  title="{{ $cliente->ativo == 'N' ? 'Ativar' : 'Inativar' }}"
                  class="cancel-confirm {{ $cliente->ativo == 'N' ? 'activate' : 'inactivate' }}"><i
                    class="fas fa-power-off"></i></a>
                <a href="/cliente/delete/{{$cliente->id}}" title="Apagar" class="delete-confirm required_input"><i
                    class="fas fa-trash"></i></a>
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

<script src="{{ asset('assets/js/index_cliente.js') }}"></script>
@endsection