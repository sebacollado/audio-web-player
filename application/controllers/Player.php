<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Player extends CI_Controller {

	public function index()
	{
            $this->load->model('Song_model');
            $data['categoriesList'] = $this->Song_model->categoriesList();
            
            $template_data['title'] = "Player Project";
            $template_data['content'] = $this->load->view('player/index', $data, true);
            $this->load->view('templates/main', $template_data);
	}
        
        public function categories()
	{
            //GET PARAMETER FROM URL
            $data['category'] = $this->uri->segment(3);
            
            //NO CATEGORY SELECTED
            if($data['category'] == NULL){
                $template_data['title'] = "Categories";
                $template_data['content'] = $this->load->view('player/categories', $data, true);
                $this->load->view('templates/main', $template_data);
            }else{ //CATEGORY SELECTED
                $template_data['title'] = ucfirst($data['category']);
                $template_data['content'] = $this->load->view('player/songs', $data, true);
                $this->load->view('templates/main', $template_data);
            }            
	}
        
        public function song_update(){
            $this->load->model('Song_model'); //Load data model
            
            $updated='';
            
            
            /*
            * FILE EXIST BUT IS NOT INTO DATABASE
            */
            //$ruta = getcwd()."C://xampp/htdocs/mwp/music/";
            $ruta = getcwd()."/music/";
            $id = 0;
            //comprueba directorio valido
            if (is_dir($ruta)){
                //abre el directorio
                if($directorio = opendir($ruta)){
                    //cada fichero del directorio
                    while (($fich = readdir($directorio)) !== false) {
                        //correccion encoding
                        $fich = iconv ( "iso-8859-1" , "UTF-8", $fich );
                        //extension valida
                        $arr_exts=array(".mp3",".ogg","webm");
                        $ext = substr($fich,-4);
                        //si el fichero es distinto de "." y ".."
                        if(!is_dir($ruta . $fich) && $fich!="." && $fich!=".." && in_array($ext,$arr_exts)){
                            if($this->Song_model->songUpdate($fich)) $updated='Songs updated';
                        }
                    }
                    
                    closedir($directorio);
                    if ($updated !== '') echo $updated;
                }
            }else{
                echo "Invalid directory";
            }
            
            
            
            /*
            * DATABASE SONG EXIST, BUT NOT IN DIRECTORY
            */
            $songList = $this->Song_model->songListAll();
            foreach($songList->result() as $song){

                $exist = false;
                
                if (is_dir($ruta)){
                    if($directorio = opendir($ruta)){
                        while (($fich = readdir($directorio)) !== false) {
                            $fich = iconv ( "iso-8859-1" , "UTF-8", $fich );
                            $arr_exts=array(".mp3",".ogg","webm");
                            $ext = substr($fich,-4);
                            if(!is_dir($ruta . $fich) && $fich!="." && $fich!=".." && in_array($ext,$arr_exts)){
                                if($fich == $song->song_name){
                                    $exist = true;
                                }
                            }
                        }
                        closedir($directorio);
                    }
                }else{
                    echo "Invalid directory";
                }
                
                //if song in database is not in directory, remove it from database
                if($exist == false){
                    $this->Song_model->songRemove($song->song_name);
                }
            }
        }
        
        public function song_list(){
            $this->load->model('Song_model'); //Load data model
            
            $category = $_POST['category'];
            $category = substr($category, 4);
            
            $id=0;
            ?>
            <div class="row">
                <h1><?php if(isset($category)) echo ucfirst($category)?> <button id="categories-btn" class="btn btn-primary btn-outline">Go to categories!</button></h1>
                <hr>
            </div>
            <div class="row">
                <div class="col-xs-11">
                    <ul id="musiclist">
                    <?php
                    $songs_cat = $this->Song_model->songList($category);

                    foreach ($songs_cat->result() as $song)
                    {
                        echo "<li id='".++$id."' class='song-list'>"."<i class='fa fa-play-circle-o'></i> ".$song->song_name."</li>";
                    }
                    ?>
                    </ul>
                </div>
            </div>
            <?php
        }
        
        public function song_clean(){
            $this->load->model('Song_model'); //Load data model
            $this->Song_model->songClean();
            echo "Database clean";
        }
        
        public function song_categories(){
            $this->load->model('Song_model');
            
            if($this->input->post()){
                
                $songs_nocat = $this->Song_model->songList('default');

                foreach ($songs_nocat->result() as $song){
                    if($this->input->post($song->rowid) !== "" ){
                        $this->Song_model->updateSongCategory($this->input->post($song->rowid),$song->rowid);
                    }
                }
                $this->session->set_flashdata('success','Categories assigned');
            }
                        
            $data['songs_nocat'] = $this->Song_model->songList('default');
            $data['categoriesList'] = $this->Song_model->categoriesList();
            
            $template_data['title'] = "Categories";
            $template_data['content'] = $this->load->view('player/categories_select', $data, true);
            $this->load->view('templates/main', $template_data);
        }
        
        public function new_category(){
            $this->load->model('Song_model');
            
            if($this->input->post() && $this->input->post('category') != ''){
                //UPLOAD ICON
                $target_dir = getcwd()."\\assets\\images\\categories\\icons\\";
                $target_file = $target_dir . basename($_FILES["icon"]["name"]);
                $target_file_final = $target_dir . $this->input->post('category') . ".png";
                $uploadOk = 1;
                $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
                // Check if image file is a actual image or fake image
                $check = getimagesize($_FILES["icon"]["tmp_name"]);
                list($ancho, $alto, $tipo, $atributos) = getimagesize($_FILES["icon"]["tmp_name"]);
                if($check !== false) {
                    $uploadOk = 1;
                } else {
                    $this->session->set_flashdata('error','Sorry, File is not an image');
                    $uploadOk = 0;
                    redirect("player/new_category");
                }
                // Check dimensions 64x64 only
                if ($ancho !== 64 || $alto !== 64) {
                    $this->session->set_flashdata('error','Image must be 64x64');
                    $uploadOk = 0;
                    redirect("player/new_category");
                }
                // Check if file already exists
                if (file_exists($target_file_final)) {
                    $this->session->set_flashdata('error','Sorry, icon already exists');
                    $uploadOk = 0;
                    redirect("player/new_category");
                }
                // Check file size
                if ($_FILES["icon"]["size"] > 500000) {
                    $this->session->set_flashdata('error','Sorry, your file is too large');
                    $uploadOk = 0;
                    redirect("player/new_category");
                }
                // Allow certain file formats
                if($imageFileType != "png") {
                    $this->session->set_flashdata('error','Sorry, only PNG icons allowed');
                    $uploadOk = 0;
                    redirect("player/new_category");
                }
                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    //$this->session->set_flashdata('error','Sorry, there was an error uploading your file');
                // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["icon"]["tmp_name"], $target_file_final)) {
                        $this->session->set_flashdata('success','Icon uploaded successful');
                        if($this->Song_model->createCategory($this->input->post('category'))){
                            $this->session->set_flashdata('success','New category');
                            redirect("player/new_category");
                        }
                    } else {
                        $this->session->set_flashdata('error','Sorry, there was an error uploading your file');
                        redirect("player/new_category");
                    }
                }
            }
            
            $template_data['title'] = "New category";
            $template_data['content'] = $this->load->view('player/new_category', '', true);
            $this->load->view('templates/main', $template_data);
        }
        
}
