<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Products extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // checa se o usuario esta logado
        is_logged();
    }

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
        // load template
        $this->load->view("template/header");
        $this->load->view("pages/add_product");
        $this->load->view("template/footer");
    }

    /** funcao para adicionar e/ou alterar produtos
     * @param null $id
     */
    public function add_product($id = null)
    {
        // carrega o model
        $this->load->model("products_model");

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

            $post['price'] = str_replace([".", ","], ["", "."], $post['price']);

            $price = (float) $post['price'];
            // valida o valor correto
            if (!is_float($price) && $price < 0) {
                $json['message'] = createMessage("warning", "Informe um valor de preço correto");
                echo json_encode($json);
                return;
            }

            // checa se foi passado id por parametro
            if(isset($id)){
                $post['id'] = $id;
            }

            // salva o novo produto
            if($this->products_model->save($post)){
                // redireciona para a listagem
                $msg = createMessage("success", "O produto {$post['name']} foi salvo com sucesso");
                $this->session->set_flashdata('flash_msg', $msg);

                $json['redirect'] = base_url() . "dashboard";
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
        $this->load->view("pages/add_product");
        $this->load->view("template/footer");
    }

    /** tela de edicao de produto
     * @param $id
     */
    function edit($id){
        // carrega o model
        $this->load->model("products_model");
        $userAlt = null;
        // checa se tem produto pra alteracao
        if(isset($id)){
            $userAlt = $this->products_model->findById($id);
        }
        // load template
        $this->load->view("template/header");
        $this->load->view("pages/add_product", ["alter" => $userAlt]);
        $this->load->view("template/footer");
    }

    /** funcao para remover um produto
     * @param $id
     */
//    function delete($id){
//        // carrega o model
//        $this->load->model("products_model");
//        $prod = $this->products_model->findById($id);
//        if($this->products_model->delete($id)){
//            // redireciona para a listagem
//            $msg = createMessage("success", "O produto {$prod['name']} foi removido com sucesso");
//            $this->session->set_flashdata('flash_msg', $msg);
//            redirect("dashboard");
//        }else{
//            $json['message'] = createMessage("danger", "Não foi possível remover o registro, por favor tente novamente");
//            echo json_encode($json);
//        }
//    }

    /** funcao para reativar um usuario inativo para permiter alteracoes
     * @param $id
     */
    function activate($id){
        // carrega o model
        $this->load->model("products_model");
        if($this->products_model->activateProduct($id)){
            redirect('products/edit/' . $id);
        }else{
            $this->session->set_flashdata('flash_msg', createMessage("danger", "Algo errado ocorreu, o produto não pode ser reativado"));
        }
    }

    function get_product(){
        $id = $this->input->post('id');
        // carrega o model
        $this->load->model("products_model");
        $product = $this->products_model->findById($id);
        echo json_encode($product);
    }

    /**
     * funcao para buscar
     */
    function search(){
        // carrega o model
        $this->load->model("products_model");

        $term = trim(filter_input(INPUT_POST,'search',FILTER_DEFAULT));
        if(isset($term)){
            $products = $this->products_model->searchProducts($term);
        }else{
            $products = $this->products_model->getRecentProducts();
        }

        // load template
        $this->load->view("template/header");
        $this->load->view("pages/view_products",[
                'products' => $products,
                'term' => $term
            ]
        );
        $this->load->view("template/footer");
    }
}