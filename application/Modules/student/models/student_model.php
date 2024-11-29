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
        
        


        $sql="SELECT 
            users.name, 
            users.email, 
            users.dob, 
            usertypes.Category, 
            users.status,
            DATEDIFF(
                CASE 
                    WHEN DATE_FORMAT(users.dob, '%m-%d') >= DATE_FORMAT(CURDATE(), '%m-%d') 
                    THEN CONCAT(YEAR(CURDATE()), '-', DATE_FORMAT(users.dob, '%m-%d'))
                    ELSE CONCAT(YEAR(CURDATE()) + 1, '-', DATE_FORMAT(users.dob, '%m-%d'))
                END, 
                CURDATE()
            ) AS upcoming_day_count
        FROM 
            users 
        JOIN 
            usertypes 
        ON 
            users.users_id = usertypes.User_id";
            
                $query = $this->db->query($sql);
                
                $users = $query->result_array(); 
                return $users;
            }


    public function get_usertype(){

        $sql="select Category from usertypes";
        $query = $this->db->query($sql);
        $usertype = $query->result_array();

        return $usertype;
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
