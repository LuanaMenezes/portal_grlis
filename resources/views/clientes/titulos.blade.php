<div class="novo-titulo">
    <div class="element">
        <div class="button">
            <a class="clone btn btn-warning" href="#">
                <i class="fas fa-plus-circle"></i> Novo Título
            </a>
        </div>
        <div class='input-wrapper top-padding'>
            <label for='input-file'>
                Selecionar um arquivo XML &#187;
            </label>
            <input id='input-file' type='file' value='' class="esconder_arquivo" accept="text/xml" />
            <span id='file-name'></span>
            <button class="btn btn-success">Enviar Arquivo</button>
        </div>
        <div class="form-row top-padding">
            <div class="col-md-6">
                <div class="position-relative form-group"><label for="operacoes.razaosocial" class="">Razão
                        Social/Nome
                        <span class="required_input">*</span></label><input name="operacoes[razaosocial]"
                        id="operacoes[razaosocial]" placeholder="Digite aqui a razão social" type="text"
                        class="form-control" value="{{ old('operacoes[razaosocial]') }}" required readonly>
                    <div class="invalid-feedback">
                        Por favor, informe a Razão Social/Nome .
                    </div>
                    <div class="valid-feedback">
                        Parece OK!
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="position-relative form-group"><label for="cnpjsacado" class="">CNPJ do Sacado
                        <span class="required_input">*</span></label><input name="cnpjsacado" id="cnpjsacado"
                        placeholder="Digite aqui o CNPJ do Sacado" autofocus type="text" class="form-control"
                        value="{{ old('cnpjsacado') }}" required readonly>
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
                <div class="position-relative form-group"><label for="operacoes[numero]" class="">Nº do título
                        <span class="required_input">*</span></label><input name="operacoes[numero]"
                        id="operacoes[numero]" placeholder="Digite aqui o número do título" autofocus type="number"
                        class="form-control" value="{{ old('operacoes[numero]') }}" required readonly>
                    <div class="invalid-feedback">
                        Por favor, o número do título.
                    </div>
                    <div class="valid-feedback">
                        Parece OK!
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="position-relative form-group"><label for="operacoes[tipotitulo]" class="">Tipo do
                        título<span class="required_input">*</span></label>
                    <!--<input name="operacoes[tipotitulo]"
                        id="operacoes[tipotitulo]" placeholder="Digite tipo do título" type="text"
                        class="form-control" value="{{ old('operacoes[tipotitulo]') }}" required> -->
                    <select class="form-control combobox" id="operacoes[tipotitulo]" name="operacoes[tipotitulo]"
                        required>
                        <option value="{{ old('operacoes[tipotitulo]') }}" disabled selected>Selecionar tipo do título
                        </option>
                        <option>Cartão de Crédito</option>
                        <option>Cédula de Crédito Bancário</option>
                        <option>Cheque</option>
                        <option>Contrato Futuro</option>
                        <option>Cédula de Produto Rural</option>
                        <option>Conhecimento de Transporte</option>
                        <option>Duplicata Mercantil</option>
                        <option>Duplicata de Serviço</option>
                        <option>Nota Promissória</option>
                        <option>Parcela de Contrato</option>
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
                <div class="position-relative form-group"><label for="operacoes[vcto]" class="">Vencimento
                        <span class="required_input">*</span></label><input name="operacoes[vcto]" id="operacoes[vcto]"
                        placeholder="Digite aqui o vencimento" autofocus type="date" class="form-control"
                        value="{{ old('operacoes[vcto]') }}" required readonly>
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
                <div class="position-relative form-group"><label for="operacoes[vlrface]" class="">Valor Face<span
                            class="required_input">*</span></label><input name="operacoes[vlrface]"
                        id="operacoes[vlrface]" placeholder="Digite o valor face" type="number" class="form-control"
                        value="{{ old('operacoes[vlrface]') }}" required readonly>
                    <div class="invalid-feedback">
                        Por favor, informe o valor face
                    </div>
                    <div class="valid-feedback">
                        Parece OK!
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="position-relative form-group"><label for="operacoes[qtdetitulo]" class="">Quantidade
                        <span class="required_input">*</span></label><input name="operacoes[qtdetitulo]"
                        id="operacoes[qtdetitulo]" placeholder="Digite aqui a quantidade" autofocus type="number"
                        class="form-control" value="{{ old('operacoes[qtdetitulo]') }}" required>
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
                <div class="position-relative form-group"><label for="operacoes[endop]" class="">Endereço<span
                            class="required_input">*</span></label><input name="operacoes[endop]" id="operacoes[endop]"
                        placeholder="Digite o endereço" type="text" class="form-control"
                        value="{{ old('operacoes[endop]') }}" required>
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
                <div class="position-relative form-group"><label for="operacoes[ddd]" class="">DDD<span
                            class="required_input">*</span></label><input name="operacoes[ddd]" id="operacoes[ddd]"
                        placeholder="Digite o DDD" type="number" class="form-control"
                        value="{{ old('operacoes[ddd]') }}" required>
                    <div class="invalid-feedback">
                        Por favor, informe o DDD
                    </div>
                    <div class="valid-feedback">
                        Parece OK!
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="position-relative form-group"><label for="operacoes[telefone]" class="">Telefone
                        <span class="required_input">*</span></label><input name="operacoes[telefone]"
                        id="operacoes[telefone]" placeholder="Digite aqui o número do telefone" type="text"
                        class="form-control" value="{{ old('operacoes[telefone]') }}" required>
                    <div class="invalid-feedback">
                        Por favor, informe o telefone
                    </div>
                    <div class="valid-feedback">
                        Parece OK!
                    </div>
                </div>
            </div>
        </div>
        <div class="results"></div>
    </div>
</div>