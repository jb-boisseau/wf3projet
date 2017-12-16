<?php

namespace WF3\Domain;

class PaypalInvoice{

    private $id;
    

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }
}