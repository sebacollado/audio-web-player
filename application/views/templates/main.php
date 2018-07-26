<?php 
    $this->load->view('templates/header', $title); 
    $this->load->view('templates/navbar');
?>
<?=$content?>
<?php 
    $this->load->view('templates/footer'); 
?>



