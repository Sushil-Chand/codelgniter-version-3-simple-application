<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class student_model extends CI_Model {

    public function __construct() 
    {
         parent::__construct();
         $this->load->database();
         
         }

    public function get_all_users() 
    {
        $users = $this->db->get('users')->result_array();
        
        return $users;
    }


    public function add_user($data) {
        // Insert the data into the database
        return $this->db->insert('users', $data);
    }

    // public function store($data) {
    //     // Insert data into the users table
    //     return $this->db->insert('users', $data);
    // }

    public function update_user($id, $data) {
        // Update user details by ID
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    public function delete_user($id) {
        // Delete a user by ID
        $this->db->where('id', $id);
        return $this->db->delete('users');
    }
}
