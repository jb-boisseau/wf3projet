<?php
namespace WF3\DAO;

class ArticleDAO extends DAO{

	//je crée un attribut qui va contenir un objet de classe UserDAO (la lcasse qui nous permet de manipuler la table users)
	private $userDAO;
	//le setter associé
	public function setUserDAO(UserDAO $userDAO){
		$this->userDAO = $userDAO;
	}

	public function getLastArticles(){
		$result = $this->bdd->query('SELECT * FROM articles ORDER BY date_publi DESC LIMIT 0,5');
		return $result->fetchALL(\PDO::FETCH_ASSOC);
	}

	//retourne la liste des articles de l'utilisateur dont l'id est fourni
	public function getArticlesFromUser($idUser){
		$result = $this->bdd->prepare('SELECT * FROM articles WHERE author = :id');
		$result->bindValue(':id', $idUser, \PDO::PARAM_INT);
		$result->execute();
		return $result->fetchALL(\PDO::FETCH_ASSOC);
	}

	public function getArticlesWithAuthor(){
		$result = $this->bdd->query('SELECT articles.id AS idArticle, title, content, users.id AS idUser, username FROM articles INNER JOIN users ON articles.author = users.id');
		return $result->fetchALL(\PDO::FETCH_ASSOC);
	}
    
    public function findArticlesByTitle($title){
        $result = $this->bdd->prepare('SELECT articles.id AS idArticle, title, content, users.id AS idUser, username FROM articles INNER JOIN users ON articles.author = users.id WHERE title LIKE :title');
        $result->bindValue(':title', '%' . $title . '%');
        $result->execute();
        return $result->fetchALL(\PDO::FETCH_ASSOC);
    }
    
    public function deleteAllArticlesFromUser($idUser){
        $result = $this->bdd->prepare('DELETE FROM ' . $this->tableName . ' WHERE author = :iduser');
        $result->bindValue(':iduser', $idUser);
        if($result->execute()){
            return $result->rowCount();
        }
        return false;
    }
    
    public function getAllArticlesFromUsernameLike($name){
        $result = $this->bdd->prepare('SELECT articles.id, title, author, date_publi FROM ' . $this->tableName . ' INNER JOIN users ON articles.author = users.id WHERE users.username LIKE :name');
        $result->bindValue(':name', '%'.$name.'%');
        $result->execute();
        $rows =  $result->fetchALL(\PDO::FETCH_ASSOC);
        $articles = [];
        foreach($rows as $row){
            $article = $this->buildobject($row);
            $articles[$row['id']] = $article;
        }
        return $articles;
    }

    //je réécris ma méthode buildObject 
    public function buildObject($row){
    	//j'exécute le code de buildObject dans DAO
    	//qui me renvoie un objet $article de la classe Article
    	$article = parent::buildObject($row);
    	//getAuthor() renvoie l'id de l'auteur de l'article
    	$idAuteur = $article->getAuthor();
    	//on utilise l'attribut userDAo qui contient l'instance de la classe UserDAO 
    	//pour aller chercher dans la table users les infos de l'utilisateur correspondant
    	if(array_key_exists('author', $row) AND is_numeric($row['author'])){
        	$auteur = $this->userDAO->find($idAuteur);
        }
        //on remplace l'id de l'auteur par l'objet $auteur de la classe User qui contient les infos sur l'auteur
        $article->setAuthor($auteur);
        //on renvoie l'article
        return $article;
    }

}