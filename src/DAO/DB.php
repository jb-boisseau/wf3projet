<?php 
namespace WF3\DAO;

interface DB {
	
	public function findAll();
    
    public function find($id);
    
    public function delete($id);
    
    public function insert($data);
	
    public function update($id, $data);

}
