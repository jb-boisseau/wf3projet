<?php
namespace WF3\Domain;

class Spectacle{
    //déclaration des attributs
    private $id;
    private $title;
    private $content;
    private $date_venue;
    private $nb_tickets;
    private $place;
    private $type;




    
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
    
    public function getDate_venue(){
        return $this->date_venue;
    }
    
    public function getNb_tickets(){
        return $this->nb_tickets;
    }

    public function getPlace(){
        return $this->place;
    }

    public function getType(){
        return $this->type;
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

    public function setDate_venue($date_venue){
        if(!empty($date_venue) AND is_string($date_venue)){
            $this->date_venue = $date_venue; 
        }
    }

    public function setNb_tikets($nb_tickets){    
        if(!empty($nb_tickets) AND is_numeric($nb_tickets)) { 
            $this->nb_tickets = $nb_tickets;  
        }       
    }

    public function setPlace($place){    
        if(!empty($place) && is_string($place)){ 
            $this->place = $place;  
        }       
    }

    public function setType($type){    
        if(!empty($type) && $type == stage OR $type == spectacle){ 
            $this->type = $type;  
        }       
    }
}