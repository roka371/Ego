
<?php $this->load->view('site/header'); ?>

<?php $this->load->view($main_content); ?>

<?php 
if ($main_content == 'feed'){
	$this->load->view('site/footer_discovery');
}
else{
	$this->load->view('site/footer');
}
?>

