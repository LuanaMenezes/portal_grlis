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
<style>
    tr.hide-table-padding td {
        padding: 0;
    }

    .expand-button {
        position: relative;
    }

    .accordion-toggle .expand-button:after {
        position: absolute;
        left: .75rem;
        top: 50%;
        transform: translate(0, -50%);
        content: '-';
    }

    .accordion-toggle.collapsed .expand-button:after {
        content: '+';
    }
</style>
<div class="row home">
    <?php
    use Carbon\Carbon;
    \Carbon\Carbon::setUtf8(true);
    setlocale(LC_TIME, 'pt_BR'); // LC_TIME é formatação de data e hora com strftime()
    $dt = Carbon::now(new DateTimeZone('America/Bahia'));
    $data_atual = $dt->formatLocalized('%A , %d de %B de %Y');
    echo ucfirst(gmstrftime($data_atual));
    ?>
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
<form action="{{ route('admin/indexFiltering') }}" method="get" class="needs-validation" novalidate>
    <div class="form-row">
        <div class="col-md-4">
            <div class="position-relative form-group"><label class=""><strong>Data Inicial:</strong><span
                        class="required_input">*</span></label><input name="data_inicial" id="data_inicial"
                    placeholder="Digite a data inicial" type="date" class="form-control" 
                    <?php if ( ($back==0) && (strlen($errors->getBag('default')->first('data_inicial'))) == 0) { ?> value="{{ old('data_inicial') }}"
                   <?php } else if ($back == 1){ ?>  value="<?php echo date("Y-m-d");?>" 
                    <?php } else if(strlen($errors->getBag('default')->first('data_inicial'))> 0) {?> value="<?php echo date(""); }?>" required>
                <div class="invalid-feedback">
                    Por favor, informe a Data Inicial
                </div>
                <div class="valid-feedback">
                    Parece OK!
                </div>
            </div>
        </div>
        <?php ?>
        <div class="col-md-4">
            <div class="position-relative form-group"><label class=""><strong>Data Final:</strong><span
                        class="required_input">*</span></label><input name="data_final" id="data_final"
                    placeholder="Digite a data final" type="date" class="form-control" 
                    <?php if ( ($back==0) && (strlen($errors->getBag('default')->first('data_final'))) == 0) { ?> value="{{ old('data_final') }}"
                    <?php }else if ($back == 1){ ?>  value="<?php echo date("Y-m-d");?>" <?php }  
                    else if(strlen($errors->getBag('default')->first('data_final')) > 0) {?> value="<?php echo date(""); }?>"
                    required>
                <div class="invalid-feedback">
                    Por favor, informe a Data Final
                </div>
                <div class="valid-feedback">
                    Parece OK!
                </div>
            </div>
        </div>
        <div class="col-md-4" style="margin-top: 30px">
            <input type="submit" value="Pesquisar" class="btn btn-primary" />
        </div>
    </div>
</form>
<div class="row">
    <!-- Search form -->
    <div class="col-md-12">
        <div class="main-card mb-3 card">
            <div class="table-responsive">
                <table id="operacao" class="align-middle mb-0 table table-borderless table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Cedente</th>
                            <th class="text-center">Data criação</th>
                            <th class="text-center">Nº borderô</th>
                            <th class="text-center">Valor Total Face</th>
                            <th class="text-center">Quantidade de Títulos</th>
                        </tr>
                    </thead>
                    <tbody id="table">
                        @if ($borderos->count() == 0)
                        <tr style="text-align: center;">
                            <td colspan="6">Não há borderô para ser exibido.</td>
                        </tr>
                        @endif
                        <?php 
                            for($x = 0; $x < count($borderos); $x++) {?>

                        <tr class="accordion-toggle collapsed" id="accordion<?php echo $borderos[$x]->id;?>"
                            data-toggle="collapse" data-parent="#accordion<?php echo $borderos[$x]->id;?>"
                            href="#collapse<?php echo $borderos[$x]->id;?>">
                            <td class="expand-button"></td>
                            <td class="text-center">
                                <?php echo $borderos[$x]->contratante; ?>
                            </td>
                            <td class="text-center">
                                <?php echo date( 'd-m-Y' , strtotime($borderos[$x]->created_at)); ?>
                            </td>
                            <td class="text-center">
                                <?php echo $borderos[$x]->id; ?>
                            </td>
                            <td class="text-center">
                                <?php echo round($borderos[$x]->totalvlrface,2); ?>
                            </td>
                            <td class="text-center">
                                <?php echo $borderos[$x]->qtddigitada; ?>
                            </td>
                        </tr>
                        <?php 
                        for($y = 0; $y < count($operacoes); $y++) {
                                if ($borderos[$x]->id == $operacoes[$y]->bordero_id){
                         ?>
                        <tr class="hide-table-padding">
                            <td></td>
                            <td colspan="6">
                                <div id="collapse<?php echo $borderos[$x]->id;?>" class="collapse in p-3">
                                    <div class="row">
                                        <div class="col-2"><strong>Nome Sacado</strong></div>
                                        <div class="col-10">
                                            <?php echo $operacoes[$y]->razaosocial; ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-2"><strong>Nº Título</strong></div>
                                        <div class="col-10">
                                            <?php echo $operacoes[$y]->numero; ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-2"><strong>Vencimento</strong></div>
                                        <div class="col-10">
                                            <?php echo  date( 'd-m-Y', strtotime($operacoes[$y]->vcto));?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-2"><strong>Valor Face</strong></div>
                                        <div class="col-10">
                                            <?php echo round($operacoes[$y]->vlrface,2); ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-2"><strong>Status</strong></div>
                                        <div class="col-10">
                                            <?php if($operacoes[$y]->status == 0) echo 'Não consultado'; 
                                            else if($operacoes[$y]->status == 1 || $operacoes[$y]->status == 2) echo 'A consultar';
                                            else echo 'Consultado'; 
                                            ?>
                                        </div>
                                    </div>
                                    <div class="row>">
                                        <form action="/script" method="post">
                                            <input type="hidden" name="id" id="id" value="{{ $operacoes[$y]->id }}">
                                            @csrf
                                            <button type="submit" class="mt-2 btn btn-success">Consultar sem
                                                Cedente</button>
                                        </form>
                                        <form action="/consulta" method="post">
                                            <input type="hidden" name="id" id="id" value="{{ $operacoes[$y]->id }}">
                                            @csrf
                                            <button type="submit" class="mt-2 btn btn-secondary">Consultar com
                                                Cedente</button>
                                        </form>
                                    </div>
                                </div>

                            </td>
                        </tr>
                        <?php }}} ?>

                    </tbody>
                </table>
                {{-- Pagination --}}
                <div class="d-flex justify-content-center">
                    {!! $borderos->links() !!}
                </div>
                <p>
                    Mostrando {{$borderos->count()}} de {{ $borderos->total() }} borderôs.
                </p>
            </div>
        </div>
    </div>
</div>
<!--<script src="{{ asset('assets/js/home.js') }}"></script>-->
@endsection