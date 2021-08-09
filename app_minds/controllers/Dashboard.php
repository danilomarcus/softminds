<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    /**
     * funcao para abrir o dashboard com os dados recentes do sistema
     */
    public function index()
    {
        // checa se o usuario esta logado
        is_logged();

        // busca a lista de ultimos usuarios cadastrados
        $this->load->model("users_model");
        $this->load->model("products_model");
        $this->load->model("orders_model");
        $this->load->model("order_itens_model");

        $users = $this->users_model->getRecentUsers();
        $products = $this->products_model->getRecentProducts();
        $orders = $this->orders_model->getRecentOrders();
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
        $this->load->view("pages/dashboard", [
            'users' => $users,
            'orders' => $orders,
            'orders_itens' => $orders_itens,
            'products' => $products
            ]
        );
        $this->load->view("template/footer");

    }

}