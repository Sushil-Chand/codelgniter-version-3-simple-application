		<?php

		use LDAP\Result;

		defined('BASEPATH') or exit('No direct script access allowed');

		class Student extends MX_Controller
		{



			public function __construct()
			{
				parent::__construct();
				$this->load->model('student_model');
				$this->load->helper(array('form', 'url'));
				$this->load->library('upload');
			}

			public function index()
			{
				$this->db->select('*');
				$this->db->from('usertypes');
				$query = $this->db->get();

				$Data = $query->result_array();


				
				$this->load->view('student', $Data);

			}


			public function store_student()
			{


				$name = $this->input->post('name');
				$email = $this->input->post('email');
				$dob = $this->input->post('dob');
				$status = $this->input->post('status');
				$users_id = $this->input->post('users_id');
							
				$uploads_dir = 'Images/';
				$img_name = $_FILES['profile_pic']['name'];
				$imgnumber= rand(1,10);
				$img_unique=date("Y-m-d").$imgnumber.$img_name;
				
				
				if (is_uploaded_file($_FILES['profile_pic']['tmp_name']))
				{       
					 move_uploaded_file($_FILES['profile_pic']['tmp_name'], $uploads_dir.$img_unique);	 
				}
				
					$data = [
						'name' => $name,
						'email' => $email,
						'dob' => $dob,
						'status' => $status,
						'users_id' => $users_id,
						'profile_pic'=>$img_unique

					];
		
					$flag = $this->student_model->store($data);

					if($flag==True){
					 $this->session->set_flashdata('success', 'Sucessful addeD User Data');
				  }else
				{
					$this->session->set_flashdata('error', 'Something is wrong. Error!!');
				}	
					redirect('student');
						
			}
			

















		public function test()
		{

		$this->load->view('test');

		}


		public function getData()
		{
		// $users = $this->birthdaycount();
		// echo json_encode($users);

		}

		public function usertype()
		{


		$category['usertypes'] = $this->student_model->Usertype();
		echo json_encode($category['usertypes']);
		}







		public function get_users()
		{
		// Retrieve the search query from POST
		$search = $this->input->post('searchTerm');

		// Select all user data
		$this->db->select('*');
		$this->db->from('users');

		// Apply search filter if search term is provided
		if (!empty($search)) {
		$this->db->group_start(); // Group WHERE conditions for OR clause
		$this->db->like('name', $search);
		$this->db->or_like('email', $search);
		$this->db->group_end();
		}

		$query = $this->db->get();

		// Return the filtered data as JSON
		echo json_encode($query->result());
		}



			public function add_user()
			{

			$output = array(
			'status' => 'error',
			'message' => 'please fill correct validation.'
			);

			try {
			// Load form validation library
			$this->load->library('form_validation');

			// Set validation rules
			$this->form_validation->set_rules('name', 'Name', 'required|min_length[5]');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('dob', 'Date of Birth', 'required');
			$this->form_validation->set_rules('status', 'Status', 'required');
			$this->form_validation->set_rules('userfile', 'userfile', 'required');




			// Run validation
			if ($this->form_validation->run() === TRUE) {
			// Retrieve sanitized input
			$name = $this->input->post('name');
			$email = $this->input->post('email');
			$dob = $this->input->post('dob');
			$status = $this->input->post('status');
			$users_id = $this->input->post('users_id');
			$userfile = $this->input->post('userfile');

			// $config['upload_path'] = './images/uploads';
			// $config['allowed_types'] = 'gif|jpg|png';
			// $this->load->library('upload', $config);

			// if (!$this->upload->do_upload('userfile'));




		$output = array('error' => $this->upload->display_errors());

		if (empty($users_id)) {
		throw new Exception('please Choose the user Category');
		}

		// Prepare data for insertion
		$data = [
		'name' => $name,
		'email' => $email,
		'dob' => $dob,
		'status' => $status,
		'users_id' => $users_id,
		'userfile'=> ''

		];



			// Insert into database
			$inserted = $this->student_model->add_user($data);

			if ($inserted) {
			$output = array('status' => 'success', 'message' => 'User added successfully!', 'data' => $data);
			} else {
			throw new Exception('An unexpected error occurred.failed! store user');
			}

		} else {

		throw new Exception(validation_errors());
		}
		} catch (Exception $e) {
		// Log the exception and return a generic error
		log_message('error', $e->getMessage());

		//echo json_encode(['status' => 'error', 'message' => 'An unexpected error occurred.']);
		$output['status'] = 'error';
		$output['message'] = $e->getMessage();
		}

		echo json_encode($output);
		}



		// public function store() {
		// // Load the database library if not autoloaded


		// // Validate incoming POST data
		// $name = $this->input->post('name', TRUE); // The second parameter TRUE enables XSS filtering
		// $email = $this->input->post('email', TRUE);

		// // Check for missing fields
		// if (empty($name) || empty($email)) {
		// echo json_encode(['status' => 'error', 'message' => 'Name and Email are required.']);
		// return;
		// }

		// // Prepare data for insertion
		// $data = [
		// 'name' => $name,
		// 'email' => $email,
		// 'created_at' => date('Y-m-d H:i:s') // Optional: Add timestamp for better tracking
		// ];

		// $inserted = $this->student_model->store($data);

		// if ($inserted) {
		// echo json_encode(['status' => 'success', 'message' => 'User stored successfully!', 'data' => $data]);
		// } else {
		// echo json_encode(['status' => 'error', 'message' => 'Failed to store user. Please try again.']);
		// }
		// }



	public function update_user()
	{	
		$output = array(
		'status' => 'error',
		'message' => 'please fill correct validation.'
		);
		try 
		{
			$id = $this->input->post('id');
			$name = $this->input->post('modalUserName');
			$email = $this->input->post('modalUserEmail');
			$dob = $this->input->post('modalUserDob');
			$status = $this->input->post('modalUserStatus');
			$users_id = $this->input->post('modalUsersId');
			$profile_pic=$this->input->post('currentimage');

		
		$this->load->library('form_validation');

		$this->form_validation->set_rules('modalUserName', 'Name', 'required|min_length[5]');
		$this->form_validation->set_rules('modalUserEmail', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('modalUserDob', 'Date of Birth', 'required');
		$this->form_validation->set_rules('modalUserStatus', 'Status', 'required|in_list[active,inactive]');
	
		if ($this->form_validation->run() === True) {

			$uploads_dir = 'Images/';
		    $new_image = $_FILES['changeimage']['name'] ? $_FILES['changeimage']['name']:'';
			$old_image=$this->student_model->get_users_by_id($id);

		    if($new_image != '')
			{
				if (isset($old_image) && file_exists($uploads_dir . $old_image)) 
				{	
				
					if (unlink($uploads_dir . $old_image)) {
						echo "Image successfully deleted.";
						} else
						 {
						echo "Failed to delete the image.";
						}
				} 
				else 
				{
					echo "Image not found in the folder.";
				}	



					$imgnumber= rand(1,10);
					$img_unique=date("Y-m-d").$imgnumber.$new_image;
					
					if (is_uploaded_file($_FILES['changeimage']['tmp_name']))
					{       
						move_uploaded_file($_FILES['changeimage']['tmp_name'], $uploads_dir.$img_unique);	 
					}
				
			}
			
			$data = [
			'name' => $name,
			'email' => $email,
			'dob' => $dob,
			'status' => $status,
			'users_id' => $users_id,
			'profile_pic' => $img_unique ? $img_unique : $old_image
			];

		
		
			$updated = $this->student_model->update_user($id,$data);

			

			if ($updated==True) { 
				$output = array('status' => 'success', 'message' => 'User updated successfully!');
			} else {
			throw new Exception('Failed to update user.');
			}
			

	    }
		else
		{
			throw new Exception(validation_errors());
		}

	} catch (Exception $e) {
		// Log the exception and return a generic error
		// log_message('error', $e->getMessage());
		$output['status]'] = 'error';
		$output['message'] = $e->getMessage();

		}

		// echo json_encode($output);
		redirect('student');
		}


		

		public function delete_user()
		{

		try {
			$id = $this->input->post('id');

			$deleted = $this->student_model->delete_user($id);
			
		
			if ($deleted) {
				$output = array('status' => 'success', 'message' => 'User deleted successfully!');
			} else 
			{
				throw new Exception('Failed to delete user.');
			}
			} catch (Exception $e) {

			$output = $e->getMessage();

			}
		echo json_encode($output);
		redirect('student');
		
	
	}


		public function birthdaycount()
		{
		$users = $this->student_model->get_all_users(); 

		$result = [];
		$count = 1;

		foreach ($users as $user) {
		$dob = $user['dob'];
		$birthdayDate = new DateTime($dob);
		$today = new DateTime();

		$newDate = $birthdayDate->format('l jS - F Y');
		$birthdayDate->setDate($today->format('Y') + 1, $birthdayDate->format('m'), $birthdayDate->format('d'));
		$daysUntilBirthday = $today->diff($birthdayDate)->days;


		$result[] = [

			
		'sn' => $count++,
		'id'=>$user['id'],
		
		'name' => $user['name'],
		'email' => $user['email'],
		'dob' => $user['dob'],
		'Category' => $user['category'],
		'upcoming_day_count' => $user['upcoming_day_count'] ?? 0,
		'days_until_birthday' => $daysUntilBirthday,
		'newDate' => $newDate,
		'status' => $user['status'],
    	'profile_pic' => '<img src="'. base_url( 'Images/'.$user['profile_pic']) . '" 
        alt="' . $user['profile_pic'] . '" 
        style="width: 80px; height: 100px; border-radius: 20%; object-fit: cover;">',
		'action' => '<button class="btn btn-warning btn-sm editBtn" data-id="' . $user['id'] . '" data-name="' . $user['name'] . '" data-email="' . $user['email'] . '" data-dob="' . $user['dob'] . '" data-status="' . $user['status'] . '" data-category="' . $user['category'] .  '" data-profile_pic="' . $user['profile_pic'] . '" . data-users_id="' . $user['users_id'] . '">Edit</button> <button class="btn btn-danger btn-sm deleteUser" onclick="deleteUser(' . $user['id'] . ')">Delete</button>'

		
		];
		}


		
		$output = array(
		'draw' => $this->input->post('draw') ? intval($this->input->post('draw')) : 1,
		'recordsTotal' => count($result),
		'recordsFiltered' => count($result),
		'data' => $result
		);


		echo json_encode($output);


		}



		
		


		

}