<?php

	class Loader_Model extends CI_Model{
		public function __construct(){
			$this->load->database();
		}
		function get_firstname($username){
			$query = $this->db->get_where('membership', array('username' => $username));
			return $query->row()->first_name;
		}
		function get_wholename($username){
			$query = $this->db->get_where('membership', array('username' => $username));
			return $query->row()->whole_name;
		}
		function get_profileExt($username){
			$query = $this->db->get_where('membership', array('username' => $username));
			return $query->row()->profile_ext;
		}
		function get_feeds($username){
			//order_by pos_id and then get_where
			$this->db->order_by("pos_id", "asc"); 
			$query = $this->db->get_where('feed', array('username' => $username));
			return $query->result();
		}
		function select_feed($id){
			//order_by pos_id and then get_where
			$query = $this->db->get_where('feed', array('id' => $id));
			return $query->row();
		}
		function get_labels($username){
			$result = array(array(), array());
			$this->db->order_by("pos_id", "asc");
			$label = $this->db->get_where('label_list', array('username' => $username))->result();
			foreach($label as $item){
				$labelcontent = $this->labelContent($item->label);
				array_push($result[0], $item);
				array_push($result[1], $labelcontent);
			}
			return $result;
		}
		function labelContent($labelName){
			$this->db->order_by("pos_id", "asc");
			$query = $this->db->get_where('label', array('username' => $this->session->userdata('username'), 'label_name' => $labelName));
			return $query->result();
		}
		function select_label($username, $id){
			$query = $this->db->get_where('label_list', array('username' => $username, 'id' => $id));
			return $query->row();
		}
		function get_clips($username){
			/*
			$query = $this->db->get_where('clip', array('username' => $username, 'type' => 'public'));
			return $query->result();
			*/
			$result = array();
			$target = $this->db->get_where('clips', array('username' => $username, 'type' => 'public'))->result();
			foreach ($target as $item){
				$article = $this->db->get_where('articles', array('id' => $item->ref_id))->row();
				$article->type = $item->type;
				array_push($result, $article);
			}
			return $result;
		}
		function get_myClips(){
			/*
			$query = $this->db->get_where('clip', array('username' => $this->session->userdata('username'), 'type !=' => 'received'));
			return $query->result();
			*/
			$result = array();
			$target = $this->db->get_where('clips', array('username' => $this->session->userdata('username'), 'type !=' => 'received'))->result();
			foreach($target as $item){
				$article = $this->db->get_where('articles', array('id' => $item->ref_id))->row();
				$article->id = $item->id;
				$article->type = $item->type;
				$article->ref_id = $item->ref_id;
				array_push($result, $article);
			}
			return $result;
		}
		function get_receivedClips(){
			/*
			$query = $this->db->get_where('clip', array('username' => $this->session->userdata('username'), 'type' => 'received'));
			return $query->result();
			*/
			$result = array();
			$target = $this->db->get_where('clips', array('username' => $this->session->userdata('username'), 'type' => 'received'))->result();
			foreach($target as $item){
				$article = $this->db->get_where('articles', array('id' => $item->ref_id))->row();
				$article->type = $item->type;
				array_push($result, $article);
			}
			return $result;
		}
		function checkAid($permalink){
			if ($this->db->get_where('articles', array('aid' => $permalink))->num_rows() == 0){
				return false;
			} else{
				return true;
			}
		}
		function checkMark($aid){
			if ($this->db->get_where('mark', array('username' => $this->session->userdata('username'), 'aid' => $aid))->num_rows() > 0){
				return true;
			} else{
				return false;
			}
		}
		function loadArticle($aid){
			return $this->db->get_where('articles', array('aid' => $aid))->row();
		}
		function get_feeds_forLabel($username, $label){
			$query = $this->db->get_where('label', array('username' => $username, 'label_name' => $label));
			$feeds = array();
			foreach ($query->result() as $link){
				array_push($feeds, $link->feed_url);
			}
			return $feeds;
		}
		function get_feeds_all($username){
			$query = $this->db->get_where('feed', array('username' => $username));
			$feeds = array();
			foreach($query->result() as $link){
				if ($link->type == 'site'){
					array_push($feeds, $link->url);
				}
				else if ($link->type == 'keyword'){
					array_push($feeds, 'feed://news.google.com/news?pz=1&cf=all&ned=us&hl=en&q=topic:'.$link->title.'&output=rss');
				}
			}
			return $feeds;
		}
		function get_clipCount($aid){
			return $this->db->get_where('articles', array('aid' => $aid))->row();
		}
		function get_feedCount($username){
			$target = $this->db->get_where('membership', array('username' => $username))->row();
			return $target->feed_count;
		}
		function get_labelCount($username){
			$target = $this->db->get_where('membership', array('username' => $username))->row();
			return $target->label_count;
		}
		function get_labelContentCount($id){
			$target = $this->db->get_where('label_list', array('id' => $id))->row();
			return $target->count;
		}
	}