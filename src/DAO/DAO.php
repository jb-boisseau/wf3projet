<?php 
namespace WF3\DAO;

class DAO implements DB {

	protected $bdd; 

	protected $tableName; // Le nom de la table SQL

    protected $objectClassName;

	
	public function __construct($db, $nomDeLaTable, $nomDeLaClasse)
	{
		$this->bdd = $db; // Affecte la valeur de la propriété $bdd => elle devient l'équivalent de $db

        //on injecte le nom de la table passé en paramètre lors de l'instanciation
        //dans l'attribut tableName
        $this->tableName = $nomDeLaTable;
        //pareil avec le nom de la classe que l'on va utiliser pour créer des objets
        $this->objectClassName = $nomDeLaClasse;

	}

    
    public function getTableName(){
        return $this->tableName;
    }
    
    public function getDb(){
        return $this->bdd;
    }
    
    public function buildObject($row){
        if($row AND is_array($row)){
            $class = $this->objectClassName;
            $object = new $class();
            //on fait une boucle sur le tableau $row
            foreach($row as $key=>$value){
                //à chaque tour de boucle
                //on crée le nom de la méthode grâce à la clé du tableau : setId ou setTitle ...
                $method = 'set'.ucfirst($key);
                //echo $method;
                //si cette méthode existe bien dans l'objet
                if(method_exists($object, $method)){
                    //on l'exécute en lui passant en paramètre la valeur associée
                    $object->$method($value);
                }
            }
            return $object;
        }
        return false;
    }


	/**
	 * Lecture de la table
	 */
	public function findAll(){ // Read

		// On effectue la requete SQL
        // $this->tableName contient le nom de la table
		$result = $this->bdd->prepare('SELECT * FROM ' . $this->tableName);
		$result->execute();
		$rows = $result->fetchAll(\PDO::FETCH_ASSOC);
        $objectsArray =[];
        foreach($rows as $row){
            $objectsArray[$row['id']] = $this->buildObject($row);
        }
		return $objectsArray;
	}
    

    /**
     * Retourne une ligne en fonction d'un identifiant
     * @param int $id L'id de la ligne concernée
     * @return array si tout est ok, false sinon
     */
    public function find($id){
        // On vérifie que l'id soit bien un entier
        if(!empty($id) && is_numeric($id)){
            $get = $this->bdd->prepare('SELECT * FROM '.$this->tableName.' WHERE id = :id');
            $get->bindValue(':id', $id, \PDO::PARAM_INT);
            $get->execute();
            $row = $get->fetch(\PDO::FETCH_ASSOC);
            return $this->buildObject($row);
        }

        return false;
    }
    
    
    /**
	 * Supprime une ligne en fonction d'un identifiant
	 * @param int $id L'id de la ligne concernée
	 * @return bool true si tout est ok, false sinon
	 */
    public function delete($id) // Delete
    {
        // On vérifie que l'id soit bien un entier
        if(!empty($id) && is_numeric($id)){
            $delete = $this->bdd->prepare('DELETE FROM '.$this->tableName.' WHERE id = :idASupprimer');
            $delete->bindValue(':idASupprimer', $id, \PDO::PARAM_INT);

            if($delete->execute()){
                return true;
            }
        }

        return false;

    }
    
    /**
	 * Ajoute une entrée dans la table
	 * @param array $data un tableau de donnée avec les noms des champs en clé
     * et les valeurs associées
	 * @return true si ok, false sinon
	 */
    public function insert($data) // Create
    {
        if(is_object($data)){

            //on va transformer l'objet en tableau php        
            $dataArray = [];

            //on crée un tableau qui contient les noms des méthodes de notre objet
            $methods = get_class_methods($data);

            //je fais une boucle sur mes méthodes
            foreach($methods as $method){
                //si ma méthode est un setter (commence par set)
                //et que le getter correspondant existe
                if(preg_match('#^set#', $method) AND method_exists($data, str_replace('set', 'get', $method))){
                    //je récupère le getter
                    $getter = str_replace('set', 'get', $method);
                    //je rempli mon tableau avec en clé le nom de l'attribut (donc on enlève set et on met en minuscules)
                    //en valuer , le résultat de mon appel au getter
                    $dataArray[strtolower(str_replace('set', '', $method))] = $data->$getter();
                }
            }

            $data = $dataArray;

        }


        //créationde la requête sql insert
        $sql = 'INSERT INTO ' . $this->tableName . ' (';

        foreach($data as $key=>$value){
            //on rajoute à la suite de $sql avec .=
            $sql .= $key . ', ';
        }
        //on supprime les deux derniers caractères (on veut supprimer la virgule)
        $sql = substr($sql, 0, -2);

        $sql .= ') VALUES (';

        //on écrit les marqueurs
        foreach($data as $key=>$value){
            //on rajoute à la suite de $sql avec .=
            $sql .= ':' . $key . ', ';
        }
        //on supprime les deux derniers caractères (on veut supprimer la virgule)
        $sql = substr($sql, 0, -2);
        //on ferme la parenthèse
        $sql .= ')';    
        
        $add = $this->bdd->prepare($sql);

        foreach($data as $key=>$value){
            //on va créer les lignes bindValue correspondantes
            $add->bindValue(':' . $key, strip_tags($value));
        }

        if($add->execute()){
            return true;
        }
        else{
            return false; 
        }
        
    }
	
    /**
	 * Met a jour un article en fonction de son identifiant
	 * @param array $data Un tableau associatif de valeurs à insérer
	 * @param int $id L'identifiant de la ligne à mettre à jour
	 * @return true si ok, false sinon
	 */
    public function update($id, $data)
    {//update
        if(is_object($data)){

            //on va transformer l'objet en tableau php        
            $dataArray = [];

            //on crée un tableau qui contient les noms des méthodes de notre objet
            $methods = get_class_methods($data);

            //je fais une boucle sur mes méthodes
            foreach($methods as $method){
                //si ma méthode est un setter (commence par set)
                //et que le getter correspondant existe
                if(preg_match('#^set#', $method) AND method_exists($data, str_replace('set', 'get', $method))){
                    //je récupère le getter
                    $getter = str_replace('set', 'get', $method);
                    //je rempli mon tableau avec en clé le nom de l'attribut (donc on enlève set et on met en minuscules)
                    //en valuer , le résultat de mon appel au getter
                    $dataArray[strtolower(str_replace('set', '', $method))] = $data->$getter();
                }
            }

            $data = $dataArray;

        }
        // UPDATE tableName SET colonne = new_value, colonne_2 = new_value WHERE id = mon_id

        $sql = 'UPDATE ' . $this->tableName . ' SET ';

        foreach($data as $key => $value){
            //colonne = :colonne, colonne2 = :colonne2, 
            $sql .= "$key = :$key, ";
        }

        $sql = substr($sql, 0, -2); // Permet de retirer le dernier espace et la dernière virgule (supprime les deux derniers caractères)
        $sql .= ' WHERE id = :id'; // Rajoute la clause WHERE

        $update = $this->bdd->prepare($sql); // On prépare la requete SQL

        foreach($data as $key => $value){ // Permet d'associer les marqueurs SQL (:firstname par exemple) à leurs valeurs
            $update->bindValue(':'.$key, strip_tags($value));
        }
        // Rempli le marqueur :id en lui donnant pour valeur le paramètre de la fonction/méthode
        $update->bindValue(':id', $id, \PDO::PARAM_INT);

        if($update->execute()){	 // On exécute la requete
            return true;
        }    

        return false;

    }

}
