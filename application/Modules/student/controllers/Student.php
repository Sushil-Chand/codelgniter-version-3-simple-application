<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Student extends MX_Controller
{



	public function __construct()
	{
		parent::__construct();
		$this->load->model('student_model');
	}

	public function index()
	{
		$this->db->select('*');
		$this->db->from('usertypes');
		$query = $this->db->get();

		$Data = $query->result_array();



		$this->load->view('student', $Data);

	}


	public function test()
	{


		$this->load->view('test');
	}


	public function getData()
	{
		$users = $this->birthdaycount();

		echo json_encode($users);
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



			// Run validation
			if ($this->form_validation->run() === TRUE) {
				// Retrieve sanitized input
				$name = $this->input->post('name');
				$email = $this->input->post('email');
				$dob = $this->input->post('dob');
				$status = $this->input->post('status');
				$users_id = $this->input->post('users_id');

				if (empty($users_id)) {
					throw new Exception('please Choose the user Category');
				}

				// Prepare data for insertion
				$data = [
					'name' => $name,
					'email' => $email,
					'dob' => $dob,
					'status' => $status,
					'users_id' => $users_id

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


		$output = array(
			'status' => 'error',
			'message' => 'please fill correct validation.'
		);
		try {
			$id = $this->input->post('id');
			$name = $this->input->post('name');
			$email = $this->input->post('email');
			$dob = $this->input->post('dob');
			$status = $this->input->post('status');
			$users_id = $this->input->post('users_id');

			// Load form validation library
			$this->load->library('form_validation');

			// Set validation rules

			$this->form_validation->set_rules('name', 'Name', 'required|min_length[5]');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('dob', 'Date of Birth', 'required');
			$this->form_validation->set_rules('status', 'Status', 'required|in_list[active,inactive]');



			// Run validation
			if ($this->form_validation->run() === TRUE) {

				// Prepare data for update
				$data = [

					'name' => $name,
					'email' => $email,
					'dob' => $dob,
					'status' => $status,
					'users_id' => $users_id

				];

				// Update the database
				$updated = $this->student_model->update_user($id, $data);

				if ($updated) { // Respond back with success 
					$output = array('status' => 'success', 'message' => 'User updated successfully!');
				} else {
					throw new Exception('Failed to update user.');
				}
			} else {
				throw new Exception(validation_errors());
			}

		} catch (Exception $e) {
			// Log the exception and return a generic error	
			log_message('error', $e->getMessage());
			$output['status]'] = 'error';
			$output['message'] = $e->getMessage();

		}

		echo json_encode($output);
	}
	public function delete_user()
	{

		try {
			$id = $this->input->post('id');

			// Delete from the database

			$deleted = $this->student_model->delete_user($id);

			// Respond back with success
			if ($deleted) {
				$output = array('status' => 'success', 'message' => 'User deleted successfully!');
			} else {
				throw new Exception('Failed to delete user.');
			}
		} catch (Exception $e) {

			$output = $e->getMessage();

		}

		echo json_encode($output);

	}


	public function birthdaycount()
	{

		$users = $this->student_model->get_all_users();

		$result = [];

		// Loop through each student to calculate days until the next birthday
		foreach ($users as $student) {
			$dob = $student['dob']; //


			$birthdayDate = new DateTime($dob);

			$today = new DateTime();




			$originalDate = $birthdayDate->format('y-m-d');


			$newDate = date("l jS- F Y", strtotime($originalDate));





			$birthdayDate->setDate($today->format('Y') + 1, $birthdayDate->format('m'), $birthdayDate->format('d'));



			$daysUntilBirthday = $today->diff($birthdayDate)->days;
			$daysUntilBirthday = $daysUntilBirthday + 1;



			$result[] = [
				'id' => $student['id'],
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