<?php

class Order_itens_model extends CI_Model{

    private $table = "orders_itens";

    /** salva um novo item no pedido
     * @param $data
     * @return mixed
     */
    public function save($data): bool{

        if(isset($data['id']) && $data['id'] > 0){
            // atualiza
            $id = $data['id'];
            unset($data['id']);
            $this->db->where('id', $id);
            return $this->db->update($this->table,$data);
        }else{
            // salva um novo
            return $this->db->insert($this->table,$data);
        }
    }

    public function delete($id): bool{
        return $this->db->delete($this->table, array('id' => $id));
    }

    /** lista os itens de um pedido
     * @return array|null
     */
    public function getItens($id_order):?array {
        $query = $this->db->get_where($this->table,array("id_order" => $id_order));
        return $query->result_array();
    }

    public function findById($id){
        $query = $this->db->get_where($this->table,array('id' => $id));
        return $query->row_array();
    }

    public function update($data){
        $id = $data['id'];
        unset($data['id']);
        $this->db->where('id', $id);
        return $this->db->update($this->table,$data);
    }

    public function getItensOrder($id_order){
        $this->db->select('p.name as produto,p.id as id_product, orders_itens.*');
        $this->db->from($this->table);
        $this->db->join('products p', 'p.id = orders_itens.id_product');
        $this->db->where("orders_itens.id_order", $id_order);
        $query = $this->db->get();
        return $query->result_array();
    }

}