<?php

class CustomerModel extends BaseModel {

    private $table_name = "customer";
    /**
     * A model class for the `customer` database table.
     * It exposes operations that can be performed on customers records.
     */
    function __construct() {
        // Call the parent class and initialize the database connection settings.
        parent::__construct();
    }

    /**
     * Retrieve all customers from the `customer` table.
     * @return array A list of customers. 
     */
    public function getAll() {
        $sql = "SELECT * FROM customer";
        $data = $this->rows($sql);
        return $data;
    }

    /**
     * Get a list of customers whose name matches or contains the provided value.       
     * @param string $customerName 
     * @return array An array containing the matches found.
     */
    public function getWhereLike($customerName) {
        $sql = "SELECT * FROM customer WHERE Name LIKE :name";
        $data = $this->run($sql, [":name" => "%" . $customerName . "%"])->fetchAll();
        return $data;
    }

    /**
     * Retrieve an customer by its id.
     * @param int $customer_id the id of the customer.
     * @return array an array containing information about a given customer.
     */
    public function getCustomerById($customer_id) {
        $sql = "SELECT * FROM customer WHERE CustomerId = ?";
        $data = $this->run($sql, [$customer_id])->fetch();
        return $data;
    }

}
