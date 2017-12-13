<?php
namespace WF3\DAO;

class LivredorDAO extends DAO{

	public function findLast10(){
		$result = $this->bdd->prepare('SELECT * FROM ' . $this->tableName .' ORDER BY id DESC LIMIT 0,10');
		$result->execute();
		$rows = $result->fetchAll(\PDO::FETCH_ASSOC);
        $objectsArray =[];
        foreach($rows as $row){
            $objectsArray[$row['id']] = $this->buildObject($row);
        }
		return $objectsArray;
	}

}