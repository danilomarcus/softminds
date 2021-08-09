<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Signin extends CI_Controller
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
        // checa se esta logado e redireciona para o portal
        if (isset($this->session->userdata['logged_user']['username'])){
            redirect("dashboard");
        }

        // load template
        $this->load->view("pages/signin");
    }

    /**
     * faz o login por sessao
     */
    public function signin()
    {
        // carrega o model
        $this->load->model("users_model");

        // recebe dados para login
        $email = addslashes(trim(filter_input(INPUT_POST, 'mail', FILTER_VALIDATE_EMAIL)));
        $password = addslashes(trim(filter_input(INPUT_POST, 'pass', FILTER_DEFAULT)));

        if (empty($email) || empty($password)) {
            // campos vazios
            $this->session->set_flashdata('flash_msg', createMessage("info",
                "Por favor preencha todos os dados"));
            redirect("signin");
        } else {

            if (isset($email)) {

                // busca o usuario pelo email
                $user = $this->users_model->findByEmail($email);

                if (!$user) {
                    // usuario nao existe
                    $this->session->set_flashdata('flash_msg', createMessage("info",
                        "Dados inválidos, por favor verifique os dados informados e tente novamente"));
                    redirect("signin");
                } else {

                    // checa a senha
                    if (!passwd_verify($password, $user['password'])) {

                        // usuario nao existe
                        $this->session->set_flashdata('flash_msg', createMessage("info",
                            "Dados inválidos, por favor verifique os dados informados e tente novamente"));
                        redirect("signin");

                    } else {

                        // password OK

                        //checa se eh um fornecedor, pois este nao pode acessar o portal
                        if($user['is_supplier'] == "1")
                        {

                            $firstName = str_limit_words($user['name'],1,"");
                            $this->session->set_flashdata('flash_msg', createMessage("info",
                                "Olá, {$firstName}, infelizmente você não pode acessar o portal da " . CONF_SITE_NAME));
                            redirect("signin");

                        }
                        // checa se o usuario tem acesso ao portal
                        elseif ($user['portal_access'] == "0")
                        {

                            // usuario nao pode acessar o portal
                            // usuario nao existe
                            $firstName = str_limit_words($user['name'],1,"");
                            $this->session->set_flashdata('flash_msg', createMessage("info",
                                "Olá, {$firstName}, parece que você não pode acessar essa área, entre em contato com o suporte da " . CONF_SITE_NAME));
                            redirect("signin");

                        }
                        else
                        {
                            // acesso permitido
                            // cria os dados do usuario logado e joga na sessao
                            $newdata = array(
                                'id' => $user['id'],
                                'username' => $user['name'],
                                'email' => $user['email'],
                                'status_active' => $user['status_active'],
                                'logged_in' => true
                            );
                            $this->session->set_userdata('logged_user', $newdata);

                            // atualiza o hash da senha no banco
                            $this->users_model->save($user);

                            // redireciona para o painel do sistema
                            redirect("dashboard");

                        }

                    }

                }

            }

        }
    }

    /**
     * funcao para deslogar
     */
    public function signout()
    {
        if(!isset($this->session->userdata['logged_user'])){
            redirect("signin/signin");
        }

        $firstName = str_limit_words($this->session->userdata['logged_user']['username'],1,"");
        $msg = createMessage("info",
            "Olá, {$firstName}, você deslogou com sucesso, volte logo");
        $this->session->set_flashdata('flash_msg', $msg);

        // remove a sessao do usuario logado
        $this->session->unset_userdata('logged_user');

        redirect("signin");
    }

}