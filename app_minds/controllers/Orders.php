<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Orders extends CI_Controller
{
    /**
     *  carrega o template de login
     */
    public function index()
    {
        redirect("dashboard");
    }

    /**
     * abre tela para novo cadastro
     */
    public function new()
    {
        $this->load->model("users_model");
        $suppliers = $this->users_model->getSuppliers();
        // load template
        $this->load->view("template/header");
        $this->load->view("pages/add_order",[
            "suppliers" => $suppliers
        ]);
        $this->load->view("template/footer");
    }

    /** funcao para adicionar e/ou alterar produtos
     * @param null $id
     */
    public function add_order($id = null)
    {
        // carrega o model
        $this->load->model("orders_model");

        // checa se foi um post via form
        $csrf = filter_input(INPUT_POST, 'csrf', FILTER_DEFAULT);

        if (!empty($csrf)) {

            // recebendo parametros
            $post = $_POST;
            // filtrando dados e eliminando espacos
            $post = filter($post);
            $post = array_map("trim", $post);

            // valida o formulario
            if (!csrf_verify($post)) {
                $json['message'] = createMessage("warning", "Erro ao enviar, favor use o formulário");
                echo json_encode($json);
                return;
            }
            unset($post['csrf']);

            if (in_array("", $post)) {
                $json['message'] = createMessage("warning", "Por favor preencha todos os campos");
                echo json_encode($json);
                return;
            }

//            $post['price'] = str_replace([".", ","], ["", "."], $post['price']);

//            $price = (float) $post['price'];
//            // valida o valor correto
//            if (!is_float($price) && $price < 0) {
//                $json['message'] = createMessage("warning", "Informe um valor de preço correto");
//                echo json_encode($json);
//                return;
//            }

            // salva o novo pedido
            if($insert_id = $this->orders_model->save($post)){

                // redireciona para inserir produtos
                $msg = createMessage("success", "O pedido foi salvo com sucesso");
                $this->session->set_flashdata('flash_msg', $msg);

                // redireciona para inserir produtos
                $json['redirect'] = base_url() . "orders/edit_order_composition/" . $insert_id;
                echo json_encode($json);
                return;
            }else{
                $json['message'] = createMessage("danger", "Não foi possível salvar, por favor tente novamente");
                echo json_encode($json);
                return;
            }
        }

        // load template
        $this->load->view("template/header");
        $this->load->view("pages/add_order");
        $this->load->view("template/footer");
    }

    /** alterar os dados base do pedido
     * @param $id
     */
    function edit($id){
        if(!isset($id)){
            $this->session->set_flashdata('flash_msg', createMessage("danger", "O pedido não foi encontrado"));
            redirect("dashboard");
        }
        // carrega o model
        $this->load->model("orders_model");
        $userAlt = null;
        // checa se tem produto pra alteracao
        if(isset($id)){
            $userAlt = $this->orders_model->findById($id);
        }
        $this->load->model("users_model");
        $suppliers = $this->users_model->getSuppliers();
        // load template
        $this->load->view("template/header");
        $this->load->view("pages/add_order", ["alter" => $userAlt, "suppliers" => $suppliers]);
        $this->load->view("template/footer");
    }


    /** tela de edicao de pedido
     * @param $id
     */
    function edit_order_composition($id){

        if(!isset($id)){
            $this->session->set_flashdata('flash_msg', createMessage("danger", "O pedido não foi encontrado"));
            redirect("dashboard");
        }

        // carrega o model
        $this->load->model("orders_model");
        $this->load->model("order_itens_model");

        // busca os dados do pedido
        $order = $this->orders_model->getOrder($id);

        // busca os itens do pedido
        $products = $this->order_itens_model->getItensOrder($order['id']);

        // load template
        $this->load->view("template/header");
        $this->load->view("pages/edit_order", [
            "order" => $order,
            "products" => $products
            ]
        );
        $this->load->view("template/footer");
    }

    /** funcao para remover um produto
     * @param $id
     */
    function delete($id){
        // carrega o model
        $this->load->model("orders_model");
        if($this->orders_model->delete($id)){
            // redireciona para a listagem
            $msg = createMessage("success", "O pedido foi removido com sucesso");
            $this->session->set_flashdata('flash_msg', $msg);
            redirect("dashboard");
        }else{
            $json['message'] = createMessage("danger", "Não foi possível remover o registro, por favor tente novamente");
            echo json_encode($json);
        }
    }

    /** funcao para reativar um usuario inativo para permiter alteracoes
     * @param $id
     */
    function activate($id){
        // carrega o model
        $this->load->model("orders_model");
        if($this->orders_model->activateProduct($id)){
            redirect('products/edit/' . $id);
        }else{
            $this->session->set_flashdata('flash_msg', createMessage("danger", "Algo errado ocorreu, o produto não pode ser reativado"));
        }
    }

    /**
     * funcao para ajax - retorna produtos para lancar no pedido
     */
    public function select_product(){
        $param = $this->input->get('term');
        // carrega o model
        $this->load->model("orders_model");
        $data = $this->orders_model->findLike($param);
        // monta a caixa de retorno com os itens
        $json = [];
        if ($data) {
            foreach ($data as $prod) {
                $json[] = [
                    'id' => $prod['id'],
                    'text' => $prod['name'],
                    'price' => $prod['price']
                ];
            }
        }
        echo json_encode($json);
    }

    /**
     * insere um item ao pedido
     */
    public function insert_product(){
        $post = $_POST;

        // carrega o model
        $this->load->model("order_itens_model");
        $this->load->model("products_model");

        $amount = (int) $post['amount'];
        if(!is_int($amount) || $amount < 1){
            $json['message'] = createMessage("warning", "Insira a qtde do produto para lançar");
            echo json_encode($json);
            return;
        }

        // checa se alteracao
        if(!isset($post['produto'])){
            // muda o id do produto para o mesmo cadastrado
            $post['produto'] = $post['id_product_alter'];
        }

        // pega o produto para alteracao
        $product = $this->products_model->findById($post['produto']);

//        $json = $post;
//        echo json_encode($json); return;

        // remove se tiver virgula
        if(strpos($post['price'],",")){
            $post['price'] = str_replace([".", ","], ["", "."], $post['price']);
        }

        $price = (float) $post['price'];
        // valida o valor correto
        if (!is_float($price) || $price < 0) {
            $json['message'] = createMessage("warning", "Informe um valor de preço correto");
            echo json_encode($json);
            return;
        }

        // monta o array de alteracao
        if(isset($post['id_item_alter']) && !empty($post['id_item_alter'])){
            // alterar item
            $data = [
                'id' => $post['id_item_alter'],
                "id_order"  => $post['order_id'],
                "id_product"  => $product['id'],
                "price"  => $post['price'],
                "amount"  => $post['amount']
            ];
            unset($post['id_item_alter']);
            unset($post['id_product_alter']);
        }else{
            // novo item
            $data = [
              "id_order"  => $post['order_id'],
              "id_product"  => $product['id'],
              "price"  => $post['price'],
              "amount"  => $post['amount']
            ];
        }

        if($this->order_itens_model->save($data)){
            $this->session->set_flashdata('flash_msg', createMessage("success", "O produto {$product['name']} foi removido com sucesso"));
            $json['redirect'] = base_url() . "orders/edit_order_composition/{$post['order_id']}";
            echo json_encode($json);
        }else{
            $json['message'] = createMessage("danger", "Não foi possível inserir o produto, por favor tente novamente");
            echo json_encode($json);
        }
    }


    /** remove um item do pedido
     * @param $id_product
     */
    public function delete_order_item($id_product){
        // carrega o model
        $this->load->model("order_itens_model");
        $itemOrder = $this->order_itens_model->findById($id_product);
        if($this->order_itens_model->delete($id_product)){
            redirect("orders/edit_order_composition/{$itemOrder['id_order']}");;
        }
    }

    /**
     * fecha o pedido
     */
    function close($order_id){
        // carrega o model
        $this->load->model("orders_model");
        $data = [
            "id" => $order_id,
            "status_order" => "1" // order closes
        ];
        if($this->orders_model->update($data)){
            redirect("dashboard");
        }else{
            $this->session->set_flashdata('flash_msg', createMessage("danger", "O pedido não pode ser fechado, por favor tente novamente"));
            redirect("orders/edit_order_composition/{$order_id}");;
        }
    }

    /**
     * funcao apenas para ver o pedido fechado
     */
    function view_order($id){
        if(!isset($id)){
            $this->session->set_flashdata('flash_msg', createMessage("danger", "O pedido não foi encontrado"));
            redirect("dashboard");
        }

        // carrega o model
        $this->load->model("orders_model");
        $this->load->model("order_itens_model");

        // busca os dados do pedido
        $order = $this->orders_model->getOrder($id);

        // busca os itens do pedido
        $products = $this->order_itens_model->getItensOrder($order['id']);

        // load template
        $this->load->view("template/header");
        $this->load->view("pages/view_order", [
                "order" => $order,
                "products" => $products
            ]
        );
        $this->load->view("template/footer");
    }

    /**
     * funcao para buscar
     */
    function search(){
        // carrega o model
        $this->load->model("orders_model");
        $this->load->model("order_itens_model");

        // recebe os parametros
        $date_begin = trim(filter_input(INPUT_POST,'date_begin',FILTER_DEFAULT));
        $date_end = trim(filter_input(INPUT_POST,'date_end',FILTER_DEFAULT));

        // busca os pedidos pelo periodo ou os ultimos 5
        if(isset($date_begin) && $date_begin != "" && isset($date_end) && $date_end != ""){
            $orders = $this->orders_model->searchOrders($date_begin, $date_end);
        }else{
            $orders = $this->orders_model->getRecentOrders();
        }

        // busta os itens de cada pedido
        $orders_itens = null;
        if(isset($orders) && is_array($orders) && sizeof($orders) > 0){
            for($i= 0; $i< sizeof($orders); $i++){
                $orders_itens = $this->order_itens_model->getItens($orders[$i]['id']);
                if(isset($orders_itens)){
                    $orders[$i]['itens'] = sizeof($orders_itens);
                }
            }
        }

        // load template
        $this->load->view("template/header");
        $this->load->view("pages/view_orders",[
                'orders' => $orders,
                'date_begin' => $date_begin,
                'date_end' => $date_end
            ]
        );
        $this->load->view("template/footer");
    }

}