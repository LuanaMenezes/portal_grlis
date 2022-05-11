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
<div class="row">
    <!-- Search form -->
    <div class="col-md-12">
        <div class="main-card mb-3 card">
            <div class="card-header">Meus Borderôs

            </div>
            @if(session('mensagem'))
            <script>
                swal({
                      title: 'Sucesso',
                      text: 'Solicitação  cadastrada!',
                      icon: 'success',
                  });
            </script>
            @endif
            <!--<div style="width: 70%; padding-right:200px; margin-left:50%; padding-top:10px; padding-bottom:10px;">
                <input class="form-control" id="myInput" type="text" autofocus placeholder="Search..">
            </div>-->
            <div class="table-responsive">
                <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
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
                        <?php for($x = 0; $x < count($borderos); $x++) {?>
                        <tr class="accordion-toggle collapsed" id="accordion<?php echo $borderos[$x]->id;?>"
                            data-toggle="collapse" data-parent="#accordion<?php echo $borderos[$x]->id;?>"
                            href="#collapse<?php echo $borderos[$x]->id;?>">
                            <td class="expand-button"></td>
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
                            <td colspan="4">
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
                                            <?php echo  date( 'd-m-Y', strtotime($borderos[$x]->created_at));?>
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
                                            <?php echo 'Solicitado';//$operacoes[$y]->status;?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php } }}?>
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
<script src="{{ asset('assets/js/index_cliente.js') }}"></script>
<script>
    $(document).ready(function(){
      $("#myInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#table tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
      });
    });
    function myFunction() {
        //var s = document.getElementById('myInput');
          //  s.value = "";
}
</script>
@endsection