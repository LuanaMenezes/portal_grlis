@extends('layouts.admin')
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-news-paper ">
                </i>
            </div>
            <div><strong>Solicitação de Borderô</strong>
            </div>
        </div>
    </div>
</div>
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                @if($failures ?? '')
                <div class="card-footer">
                    @foreach ($failures ?? '' as $failure)
                    @foreach ($failure->errors() as $error)
                    <div class="alert alert-danger"><a href='#' class='close' data-dismiss='alert'
                            aria-label='close'>&times;</a>
                        <strong>Erro!</strong>
                        <p>{{ $error }}</p>
                    </div>
                    @endforeach
                    @endforeach
                </div>
                @endif
                <form action="{{ route('clientes/importFile') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">

                            <em><strong>Formatos válidos: *.xlxs; *.xls<strong></em><br>

                            <input id="planilha" type="file" name="planilha"
                                accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                            <div style="height:30px;">
                            </div>
                            <a href="{{ url()->previous() }}" class="mt-2 btn btn-secondary">Cancelar</a>
                            <button type="submit" class=" mt-2 btn btn-primary" name="Submit">Enviar</button>
                        </div>
                        <div class="form-group col-md-12">
                            <strong>Importação de Dados dos Clientes  <strong>
                                    <h6>
                                        <br> Selecione o arquivo Excel com os dados
                                        <br> dos seus clientes e envie para o sistema
                                        <br> utilizando a planilha padrão.
                                        <br>Clique no botão abaixo para fazer
                                        <br>o download da planilha.
                                        <br>


                                        <label style='margin-top:15px;' id=selected>
                                            <a class='btn btn-success'
                                                href='{{asset('uploads/planilha_padrao_contaut.xlsx')}}'
                                                download="planilha_padrao_contaut.xlsx"><strong>Planilha
                                                    Padrão<strong></a></label>
                                                    

                        </div>
                    </div>
                </form>
                <br>
                <br>
                <form action="{{ route('clientes/importFile') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">

                            <em><strong>Formatos válidos: *.pdf; *.xml<strong></em><br>

                            <input id="planilha" type="file" name="planilha"
                                accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                            <div style="height:30px;">
                            </div>
                            <a href="{{ url()->previous() }}" class="mt-2 btn btn-secondary">Cancelar</a>
                            <button type="submit" class=" mt-2 btn btn-primary" name="Submit">Enviar</button>
                        </div>
                        <div class="form-group col-md-12">
                        <div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-file-pdf text-danger">
                </i>
            </div>
            <div><strong></strong>
            </div>
        </div>
    </div>
    
</div>
        </div>
    </div>

                        </div>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>
@endsection