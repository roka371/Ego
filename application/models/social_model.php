<?php

class Social_model extends CI_Model{
	public function __construct(){
			$this->load->database();
	}
	function get_friendsList($username){
		$query = $this->db->get_where('friendship', array('username' => $username));
		
		$ID_List = $query->result();
		$friendsList = array();
		foreach ($ID_List as $friend){
			array_push($friendsList, $this->db->get_where('membership', array('username' => $friend->friend_name))->row());
		}
		return $friendsList;
	}
	function search_member($username){
		$query = $this->db->get_where('membership', array('username' => $username));
		return $query->result();
	}
	function add_friend($username, $friend_name){
		$friend = array(
				'username' => $username,
				'friend_name' => $friend_name,
				'friend_since' => date("Y-m-d")
		);
		$this->db->insert('friendship', $friend);
	}
	function update_profileInfo($username, $ext){
		$profileInfo = array('profile_ext' => $ext);
		$this->db->where('username', $username);
		$this->db->update('membership', $profileInfo);
	}
}


?>