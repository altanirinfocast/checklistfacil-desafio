<?php

namespace App\Interfaces;

interface CustomerRepositoryInterface
{
    public function getAllCustomers();
    public function getFillableCustomers();
    public function getCustomerById($customerId);
    public function createCustomer(array $customerDetails);
    public function updateCustomerById($customerId, array $customerDetails);
    public function deleteCustomerById($customerId);
}
