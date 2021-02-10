<?php

require_once('database.php');

class Customer extends Database {
    private $json_input;

    public function __construct($json_input) {
        $this->json_input = $json_input;
        parent::__construct();
    }

    public function fetch_restaurant() {
        $res_id = $this->json_input->id;

        $query = 'SELECT * ';
        $query .= 'FROM restaurant WHERE res_id=?;';
        $paraArray = ['s', $res_id];

        return @$this->query($query, $paraArray);
    }
   
    public function next_order_id($increment) {
        return @$this->fetch_next_id('orders', 'order_id', 'oid', 5, $increment);
    }
}
