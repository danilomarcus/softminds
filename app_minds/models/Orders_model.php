<?php

class Orders_model extends CI_Model{

    private $table = "orders";

    /** salva um novo pedido
     * @param $data
     * @return mixed
     */
    public function save($data):?string {

        if(isset($data['id']) && $data['id'] > 0){
            // atualiza
            $id = $data['id'];
            unset($data['id']);
            $this->db->where('id', $id);
            if($this->db->update($this->table,$data)){
                return $id;
            }
        }else{
            // salva um novo
            if($this->db->insert($this->table,$data)){
                return $this->db->insert_id();
            }
        }
        return null;
    }

    public function delete($id): bool{
        return $this->db->delete($this->table, array('id' => $id));
    }

    /** lista os ultimos 5 pedidos
     * @return array|null
     */
    public function getRecentOrders():?array {
        $this->db->select('s.name as supplier, u.name as user, orders.*');
        $this->db->from($this->table);
        $this->db->join('users s', 's.id = orders.id_supplier');
        $this->db->join('users u', 'u.id = orders.id_user');
        $this->db->order_by('orders.id', 'DESC');
        $this->db->limit(5);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function findById($id){
        $query = $this->db->get_where($this->table,array('id' => $id));
        return $query->row_array();
    }

    public function findBySupplier($param){
        $query = $this->db->get_where($this->table,array('id_supplier' => $param));
        return $query->result_array();
    }

    public function update($data){
        $id = $data['id'];
        unset($data['id']);
        $this->db->where('id', $id);
        return $this->db->update($this->table,$data);
    }

    /** retorna os dados do pedido
     * @param $id
     * @return mixed
     */
    public function getOrder($id){
        $this->db->select('s.name as supplier, u.name as user, orders.id, orders.details');
        $this->db->from($this->table);
        $this->db->join('users s', 's.id = orders.id_supplier');
        $this->db->join('users u', 'u.id = orders.id_user');
        $this->db->where("orders.id", $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function findLike($term){
        $this->db->select('*');
        $this->db->from('products');
        $this->db->like('name', $term, 'both');
        $this->db->where("status_active", '1');
        $query = $this->db->get();
        return $query->result_array();
    }

    /** retorna a busca pelo
     * @param $term
     * @return mixed
     */
    public function searchOrders($term1, $term2){
        $this->db->select('s.name as supplier, u.name as user, orders.*');
        $this->db->from($this->table);
        $this->db->join('users s', 's.id = orders.id_supplier');
        $this->db->join('users u', 'u.id = orders.id_user');
        $this->db->where(" date(orders.created_at)  BETWEEN '$term1' AND '$term2'");
        $query = $this->db->get();
        return $query->result_array();
    }

    /** funcao para retorno na API
     * @param $term1
     * @param $term2
     * @return mixed
     */
    public function getAllClosedOrders($term1=null, $term2=null){
        $this->db->select('s.name as supplier, u.name as user, orders.*');
        $this->db->from($this->table);
        $this->db->join('users s', 's.id = orders.id_supplier');
        $this->db->join('users u', 'u.id = orders.id_user');
        $this->db->where("orders.status_order='1' AND date(orders.created_at)  BETWEEN '$term1' AND '$term2'");
        $query = $this->db->get();
        return $query->result_array();
    }


}