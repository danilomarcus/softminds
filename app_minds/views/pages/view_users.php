<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Lista de usuários</h1>
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

    <h6>Buscar Colaborador: </h6>
    <form class="row g-3 ajax_off" method="post" action="<?= base_url() ?>users/search">
        <div class="col-md-6">
            <input class="form-control" type="text" name="search" id="search" placeholder="Digite o nome">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary mb-3" type="submit">Pesquisar</button>
        </div>
    </form>

    <!--  RECENTES USUARIOS -->
    <!--  RECENTES USUARIOS -->
    <!--  RECENTES USUARIOS -->
    <!--  RECENTES USUARIOS -->

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h4 class="h4"> Usuários recentes</h4>
        <div class="btn-toolbar mb-2 mb-md-0">
            <?php
            if ($this->session->userdata['logged_user']['status_active'] == "1"):
                ?>
                <a href="<?= base_url() ?>users/new" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Novo
                    Usuário</a>
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
                <th scope="col">Nome</th>
                <th scope="col">Email</th>
                <th scope="col">Telefone</th>
                <th scope="col">Documento</th>
                <th scope="col">Fornecedor</th>
                <th scope="col">Status</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>

            <?php
            if (isset($users) && is_array($users) && sizeof($users)):

                foreach ($users as $user):
                    ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td>
                            <a class="link-name-anchor"
                               href="<?= base_url() ?>users/edit/<?= $user['id'] ?>"><?= $user['name'] ?></a>
                        </td>
                        <td><?= $user['email'] ?></td>
                        <td><?= $user['phone'] ?></td>
                        <td><?= $user['document'] ?></td>
                        <td>
                            <?=
                            ($user['is_supplier'] == "1") ? "<i class='fas fa-check colorGreen'></i>" : "<i class='fas fa-times colorRed'></i>";
                            ?>
                        </td>
                        <td>
                            <?=
                            ($user['status_active'] == "1") ? "<i class='fas fa-check colorGreen'></i>" : "<i class='fas fa-times colorRed'></i>";
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($this->session->userdata['logged_user']['status_active'] == "1"):
                                ?>
                                <a href="<?= base_url() ?>users/edit/<?= $user['id'] ?>" class="btn btn-sm btn-warning"><i
                                        class="fas fa-pencil-alt"></i></a>
                                <!--                            <a class="btn btn-sm btn-danger deleteRegister" href="#"-->
                                <!--                               data-url="--><?//= base_url()
                                ?><!--users/delete/--><?//= $user['id']
                                ?><!--">-->
                                <!--                                <i class="fas fa-trash-alt"></i>-->
                                <!--                            </a>-->
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
                    <td colspan="7">Nenhum usuário recente foi cadastrado</td>
                </tr>
            <?php
            endif;
            ?>

            </tbody>
        </table>
    </div>

    <div class="div-separator"></div>

</main>