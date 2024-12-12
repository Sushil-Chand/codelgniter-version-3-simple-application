<?php
defined('BASEPATH') or exit('No direct script access allowed');

class student_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->library('upload');

    }

    public function get_all_users()
    {
        $search_data = $this->input->post('search');

		$search_value = $search_data['value'];

        // order by column name asc

       
        
        
        $sort_the_column= $this->input->post('order');
        $sort_column = $sort_the_column['0']['column'];

       
        $sort_column_dir= $sort_the_column['0']['dir'];
        
        // order by $column_name $sort_column_dir

        $column_data = $this->input->post('columns');
        $column_name = $column_data[$sort_column]['data'];

        

        if(!empty($search_value))
        {
            $search_condition = "where name like '%$search_value%' or email like '%$search_value%' or dob like '%$search_value%' or status like '%$search_value%'or user_id like '%$search_value'";
        }
        else{
            $search_condition = "";
        }


        if(!empty($column_name!='sn'))
        {
            
            $order_by_sql = "order by  $column_name $sort_column_dir";
        }
        else{
           
            $order_by_sql= '';
        }
        
        $sql = "SELECT
            users.id, 
            users.name as name, 
            users.email as email, 
            users.dob as dob,
            usertypes.Category as category,

         
            usertypes.User_id as users_id, 
            users.status as status,
            users.profile_pic,
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
            users.users_id = usertypes.User_id $search_condition
        
         $order_by_sql  ";



       
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
            // $this->db->or_like('User_id', $search);
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
       $query= $this->db->insert('users', $data);
       if($query){
      
        return true;
      }
      else{
        
        return false;
      }

    }

    public function update_user($id, $data)
    {


        // Update user details by ID
        $this->db->where('id', $id);
        $query= $this->db->update('users', $data);

        if($query){
            return true;
        }else{
            return false;
        }
    }

    public function delete_user($id)
    {

        // Delete a user by ID
        $this->db->where('id', $id);
        return $this->db->delete('users');
    }

    public function get_users_by_id($id){
        $this->db->select('profile_pic');
        $this->db->from('users');
        $this->db->where('id',$id);
        $query =$this->db->get();

        $curr_img = $query->row_array();

         return  $curr_img['profile_pic'];     
    }

    public function get_province(){

        $this->db->select('p_name');
        $this->db->from('provinces');
        $query = $this->db->get();

        $province_name= $query->result_array();

        return $province_name;

    }




    


   
}