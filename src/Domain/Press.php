<?php
namespace WF3\Domain;

class Press{


    //déclaration des attributs
    private $id;
    private $title;
    private $content;
    private $image;
    private $link;




    
    //Déclaration des GETTERS :

    public function getId(){
        return $this->id;
    }
    
    public function getTitle(){
        return $this->title;
    }
    
    public function getContent(){
        return $this->content;
    }
    
  
    public function getImage(){
        return $this->image;
    }
    
    public function getLink(){
        return $this->link;
    }





    
    //Déclaration des SETTERS :
    public function setId($id){
        if(!empty($id) AND is_numeric($id)){
            $this->id = $id;
            return $this;
        }
        return false;
    }

    public function setTitle($title){
        if(!empty($title) AND is_string($title)){
            $this->title = $title; 
        }
    }

    public function setContent($content){
        if(!empty($content) AND is_string($content)){
            $this->content = $content; 
        }
    }

    public function setImage($image){    
        if(!empty($image)){ 
            $this->image = $image;  
        }       
    }
    
    public function setLink($link){    
        if(!empty($link)){ 
            $this->link = $link;  
        }       
    }
}
