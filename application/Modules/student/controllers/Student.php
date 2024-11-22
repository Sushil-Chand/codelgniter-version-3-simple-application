<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends MX_Controller{


    
	 public function __construct() {
        parent::__construct();
        $this->load->model('student_model'); 
    }

    public function index()
	{
	
		$users = $this->student_model->get_all_users();
        $this->load->view('student',$users);
    }

	


    
	public function get_users() {
        $users = $this->student_model->get_all_users();
        echo json_encode($users);
    }
	

	public function add_user() 
   {
    // Get the raw POST data
    $rawData = file_get_contents("php://input");
    $postData = json_decode($rawData, true); // Decode JSON to array

    // Validate received data
    if (!isset($postData['name']) || !isset($postData['email'])) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input data.']);
        return;
    }

    $name = $postData['name'];
    $email = $postData['email'];

    // Prepare data for insertion
    $data = [
        'name'  => $name,
        'email' => $email
    ];

    // Insert into database
    $inserted = $this->student_model->add_user($data);

    if ($inserted) {
        echo json_encode(['status' => 'success', 'message' => 'User added successfully!', 'data' => $data]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add user.']);
    }
  }

	
	// public function store() {
	// 	// Load the database library if not autoloaded
	
	
	// 	// Validate incoming POST data
	// 	$name = $this->input->post('name', TRUE); // The second parameter TRUE enables XSS filtering
	// 	$email = $this->input->post('email', TRUE);
	
	// 	// Check for missing fields
	// 	if (empty($name) || empty($email)) {
	// 		echo json_encode(['status' => 'error', 'message' => 'Name and Email are required.']);
	// 		return;
	// 	}
	
	// 	// Prepare data for insertion
	// 	$data = [
	// 		'name'  => $name,
	// 		'email' => $email,
	// 		'created_at' => date('Y-m-d H:i:s') // Optional: Add timestamp for better tracking
	// 	];
	
	// 	$inserted = $this->student_model->store($data);

    //     if ($inserted) {
    //         echo json_encode(['status' => 'success', 'message' => 'User stored successfully!', 'data' => $data]);
    //     } else {
    //         echo json_encode(['status' => 'error', 'message' => 'Failed to store user. Please try again.']);
    //     }
	// }



	public function update_user() {
		
		try{
			
			$id = $this->input->post('id');
			$name = $this->input->post('name');
			$email = $this->input->post('email');
		
			if(empty($id) || empty($name) || empty($email)) 
			{ 
				echo json_encode(['status' => 'error', 'message' => 'All fields are required.u']);
				 return; 
			}


			// Prepare data for update
			$data = [
				'name'  => $name,
				'email' => $email
			];
		
			// Update the database
			$updated = $this->student_model->update_user($id, $data);
			
			if($updated) { // Respond back with success 
			echo json_encode(['status' => 'success', 'message' => 'User updated successfully!']);
		 	} else 
		 	{ 
			echo json_encode(['status' => 'error', 'message' => 'Failed to update user.']); 
			}
			

		} catch (Exception $e)
		{
			echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]); 
		}
	}

	public function delete_user() {
	
		// Get POST data
		$id = $this->input->post('id');
	
		// Delete from the database
		
        $deleted = $this->student_model->delete_user($id);
	
		// Respond back with success
		echo json_encode(['status' => 'success', 'message' => 'User deleted successfully!']);
	}
	
	
	
}

