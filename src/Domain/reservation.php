<?php
namespace WF3\Domain;
//creation d'une class livredor
class Livredor
{

    public $id;
    public $name;
    public $email;
    public $nb_ticket;
    public $date_add;

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
   
   public function getNb_ticket(){
       return $this->nb_ticket;
   }
    public function getDate_add(){
       return $this->date_add;
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

   public function setNb_ticket($nb_ticket){
       if(!empty($nb_ticket) AND is_string($nb_ticket)){
           $this->nb_ticket = $nb_ticket;
       }
   }
    public function setDate_add($date_add){
       if(!empty($date_add) AND is_string($date_add)){
           $this->date_add = $date_add;
       }
   }

   public function setEmail($email){
       if(!empty($email)){
           $this->email = $email;
       }
   }




     
}