<?php
defined('BASEPATH') or exit('No direct script access allowed');

class student_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();

    }

    public function get_all_users()
    {

        $sql = "SELECT
            users.id, 
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


    public function Usertype()
    {


        $search = $this->input->post('searchTerm');

        $this->db->select('*');
        $this->db->from('usertypes');

        //Apply search filter if search term is provided
        if (!empty($search)) {
            $this->db->group_start(); // Group WHERE conditions for OR clause
            $this->db->like('Category', $search);
            $this->db->or_like('User_id', $search);
            $this->db->group_end();
        }

        $query = $this->db->get();

        // Return the filtered data as JSON
        return ($query->result());

    }


    public function add_user($data)
    {
        // Insert the data into the database
        return $this->db->insert('users', $data);


    }

    public function store($data)
    {
        // Insert data into the users table
        return $this->db->insert('users', $data);
    }

    public function update_user($id, $data)
    {


        // Update user details by ID
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    public function delete_user($id)
    {

        // Delete a user by ID
        $this->db->where('id', $id);
        return $this->db->delete('users');
    }
}