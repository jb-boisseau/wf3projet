<?php
namespace WF3\DAO;

class SpectacleDAO extends DAO{

    public function LastNineArticles(){
        // On effectue la requete SQL
        // $this->tableName contient le nom de la table
		$result = $this->bdd->prepare('SELECT * FROM ' . $this->tableName .' ORDER by dateVenue DESC  LIMIT 0,6');
		$result->execute();
		$rows = $result->fetchAll(\PDO::FETCH_ASSOC);
        $objectsArray =[];
        foreach($rows as $row){
            $objectsArray[$row['id']] = $this->buildObject($row);
        }
		return $objectsArray;
    }   
    
    
    public function LastShow(){
    $result = $this->bdd->prepare('SELECT * FROM ' . $this->tableName .' ORDER by dateVenue DESC');
		$result->execute();
		$rows = $result->fetchAll(\PDO::FETCH_ASSOC);
        $objectsArray =[];
        foreach($rows as $row){
            $objectsArray[$row['id']] = $this->buildObject($row);
        }
		return $objectsArray;
    }   

    
    public function ArchiveShow(){
        $result = $this->bdd->prepare('SELECT * FROM ' . $this->tableName. ' ORDER by dateVenue DESC LIMIT 5,1000');
        $result->execute();
		$rows = $result->fetchAll(\PDO::FETCH_ASSOC);
        $objectsArray =[];
        foreach($rows as $row){
            $objectsArray[$row['id']] = $this->buildObject($row);
        }
		return $objectsArray;
    }
}