<?php

class Users_model extends CI_Model{

    private $table = "users";

    /** salva um novo usuario
     * @param $data
     * @return mixed
     */
    public function save($data): bool{

        // aceita apenas digitos (numeros) para telefone e documento (cpf e cnpj)
        $data['phone'] = preg_replace("/\D/",'',$data['phone']);
        $data['document'] = preg_replace("/\D/",'',$data['document']);

        if(isset($data['id']) && $data['id'] > 0){
            $id = $data['id'];
            unset($data['id']);
            // atualiza um usuario
            $this->db->where('id', $id);
            return $this->db->update($this->table,$data);
        }else{
            // salva um novo usuario
            return $this->db->insert($this->table,$data);
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id): bool{
        return $this->db->delete($this->table, array('id' => $id));
    }

    /** lista os ultimos 5 usuarios cadastrados
     * @return array|null
     */
    public function getRecentUsers():?array {
        $query = $this->db->order_by('id', 'DESC')->limit(5)->get($this->table);
        return $query->result_array();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id){
        $query = $this->db->get_where($this->table,array('id' => $id));
        return $query->row_array();
    }

    /**
     * @param $email
     * @return mixed
     */
    public function findByEmail($email){
        $query = $this->db->get_where($this->table,array('email' => $email));
        return $query->row_array();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function update($data){
        $id = $data['id'];
        unset($data['id']);
        $this->db->where('id', $id);
        return $this->db->update($this->table,$data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function activateUser($id){
        $data = array(
            'status_active' => '1'
        );
        $this->db->where('id', $id);
        return $this->db->update($this->table,$data);
    }

    /** retorna a lista de fornecedores para o pedido
     * @return mixed
     */
    public function getSuppliers(){
        $query = $this->db->get_where($this->table,array('is_supplier' => "1"));
        return $query->result_array();
    }

    /** retorna a busca de usuarios pelo nome
     * @param $term
     * @return mixed
     */
    public function searchUsers($term){
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->like('name', $term, 'both');
        $query = $this->db->get();
        return $query->result_array();
    }

}