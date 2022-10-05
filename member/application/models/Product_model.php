<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_model extends CI_Model
{

    public function get($id)
    {
        $this->db->where('id', $id);
        $this->db->order_by('version', 'desc');
        $this->db->limit(1);
        $this->db->from('products');
        $product = $this->db->get()->first_row();
        return $product;
    }

    public function get_all($active_only = true)
    {
        $this->db->order_by('id');
        $this->db->from('products');
        $products = $this->db->get()->result();
        return $products;
    }

    public function add($data)
    {
        $data['id'] = $this->get_next_product_id();
        $data['version'] = 1;
        $this->db->insert('products', $data);
        return $data['id'];
    }

    public function remove($product_id, $deleted_by)
    {
        $product = (array) $this->get($product_id);
        $product['version'] = $this->get_next_version($product_id);
        $product['created_by'] = $deleted_by;
        $product['removed'] = 1;
        $this->db->insert('products', $product);
        return $this->db->affected_rows();
    }

    public function update($product_id, $data)
    {
        $data['id'] = $product_id;
        $data['version'] = $this->get_next_version($product_id);
        $this->db->insert('products', $data);
        return $data['id'];
    }

    public function get_next_product_id()
    {
        $this->db->select('MAX(id) as id');
        $this->db->from('products');
        $product_id = $this->db->get()->first_row()->id;
        return $product_id + 1;
    }

    public function get_next_version($product_id)
    {
        $this->db->select('MAX(version) as version');
        $this->db->where('id', $product_id);
        $this->db->from('products');
        $version = $this->db->get()->first_row()->version;
        return $version + 1;
    }
}
