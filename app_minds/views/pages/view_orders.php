<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Lista de Pedidos</h1>
    </div>

    <!--    <canvas class="my-4 w-100" id="myChart" width="900" height="380"></canvas>-->

    <div class="row">
        <!--        --><? //= showFlash() ?>
        <?= $this->session->flashdata('flash_msg'); ?>
    </div>

    <?php
    if (isset($this->session->userdata['logged_user']['username'])):
        $firstName = str_limit_words($this->session->userdata['logged_user']['username'], 1, "");
        ?>
        <div class="row">
            Olá <?= $firstName ?>, seja bem vindo(a)
        </div>
    <?php
    endif;
    ?>
    <br>

    <h6>Buscar Pedido (digite um período para buscar): </h6>
    <form class="row g-3 ajax_off" method="post" action="<?= base_url() ?>orders/search">
        <div class="col-md-6">
            <input class="form-control" type="date" name="date_begin" id="date_begin" value="<?= ($date_begin ?? "") ?>" required>
        </div>
        <div class="col-md-6">
            <input class="form-control" type="date" name="date_end" id="date_end" value="<?= ($date_end ?? "") ?>" required>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary mb-3" type="submit">Pesquisar</button>
        </div>
    </form>


    <!--  PEDIDOS PRODUTOS -->
    <!--  PEDIDOS PRODUTOS -->
    <!--  PEDIDOS PRODUTOS -->
    <!--  PEDIDOS PRODUTOS -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h4 class="h4"> Pedidos recentes</h4>
        <small>pedido fechado <i class='fas fa-check colorGreen'></i> | pedido aberto<i class='fas fa-times colorRed'></i></small>
        <div class="btn-toolbar mb-2 mb-md-0">
            <?php
            if ($this->session->userdata['logged_user']['status_active'] == "1"):
                ?>
                <a href="<?= base_url() ?>orders/new" class="btn btn-sm btn-primary"><i
                            class="fas fa-plus"></i> Novo
                    Pedido</a>
            <?php
            endif;
            ?>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Data Ped.</th>
                <th scope="col">Colaborador</th>
                <th scope="col">Fornecedor</th>
                <th scope="col">Obs</th>
                <th scope="col">Itens</th>
                <th scope="col">Status</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>

            <?php
            if (isset($orders) && is_array($orders) && sizeof($orders)):

                foreach ($orders as $order):
                    ?>
                    <tr>
                        <td><?= $order['id'] ?></td>
                        <td><?= date_fmt_br_show($order['created_at']) ?></td>
                        <td><?= $order['user'] ?></td>
                        <td><?= $order['supplier'] ?></td>
                        <td><?= $order['details'] ?></td>
                        <td><?= ($order['itens'] ?? 0) ?></td>
                        <td>
                            <?=
                            ($order['status_order'] == "1") ? "<i class='fas fa-check colorGreen'></i>" : "<i class='fas fa-times colorRed'></i>";
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($this->session->userdata['logged_user']['status_active'] == "1"):
                                ?>

                                <?php
                                if ($order['status_order'] == "1"):
                                    ?>
                                    <a href="<?= base_url() ?>orders/view_order/<?= $order['id'] ?>"
                                       class="btn btn-sm btn-info"><i
                                                class="fas fa-book-reader"></i></a>
                                <?php
                                endif;
                                ?>

                                <?php
                                if ($order['status_order'] == "0"):
                                    ?>
                                    <a href="<?= base_url() ?>orders/edit_order_composition/<?= $order['id'] ?>"
                                       class="btn btn-sm btn-warning"><i
                                                class="fas fa-pencil-alt"></i></a>
                                <?php
                                endif;
                                ?>

                                <?php
                                if ($order['itens'] < 1 || $order['status_order'] == "0"):
                                    ?>
                                    <a class="btn btn-sm btn-danger deleteRegister" href="#"
                                       data-url="<?= base_url()
                                       ?>orders/delete/<?= $order['id']
                                       ?>">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                <?php
                                endif;
                                ?>
                            <?php
                            endif;
                            ?>
                        </td>
                    </tr>
                <?php
                endforeach;
            else:
                ?>
                <tr>
                    <td colspan="6">Nenhum pedido recente foi cadastrado</td>
                </tr>
            <?php
            endif;
            ?>
            </tbody>
        </table>
    </div>

    <div class="div-separator"></div>

</main>