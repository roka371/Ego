<?php

/*
The controller for loading entries.
Each function call loads entries from RSS feeds or the user's clips.
*/

class Loader extends CI_controller{
	
	function feed($feed_type, $id, $init){
		require_once('inc/simplepie.inc');
		$this->load->model('loader_model');
		if ($feed_type == 'label'){
			if ($id == 'all_feeds'){
				$url = $this->loader_model->get_feeds_all($this->session->userdata('username'));
			} else{
				$label = $this->loader_model->select_label($this->session->userdata('username'), $id);
				$url = $this->loader_model->get_feeds_forLabel($this->session->userdata('username'), $label->label);
			}
		} else{
			$feed = $this->loader_model->select_feed($id);
			if ($feed->type == 'site'){
				$url = $feed->url;
			}else if ($feed->type == 'keyword'){
				$url = 'feed://news.google.com/news/feeds?gl=us&pz=1&cf=all&ned=us&hl=en&q='.$feed->url.'&output=rss';
			}
		}
		$feed = new SimplePie($url);
		$feed->enable_cache(true);
		$feed->set_cache_location('cache');
		$feed->set_cache_duration(600);	//default: 10 minutes
		$feed->set_item_limit(11);
		$feed->init();
		$feed->handle_content_type();
		if ($init == "quantity"){
			echo $feed->get_item_quantity();
		}else if ($init == "discovery"){
			if ($feed_type == 'label'){
				$data['entries'] = $feed->get_items(0, 20);
			} else{
				$data['entries'] = $feed->get_items();
			}
			$this->load->view('loader/discovery_viewer', $data);
		} else{
			$data['entries'] = $feed->get_items($init, $init+10);
			$this->load->view('loader/feed_viewer', $data);
		}
	}
	
	function clips($init, $username){
		$this->load->model('loader_model');
		if ($username == $this->session->userdata('username')){
			$clip_array = $this->loader_model->get_myClips();
			$data['clip_list'] = array_reverse($clip_array);
		} else if ($username == 'received'){
			$data['clip_list'] = $this->loader_model->get_receivedClips();
		} else{
			$clip_array = $this->loader_model->get_clips($username);
			$data['clip_list'] = array_reverse($clip_array);
		}
		$data['init'] = $init;
		$this->load->view('loader/clip_viewer', $data);
	}
	
	function labelContent($id){
		$this->load->model('loader_model');
		$result = $this->loader_model->labelContent($id);
		foreach ($result as $item){
			echo '<li class="rightListInner">'.$item->feed_title.'</li>';
		}
	}
	function loadLabel($username){
		$this->load->model('loader_model');
		$label_list = $this->loader_model->get_label2($username);
		
		for ($i = 0; $i<sizeof($label_list[0]); $i++){
    		$label_link = $label_list[0][$i];
			echo '<li class="rightList">';		
			echo '<div class="labelListToggle" data-id="'.$label_link->id.'"><img src="/icons/right_toolbox/arrow_right.png" /></div>';
			echo anchor('/site/label/'.$label_link->id, $label_link->label, array('class' => 'labelListLinks', 'title' => $label_link->label));
			echo '<div class="rightListDelete" data-type="label" data-id="'.$label_link->id.'"><img src="/icons/right_toolbox/delete.png" /></div>';
			echo '<div class="labelContent">';
			foreach($label_list[1][$i] as $item){
				echo $item->feed_title;
			}
		}
		echo'</div>';
		echo '</li>';
	}
	function get_feedCount($username){
		$this->load->model('loader_model');
		echo $this->loader_model->get_feedCount($username);
	}
	function get_clipCount($aid){
		$this->load->model('loader_model');
		echo $this->loader_model->get_clipCount($aid);
	}
	function get_shareCount($aid){
		$this->load->model('loader_model');
		echo $this->loader_model->get_shareCount($aid);
	}
	function checkMark($aid){
		$this->load->model('loader_model');
		echo $this->loader_model->checkMark($aid);
	}
}