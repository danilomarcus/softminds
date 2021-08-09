<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Novo Pedido</h1>
    </div>

    <h5>Informe os dados para lançar um novo pedido</h5>

    <small>* todos os campos são obrigatórios</small><br>

    <p class="row">
    <form class="row g-3" method="post" action="<?= base_url() ?>orders/add_order">
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

        <!-- id do usuario que faz o pedido  -->
        <input type="hidden" value="<?= $this->session->userdata['logged_user']['id'] ?>" name="id_user" id="id_user">

        <div class="col-md-12">
            <label for="id_supplier" class="form-label">Fornecedor</label>
            <select class="form-select" id="id_supplier" name="id_supplier" required>
                <option selected disabled value="">Escolha...</option>
                <?php
                if (isset($suppliers)):
                    foreach ($suppliers as $supplier):
                        ?>
                        <option value="<?= $supplier['id'] ?>"
                            <?= (isset($alter['id_supplier']) && $alter['id_supplier'] == $supplier['id']) ? "SELECTED" : ""; ?>>
                            <?= $supplier['name'] ?>
                        </option>
                    <?php
                    endforeach;
                endif;
                ?>
            </select>
        </div>
        <div class="col-md-12">
            <label for="details" class="form-label">Observações do pedido</label>
            <input type="text" class="form-control" id="details" name="details"
                   value="<?= ($alter['details'] ?? "") ?>">
        </div>
        <div class="col-12">
            <button class="btn btn-primary" type="submit">Continuar</button>
        </div>
    </form>

    <p><br><br></p>
    </div>
</main>