<?php
namespace WF3\Domain;

class Spectacle{


    //déclaration des attributs
    private $id;
    private $title;
    private $content;
    private $dateVenue;
    private $nbTickets;
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
    
    public function getDateVenue(){
        return $this->dateVenue;
    }
    
    public function getNbTickets(){
        return $this->nbTickets;
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

    public function setDateVenue($date_venue){
        if(!empty($date_venue) AND is_string($date_venue)){
            $this->dateVenue = $date_venue; 
        }
    }

    public function setNbTickets($nbTickets){    
        if(!empty($nbTickets) AND is_numeric($nbTickets)) { 
            $this->nbTickets = $nbTickets;  
        }       
    }

    public function setPlace($place){    
        if(!empty($place) && is_string($place)){ 
            $this->place = $place;  
        }       
    }

    public function setType($type){    
        if(!empty($type)){ 
            $this->type = $type;  
        }       
    }
}