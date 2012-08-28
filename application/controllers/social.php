<?php

/*
The controller for loading entries.
Each function call loads entries from RSS feeds or the user's clips.
*/

class Social extends CI_controller{
	
	function get_friendsList($username){
		$this->load->model('social_model');
		$data['friendsList'] = $this->social_model->get_friendsList($username);
		$this->load->view('social/friends_list', $data);
	}
	
	function search_member(){
		$this->load->model('social_model');
		$username = $this->input->post('username');
		$data['result'] = $this->social_model->search_member($username);
		$this->load->view('social/search_list', $data);
	}
	
	function add_friend($friend_name){
		$this->load->model('social_model');
		$username = $this->session->userdata('username');
		$this->social_model->add_friend($username, $friend_name);
		redirect('site/clips');
	}
	
	function shareEntry(){
		$sender = $this->session->userdata('username');
		$recipient = $this->input->post('recipient');
		$permalink = $this->input->post('permalink');
		$title = $this->input->post('title');
		$content = $this->input->post('content');
		$source = $this->input->post('source');
		$date = $this->input->post('date');
		$this->load->model('query_model');
		$result = $this->query_model->share_clip($sender, $recipient, $permalink, $title, $content, $source, $date, 'received');
		echo $result;
	}
	
	function editProfilePic(){
		$config['upload_path'] = './user/profilePic';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';
		$config['file_name'] = $this->session->userdata('username');
		$config['overwrite'] = TRUE;
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors());
			echo $error;
		}
		else
		{	
			$upload_data = $this->upload->data();
			$ext = $upload_data['file_ext'];
			$this->load->model('social_model');
			$this->social_model->update_profileInfo($this->session->userdata('username'), $ext);
			header('Location: '.$_SERVER['HTTP_REFERER']);
		}
	}
}