<?php
namespace WF3\Domain;
//creation d'une class livredor
class Reservation
{

    public $id;
    public $name;
    public $email;
    public $nbTicket;
    public $dateAdd;

     //Déclaration des GETTERS :

   public function getId(){
       return $this->id;
   }
   
   public function getName(){
       return $this->name;
   }
   
   public function getEmail(){
       return $this->email;
   }
   
   public function getNbTicket(){
       return $this->nbTicket;
   }
    public function getDateAdd(){
       return $this->dateAdd;
   }

//Déclaration des SETTERS :

   public function setId($id){
       if(!empty($id) AND is_numeric($id)){
           $this->id = $id;
           return $this;
       }
       return false;
   }

   public function setName($name){
       if(!empty($name) AND is_string($name)){
           $this->name = $name;
       }
   }

   public function setNbTicket($nbTicket){
       if(!empty($nbTicket) AND is_string($nbTicket)){
           $this->nbTicket = $nbTicket;
       }
   }
    public function setDateAdd($dateAdd){
       if(!empty($dateAdd) AND is_string($dateAdd)){
           $this->dateAdd = $dateAdd;
       }
   }

   public function setEmail($email){
       if(!empty($email)){
           $this->email = $email;
       }
   }




     
}