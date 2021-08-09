<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
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
        $this->load->view("pages/add_user");
        $this->load->view("template/footer");
    }

    /** funcao para adicionar e/ou alterar usuarios
     * @param null $id
     */
    public function add_user($id = null)
    {
        // carrega o model
        $this->load->model("users_model");

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

            // exige todos os campos / exceto quando nao for colaborador fornecedor
            if($post['is_supplier'] == "0"){
                unset($post['address_street_02']);
                unset($post['address_number_02']);
                unset($post['address_city_02']);
                unset($post['address_state_02']);
                unset($post['address_zip_code_02']);
            }else{
                // cancela o acesso ao portal para fornecedor automaticamente
                $post['portal_access'] = "0";
            }

            if (in_array("", $post)) {
                $json['message'] = createMessage("warning", "Por favor preencha todos os campos");
                echo json_encode($json);
                return;
            }

            // validacao de email
            if (!filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL)) {
                $json['message'] = createMessage("warning", "Por favor, informe um email válido");
                echo json_encode($json);
                return;
            }

            // verifica duplicidade de email
            $ret = $this->users_model->findByEmail($post['email']);
            if(isset($ret) && $id != null){
                $json['message'] = createMessage("warning", "Este email não está disponível, por favor, informe outro email");
                echo json_encode($json);
                return;
            }

            $pass1test = $post['password'];
            $pass2test = $post['password2'];
            // checa tamanho da senha
            if(!is_passwd($post['password'])){
                $json['message'] = createMessage("warning", "Por favor, as senhas devem ter entre 8 e 40 caracteres");
                echo json_encode($json);
                return;
            }else{
                // reincripta o password para continuar seguro
                $post['password'] = passwd($post['password']);
            }

            // trata a senha
            if ($pass1test != $pass2test) {
                $json['message'] = createMessage("warning", "Por favor, as senhas devem coincidir");
                echo json_encode($json);
                return;
            }
            unset($post['password2']);

            // checa se foi passado id de usuario por parametro
            if(isset($id)){
                $post['id'] = $id;
            }

            // salva o novo usuario
            if($this->users_model->save($post)){
                // redireciona para a listagem de usuarios
//                $this->session->flash_msg = createMessage("success", "O usuário {$post['name']} foi salvo com sucesso");

                $msg = createMessage("success", "O usuário {$post['name']} foi salvo com sucesso");
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
        $this->load->view("pages/add_user");
        $this->load->view("template/footer");
    }

    /** tela de edicao de usuario
     * @param $id
     */
    function edit($id){
        // carrega o model
        $this->load->model("users_model");
        $userAlt = null;
        // checa se tem usuario pra alteracao
        if(isset($id)){
            $userAlt = $this->users_model->findById($id);
        }
        // load template
        $this->load->view("template/header");
        $this->load->view("pages/add_user", ["userAlt" => $userAlt]);
        $this->load->view("template/footer");
    }

    /** funcao para remover um usuario
     * @param $id
     */
//    function delete($id){
//        // carrega o model
//        $this->load->model("users_model");
//        $user = $this->users_model->findById($id);
//        if($this->users_model->delete($id)){
//            // redireciona para a listagem de usuarios
//            $msg = createMessage("success", "O usuário {$user['name']} foi removido com sucesso");
//            $this->session->set_flashdata('flash_msg', $msg);
//            redirect("dashboard");
//        }else{
//            $json['message'] = createMessage("danger", "Não foi possível remover o registro, por favor tente novamente");
//            echo json_encode($json);
//            return;
//        }
//    }

    /** funcao para reativar um usuario inativo para permiter alteracoes
     * @param $id
     */
    function activate($id){
        // carrega o model
        $this->load->model("users_model");
        if($this->users_model->activateUser($id)){
            redirect('users/edit/' . $id);
        }else{
            $this->session->set_flashdata('flash_msg', createMessage("danger", "Algo errado ocorreu, o usuário não pode ser reativado"));
        }
    }

    /**
     * funcao para buscar
     */
    function search(){
        // checa se o usuario esta logado
        is_logged();

        // carrega o model
        $this->load->model("users_model");

        $term = trim(filter_input(INPUT_POST,'search',FILTER_DEFAULT));
        if(isset($term)){
            $users = $this->users_model->searchUsers($term);
        }else{
            $users = $this->users_model->getRecentUsers();
        }

        // load template
        $this->load->view("template/header");
        $this->load->view("pages/view_users",[
            'users' => $users,
            'term' => $term
            ]
        );
        $this->load->view("template/footer");
    }
}