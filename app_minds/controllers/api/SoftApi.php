<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class SoftApi extends REST_Controller
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function orders_get()
    {
        // get parameters from get
        $date_begin = $this->input->get('date_begin');
        $date_end = $this->input->get('date_end');

        // load orders model
        $this->load->model('orders_model');
        $this->load->model("order_itens_model");
        $this->load->model("users_model");

        // get data from databse
        $response = $this->orders_model->getAllClosedOrders($date_begin, $date_end);

        // busta os itens de cada pedido
        $response_itens = null;
        if (isset($response) && is_array($response) && sizeof($response) > 0) {
            for ($i = 0; $i < sizeof($response); $i++) {

                // obter os itens
                $response_itens = $this->order_itens_model->getItensOrder($response[$i]['id']);
                if (isset($response_itens)) {
                    $response[$i]['itens'] = $response_itens;
                }
                // dados do fornecedor
                $supplier = $this->users_model->findById($response[$i]['id_supplier']);
                $response[$i]['supplier'] = $supplier;
                // dados do usuario que cadastrou
                $user = $this->users_model->findById($response[$i]['id_user']);
                $response[$i]['user'] = $user;
            }

            // retorna todos os registros
            $this->response($response, REST_Controller::HTTP_OK);

        } else {
            // nenhum registro encontrado
            $this->response([
                'status' => false,
                'message' => 'Nenhum registro encontrado, informe outras datas'
            ], REST_Controller::HTTP_NOT_FOUND);

        }

        // BAD_REQUEST (400) being the HTTP response code
//        $this->response(null, REST_Controller::HTTP_BAD_REQUEST);

    }

    public function orders_post()
    {
        // Recurso não disponível
        $message = $this->response([
            'status' => false,
            'message' => 'Recurso não disponível'
        ], REST_Controller::HTTP_NOT_FOUND);
        $this->set_response($message, REST_Controller::HTTP_METHOD_NOT_ALLOWED);
    }

    public function orders_delete()
    {
        // Recurso não disponível
        $message = $this->response([
            'status' => false,
            'message' => 'Recurso não disponível'
        ], REST_Controller::HTTP_NOT_FOUND);
        $this->set_response($message, REST_Controller::HTTP_METHOD_NOT_ALLOWED);
    }

}
