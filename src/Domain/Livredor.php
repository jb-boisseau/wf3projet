<?php
namespace WF3\Domain;
//creation d'une class livredor
class Livredor
{

	public $id;
	public $author;
	public $email;
	public $content;

	 //Déclaration des GETTERS :

    public function getId(){
        return $this->id;
    }
    
    public function getAuthor(){
        return $this->author;
    }
    
    public function getEmail(){
        return $this->email;
    }
    
    public function getContent(){
        return $this->content;
    }

 //Déclaration des SETTERS :

    public function setId($id){
        if(!empty($id) AND is_numeric($id)){
            $this->id = $id;
            return $this;
        }
        return false;
    }

    public function setAuthor($author){
        if(!empty($author) AND is_string($author)){
            $this->author = $author; 
        }
    }

    public function setContent($content){
        if(!empty($content) AND is_string($content)){
            $this->content = $content; 
        }
    }

    public function setEmail($email){
        if(!empty($email)){
            $this->email = $email; 
        }

    }



	 
}