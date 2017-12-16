<?php
namespace WF3\Domain;

class Sale
{

	public $amount;
	public $buyerid;
	public $paymentid;
	public $payerid;
	public $spectacleid;
	public $email;
	public $phone;
	public $status;
	public $adress;
	public $createtime;


	 //Déclaration des GETTERS :

    public function getAmount(){
        return $this->amount;
    }
    
    public function getBuyerid(){
        return $this->buyerid;
    }
    
    public function getPaymentid(){
        return $this->paymentid;
    }
    
    public function getPayerid(){
        return $this->payerid;
    }
    
    public function getSpectacleid(){
        return $this->spectacleid;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getPhone(){
        return $this->phone;
    }

    public function getStatus(){
        return $this->status;
    }

    public function getAdress(){
        return $this->adress;
    }

    public function getCreatetime(){
        return $this->createtime;
    }

 //Déclaration des SETTERS :

    public function setAmount($amount){
        if(!empty($amount)){
            $this->amount = $amount;
        }
    }    
    
    public function setBuyerid($buyerid){
        if(!empty($buyerid)){
            $this->buyerid = $buyerid; 
        }
    }

    public function setPaymentid($paymentid){
        if(!empty($paymentid)){
            $this->paymentid = $paymentid; 
        }
    }

    public function setPayerid($payerid){
        if(!empty($payerid)){
            $this->payerid = $payerid; 
        }
    }

    public function setSpectacleid($productid){
        if(!empty($productid)){
            $this->spectacleid = $productid; 
        }
    }

    public function setEmail($email){
        if(!empty($email)){
            $this->email = $email; 
        }
    }

    public function setPostalCode($postalCode){
        if(!empty($postalCode)){
            $this->postalCode = $postalCode; 
        }
    }

    public function setPhone($phone){
        if(!empty($phone)){
            $this->phone = $phone; 
        }
    }

    public function setStatus($status){
        if(!empty($status)){
            $this->status = $status; 
        }
    }

    public function setAdress($adress){
        if(!empty($adress)){
            $this->adress = $adress; 
        }
    }

    public function setShipping($shipping){
        if(!empty($shipping)){
            $this->shipping = $shipping; 
        }
    }

    public function setCreatetime($createtime){
        if(!empty($createtime)){
            $this->createtime = $createtime; 
        }
    }
	 
}