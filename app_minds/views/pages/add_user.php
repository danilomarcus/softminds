<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?= isset($userAlt) ? "Alteração de Usuário" : "Cadastro de Usuário" ?></h1>
    </div>

    <h5><?= isset($userAlt) ? "Informe os dados para alteração" : "Informe os dados para cadastro" ?></h5>

    <small>* todos os campos são obrigatórios</small><br>
    <small>* telefone e documento aceitam apenas números</small><br>
    <small>* caso seja fornecedor, deverá preencher obrigatoriamente o enedereço secundário</small>

    <p class="row">
    <form class="row g-3" method="post" action="<?= base_url() ?>users/add_user">
        <!-- message response and validation -->
        <div class="ajax_response"></div>
        <?= csrf_input(); ?>

        <?php
        if (isset($userAlt['id'])):
            ?>
            <input type="hidden" name="id" id="id" value="<?= ($userAlt['id'] ?? "") ?>">
        <?php
        endif;
        ?>

        <div class="col-md-4">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= ($userAlt['name'] ?? "") ?>"
                   required>
        </div>
        <div class="col-md-4">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email"
                   value="<?= ($userAlt['email'] ?? "") ?>">
        </div>
        <div class="col-md-4">
            <label for="phone" class="form-label">Telefone</label>
            <input type="text" class="form-control" id="phone" name="phone"
                   value="<?= ($userAlt['phone'] ?? "") ?>">
        </div>
        <div class="col-md-4">
            <label for="document" class="form-label">Documento (CPF/CNPJ)</label>
            <input type="text" class="form-control" id="document" name="document" maxlength="18"
                   value="<?= ($userAlt['document'] ?? "") ?>">
        </div>
        <div class="col-md-4">
            <label for="password" class="form-label">Senha</label>
            <input type="password" class="form-control" id="password" name="password"
                   value="<?= ($userAlt['password'] ?? "") ?>">
        </div>
        <div class="col-md-4">
            <label for="password2" class="form-label">Repita a senha</label>
            <input type="password" class="form-control" id="password2" name="password2"
                   value="<?= ($userAlt['password'] ?? "") ?>">
        </div>
        <div class="col-md-4">
            <label for="is_supplier" class="form-label">É Fornecedor?</label>
            <select class="form-select" id="is_supplier" name="is_supplier">
                <option selected disabled value="">Escolha...</option>
                <option value="0" <?= (isset($userAlt['is_supplier']) && $userAlt['is_supplier'] == "0") ? "SELECTED" : ""; ?>>
                    Não
                </option>
                <option value="1" <?= (isset($userAlt['is_supplier']) && $userAlt['is_supplier'] == "1") ? "SELECTED" : ""; ?>>
                    Sim
                </option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="portal_access" class="form-label">Acesso ao Portal</label>
            <select class="form-select" id="portal_access" name="portal_access">
                <option selected disabled value="">Escolha...</option>
                <option value="0" <?= (isset($userAlt['portal_access']) && $userAlt['portal_access'] == "0") ? "SELECTED" : ""; ?>>
                    Não
                </option>
                <option value="1" <?= (isset($userAlt['portal_access']) && $userAlt['portal_access'] == "1") ? "SELECTED" : ""; ?>>
                    Sim
                </option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="status_active" class="form-label">Status de usuário</label>
            <select class="form-select" id="status_active" name="status_active">
                <option value="1" <?= (isset($userAlt['status_active']) && $userAlt['status_active'] == "1") ? "SELECTED" : ""; ?>>
                    Ativo
                </option>
                <option value="0" <?= (isset($userAlt['status_active']) && $userAlt['status_active'] == "0") ? "SELECTED" : ""; ?>>
                    Inativo
                </option>
            </select>
        </div>

        <!--            endereco 01 -->
        <h6>Endereço 1</h6>
        <div class="col-md-8">
            <label for="address_street_01" class="form-label">Endereço</label>
            <input type="text" class="form-control" id="address_street_01" name="address_street_01"
                   placeholder="Rua, Av." value="<?= ($userAlt['address_street_01'] ?? "") ?>">
        </div>
        <div class="col-md-4">
            <label for="address_number_01" class="form-label">Número</label>
            <input type="text" class="form-control" id="address_number_01" name="address_number_01"
                   value="<?= ($userAlt['address_number_01'] ?? "") ?>">
        </div>
        <div class="col-md-6">
            <label for="address_city_01" class="form-label">Cidade</label>
            <input type="text" class="form-control" id="address_city_01" name="address_city_01"
                   value="<?= ($userAlt['address_city_01'] ?? "") ?>">
        </div>
        <div class="col-md-4">
            <label for="address_state_01" class="form-label">Estado</label>
            <input type="text" class="form-control" id="address_state_01" name="address_state_01"
                   value="<?= ($userAlt['address_state_01'] ?? "") ?>">
        </div>
        <div class="col-md-2">
            <label for="address_zip_code_01" class="form-label">CEP</label>
            <input type="text" class="form-control" id="address_zip_code_01" name="address_zip_code_01"
                   value="<?= ($userAlt['address_zip_code_01'] ?? "") ?>">
        </div>

        <?php
        $style = "style='display:none'";
        if (isset($userAlt) && $userAlt['is_supplier'] == "1") {
            $style = "";
        }
        ?>
        <!--            endereco 02 -->
        <div class="row p-0 m-0 mt-4" id="div-address-02" <?= $style ?>>
            <h6>Endereço 2 (obrigatório para Fornecedor)</h6>
            <div class="col-md-8">
                <label for="address_street_02" class="form-label">Endereço</label>
                <input type="text" class="form-control" id="address_street_02" name="address_street_02"
                       placeholder="Rua, Av." value="<?= ($userAlt['address_street_02'] ?? "") ?>">
            </div>
            <div class="col-md-4">
                <label for="address_number_02" class="form-label">Número</label>
                <input type="text" class="form-control" id="address_number_02" name="address_number_02"
                       value="<?= ($userAlt['address_number_02'] ?? "") ?>">
            </div>
            <div class="col-md-4">
                <label for="address_city_02" class="form-label">Cidade</label>
                <input type="text" class="form-control" id="address_city_02" name="address_city_02"
                       value="<?= ($userAlt['address_city_02'] ?? "") ?>">
            </div>
            <div class="col-md-4">
                <label for="address_state_02" class="form-label">Estado</label>
                <input type="text" class="form-control" id="address_state_02" name="address_state_02"
                       value="<?= ($userAlt['address_state_02'] ?? "") ?>">
            </div>
            <div class="col-md-4">
                <label for="address_zip_code_02" class="form-label">CEP</label>
                <input type="text" class="form-control" id="address_zip_code_02" name="address_zip_code_02"
                       value="<?= ($userAlt['address_zip_code_02'] ?? "") ?>">
            </div>
        </div>

        <?php
        if ( (isset($userAlt['status_active']) && $userAlt['status_active'] == "1") || empty($userAlt)):
            ?>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Salvar</button>
            </div>
        <?php
        endif;
        ?>
    </form>

    <?php
    if (isset($userAlt['status_active']) && $userAlt['status_active'] == "0"):
        ?>

        <p></p><h6 class="alert alert-danger">Este usuário está desativado e não pode ser alterado, para fazer
        alterações clique abaixo para reativá-lo</h6></p>
        <a class="btn btn-sm btn-primary" href="<?= base_url() ?>users/activate/<?= $userAlt['id'] ?>">Reativar
            Usuário</a>
    <?php
    endif;
    ?>
    <p><br><br></p>
    </div>
</main>