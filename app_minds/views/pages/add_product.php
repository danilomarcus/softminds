<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?= isset($alter) ? "Alteração de Produto" : "Cadastro de Produto" ?></h1>
    </div>

    <h5><?= isset($alter) ? "Informe os dados para alteração" : "Informe os dados para cadastro" ?></h5>

    <small>* todos os campos são obrigatórios</small><br>

    <p class="row">
    <form class="row g-3" method="post" action="<?= base_url() ?>products/add_product">
        <!-- message response and validation -->
        <div class="ajax_response"></div>
        <?= csrf_input(); ?>

        <?php
        if (isset($alter['id'])):
            ?>
            <input type="hidden" name="id" id="id" value="<?= ($alter['id'] ?? "") ?>">
        <?php
        endif;
        ?>

        <div class="col-md-4">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= ($alter['name'] ?? "") ?>"
                   required>
        </div>
        <div class="col-md-4">
            <label for="description" class="form-label">Descricao</label>
            <input type="text" class="form-control" id="description" name="description"
                   value="<?= ($alter['description'] ?? "") ?>">
        </div>
        <div class="col-md-4">
            <label for="price" class="form-label">Preço</label>
            <input type="text" class="form-control mask-money" id="price" name="price"
                   value="<?= ($alter['price'] ?? "") ?>">
        </div>
        <div class="col-md-4">
            <label for="status_active" class="form-label">Status de produto</label>
            <select class="form-select" id="status_active" name="status_active">
                <option value="1" <?= (isset($alter['status_active']) && $alter['status_active'] == "1") ? "SELECTED" : ""; ?>>
                    Ativo
                </option>
                <option value="0" <?= (isset($alter['status_active']) && $alter['status_active'] == "0") ? "SELECTED" : ""; ?>>
                    Inativo
                </option>
            </select>
        </div>

        <?php
        if (isset($alter['status_active']) && $alter['status_active'] == "1" || empty($alter)):
            ?>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Salvar</button>
            </div>
        <?php
        endif;
        ?>
    </form>

    <?php
    if (isset($alter['status_active']) && $alter['status_active'] == "0"):
        ?>

        <p></p><h6 class="alert alert-danger">Este produto está desativado e não pode ser alterado, para fazer
        alterações clique abaixo para reativá-lo</h6></p>
        <a class="btn btn-sm btn-primary" href="<?= base_url() ?>products/activate/<?= $alter['id'] ?>">Reativar
            Produto</a>
    <?php
    endif;
    ?>
    <p><br><br></p>
    </div>
</main>