<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Composição de Pedido</h1>
    </div>

    <p class="row">
    <form class="row g-3" method="post" action="<?= base_url() ?>orders/add_order">
        <!-- message response and validation -->
        <div class="ajax_response"></div>
        <?= csrf_input(); ?>

        <!-- id do usuario que faz o pedido  -->
        <input type="hidden" value="<?= $this->session->userdata['logged_user']['id'] ?>" name="id_user" id="id_user">

        <h5>Confira os dados do pedido abaixo</h5>
        <div class="col-md-12">
            <p>
                <strong>Fornecedor:</strong> <?= $order['supplier'] ?></label>
            </p>
            <p>
                <strong>Colaborador:</strong> <?= $order['user'] ?></label>
            </p>
            <p>
                <strong>Observações do pedido:</strong>
                <?= ($order['details'] ?? "") ?>
            </p>
            <a href="<?= base_url() ?>orders/edit/<?= $order['id'] ?>" class="btn btn-primary" type="submit">Alterar
                Dados do pedido</a>
        </div>
    </form>

    <div class="div-separator"></div>


    <!-- ADD NOVOS PRODUTOS -->
    <h6>Adicionar produto: </h6>
    <form class="row g-3" method="post" action="<?= base_url() ?>orders/insert_product">
        <input type="hidden" name="id_item_alter" id="id_item_alter">
        <input type="hidden" name="id_product_alter" id="id_product_alter">
        <input type="hidden" name="order_id" id="order_id" value="<?= $order['id'] ?>">
        <div class="col-md-6">
            <select class="js-order-product" name="produto" id="produto"></select>
        </div>
        <div class="col-md-2">
            <input class="form-control" type="number" min="1" max="9999" name="amount" id="amount"
                   placeholder="qtde" value="1">
        </div>
        <div class="col-md-2">
            <input class="form-control mask-money" type="price" name="price" id="price" placeholder="preço">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary mb-3" type="submit">Adicionar</button>
        </div>
    </form>

    <div class="row">

        <!--  PRODUTOS JA NO PEDIDO -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h4 class="h6"> Listagem de produtos</h4>
<!--            <div class="btn-toolbar mb-2 mb-md-0">-->
<!--                --><?php
//                if ($this->session->userdata['logged_user']['status_active'] == "1"):
//                    ?>
<!--                    <a href="--><?//= base_url() ?><!--products/new" class="btn btn-sm btn-primary"><i-->
<!--                                class="fas fa-plus"></i> Adicionar Produto</a>-->
<!--                --><?php
//                endif;
//                ?>
<!--            </div>-->
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Produto</th>
                    <th scope="col">Qtde</th>
                    <th scope="col">Preço</th>
                    <th scope="col">Subtotal</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>

                <?php
                $qtdeTotal = 0;
                $total = 0;
                if (isset($products) && is_array($products) && sizeof($products)):

                    foreach ($products as $product):
                        $sub = $product['amount'] * $product['price'];
                        $qtdeTotal += $product['amount'];
                        $total += $sub;
                        ?>
                        <tr>
                            <td><?= $product['id'] ?></td>
                            <td><?= $product['produto'] ?></td>
                            <td><?= $product['amount'] ?></td>
                            <td><?= number_format($product['price'], 2, ",", ".") ?></td>
                            <td><?= number_format($sub, 2, ",", ".") ?></td>
                            <td>
                                <?php
                                if ($this->session->userdata['logged_user']['status_active'] == "1"):
                                    ?>
                                    <a href="#"
                                       class="btn btn-sm btn-warning edit-item-order" data-id="<?= $product['id'] ?>"
                                       data-qtde="<?= $product['amount'] ?>"
                                       data-price="<?= $product['price'] ?>"
                                       data-produto-id="<?= $product['id_product'] ?>"
                                       data-produto="<?= $product['produto'] ?>"><i
                                                class="fas fa-pencil-alt"></i></a>
                                    <a class="btn btn-sm btn-danger deleteRegister" href="#"
                                       data-url="<?= base_url()
                                       ?>orders/delete_order_item/<?= $product['id']
                                       ?>">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                <?php
                                endif;
                                ?>
                            </td>
                        </tr>
                    <?php
                    endforeach;
                    ?>
                    <tr>
                        <td colspan="2"><strong>Totais:</strong></td>
                        <td><strong><?= $qtdeTotal ?></strong></td>
                        <td>&nbsp;</td>
                        <td><strong><?= number_format($total, 2, ",", ".") ?></strong></td>
                        <td>&nbsp;</td>
                    </tr>
                <?php
                else:
                    ?>
                    <tr>
                        <td colspan="6">Nenhum produto no pedido</td>
                    </tr>
                <?php
                endif;
                ?>
                </tbody>
            </table>
        </div>

    </div>

    <?php
    if (isset($products) && is_array($products) && sizeof($products) > 0):
        ?>
        <div class="row">
            <a href="<?= base_url() ?>orders/close/<?= $order['id'] ?>" class="btn btn-sm btn-danger">Fechar Pedido</a>
        </div>
    <?php
    endif;
    ?>

    <p><br><br></p>
</main>