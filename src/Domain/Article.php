<?php
namespace WF3\Domain;

class Article{
    //déclaration des attributs
    private $id;
    private $title;
    private $content;
    private $date_publi;
    private $author;

    
    public function getId(){
        return $this->id;
    }
    
    public function getTitle(){
        return $this->title;
    }
    
    public function getContent(){
        return $this->content;
    }
    
    public function getDate_publi(){
        return $this->date_publi;
    }
    
    public function getAuthor(){
        return $this->author;
    }
    
        //setters
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

    public function setDate_publi($datePubli){
        if(!empty($datePubli) AND is_string($datePubli)){
            $this->date_publi = $datePubli; 
        }
    }

    public function setAuthor($authorId){        
            $this->author = $authorId;         
    }
    
}