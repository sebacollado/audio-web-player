<?php
class Song_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    function songList($category){
        $this->db->select('rowid,song_name,song_cat');
        $this->db->from('songs');
        $this->db->where('song_cat',$category);
        $query = $this->db->get();
        return $query;
    }
    function songListAll(){
        $this->db->select('rowid,song_name,song_cat');
        $this->db->from('songs');
        $query = $this->db->get();
        return $query;
    }
    function songUpdate($fich){
        //Check if song exist
        $this->db->where('song_name',$fich);
        $res = $this->db->get('songs');
        
        if($res->num_rows() == 0){ //Song is not in database -> insert
            $data = array(
                'song_name' => $fich,
                'song_cat'  => 'default',
            );

            $this->db->insert('songs', $data);
            return true;
        }
    }
    function songClean(){
        $this->db->empty_table('songs');
        return true;
    }
    function songRemove($song){
        $this->db->where('song_name', $song);
        $this->db->delete('songs'); 
    }
    function createCategory($category){
        //Check if category exist
        $this->db->where('cat_name',$category);
        $res = $this->db->get('categories');
        
        if($res->num_rows() == 0){ //Category is not in database -> insert
            $data = array(
                'cat_name'  => $category,
            );

            $this->db->insert('categories', $data);
            return true;
        }
        return false;
    }
    function categoriesList(){
        $this->db->select('*');
        $this->db->from('categories');
        $query = $this->db->get();
        return $query;
    }
    function updateSongCategory($cat,$id){
        $data = array(
            'song_cat'  => $cat,
        );
        $this->db->where('rowid',$id);
        $this->db->update('songs', $data);
        return true;
    }
}
?>