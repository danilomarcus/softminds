<?php

class Products_model extends CI_Model{

    private $table = "products";

    /** salva um novo produto
     * @param $data
     * @return mixed
     */
    public function save($data): bool{

        if(isset($data['id']) && $data['id'] > 0){
            // atualiza um produto
            $id = $data['id'];
            unset($data['id']);
            $this->db->where('id', $id);
            return $this->db->update($this->table,$data);
        }else{
            // salva um novo produto
            return $this->db->insert($this->table,$data);
        }
    }

    public function delete($id): bool{
        return $this->db->delete($this->table, array('id' => $id));
    }

    /** lista os ultimos 5 produtos cadastrados
     * @return array|null
     */
    public function getRecentProducts():?array {
        $query = $this->db->order_by('id', 'DESC')->limit(5)->get($this->table);
        return $query->result_array();
    }

    public function findById($id){
        $query = $this->db->get_where($this->table,array('id' => $id));
        return $query->row_array();
    }

    public function findByName($param){
        $this->db->like('name',$param);
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    public function update($data){
        $id = $data['id'];
        unset($data['id']);
        $this->db->where('id', $id);
        return $this->db->update($this->table,$data);
    }

    public function activateProduct($id){
        $data = array(
            'status_active' => '1'
        );
        $this->db->where('id', $id);
        return $this->db->update($this->table,$data);
    }

    /** retorna a busca pelo nome do produto
     * @param $term
     * @return mixed
     */
    public function searchProducts($term){
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->like('name', $term, 'both');
        $query = $this->db->get();
        return $query->result_array();
    }

}