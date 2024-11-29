<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends MX_Controller{


    
	 public function __construct() {
        parent::__construct();
        $this->load->model('student_model'); 
    }

    public function index()
	{
		
		$usertype=$this->student_model->get_usertype();

		
        $this->load->view('student', $usertype);

    }

	


    
	public function get_users() {
        $users =$this-> birthdaycount();
		
        echo json_encode($users);
    }
	

	public function add_user() 
   {	
		

		

	$output= array('status' => 'error', 
					'message' => 'please fill correct validation.');

    try {
        // Load form validation library
        $this->load->library('form_validation');

        // Set validation rules
        $this->form_validation->set_rules('name', 'Name', 'required|min_length[5]|alpha');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
	


        // Run validation
        if ($this->form_validation->run() === TRUE) {
            // Retrieve sanitized input
            $name = $this->input->post('name');
            $email = $this->input->post('email');

            // Prepare data for insertion
            $data = [
                'name'  => $name,
                'email' => $email
				
            ];

            // Insert into database
            $inserted = $this->student_model->add_user($data);

            if ($inserted) {
                $output= array('status' => 'success', 'message' => 'User added successfully!', 'data' => $data);
			}
			else{
				throw new Exception('An unexpected error occurred.failed! store user');
			}

        } else {
            
            throw new Exception(validation_errors());
        }
    } 
	catch (Exception $e) 
	{
        // Log the exception and return a generic error
        log_message('error', $e->getMessage());

        //echo json_encode(['status' => 'error', 'message' => 'An unexpected error occurred.']);
		$output['status'] = 'error';	
		$output['message'] = $e->getMessage();
    }

	echo json_encode($output);
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



	public function update_user()
	{	
		$output= array('status' => 'error', 
					'message' => 'please fill correct validation.');	
			try
			{
				$id = $this->input->post('id');
				$name = $this->input->post('name');
				$email = $this->input->post('email');
			
				// Load form validation library
				$this->load->library('form_validation');

				// Set validation rules
				$this->form_validation->set_rules('name', 'Name', 'required|min_length[5]|alpha');
				$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		
				// Run validation
				if ($this->form_validation->run() === TRUE)
				{

					// Prepare data for update
					$data = [
						'name'  => $name,
						'email' => $email
					];
				
					// Update the database
					$updated = $this->student_model->update_user($id, $data);
					
					if($updated) { // Respond back with success 
					$output= array('status' => 'success', 'message' => 'User updated successfully!');
					} else 
					{ 
					throw new Exception('Failed to update user.'); 
					}
				}
				else
				{
					throw new Exception(validation_errors());
				}

			}
			catch (Exception $e)
			{
					// Log the exception and return a generic error	
					log_message('error', $e->getMessage());
					$output['status]'] = 'error';
					$output['message'] = $e->getMessage();

			}
			
		echo json_encode($output);		
	}

	public function delete_user() {
	
		try{
			$id = $this->input->post('id');
		
			// Delete from the database
			
			$deleted = $this->student_model->delete_user($id);
		
			// Respond back with success
			if ($deleted){
				$output= array('status' => 'success', 'message' => 'User deleted successfully!');
			} else {
				throw new Exception('Failed to delete user.');
			}
		}catch (Exception $e) {

			$output=$e->getMessage();

		}

		echo json_encode($output);		
		
	}


	public function birthdaycount(){

		$users = $this->student_model->get_all_users();

		$result = [];
    
    // Loop through each student to calculate days until the next birthday
    foreach ($users as $student) {
        $dob = $student['dob']; //

		
        $birthdayDate = new DateTime($dob);
		
        $today = new DateTime();

		

		
		$originalDate = $birthdayDate->format('y-m-d');
		
		
		$newDate = date("d-m-Y", strtotime($originalDate));
		
        $birthdayDate->setDate($today->format('Y')+1, $birthdayDate->format('m'), $birthdayDate->format('d'));
        
	
		
        

        $daysUntilBirthday = $today->diff($birthdayDate)->days;

		

		$result[] = [
            'name' => $student['name'],
            'email' => $student['email'],
            'dob' => $student['dob'],
			'Category' => $student['Category'],
			'status' => $student['status'],
			'upcoming_day_count' => $student['upcoming_day_count'],
            'days_until_birthday' => $daysUntilBirthday,
			'newDate' => $newDate
        ];

       
    }

   

		return $result;
		


	}
	
	
	
}



