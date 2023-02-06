<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Interfaces\CustomerRepositoryInterface;

class CustomerRepository extends Customer implements CustomerRepositoryInterface
{

    /**
     * Get all of the models from the database.
     *
     * @return \App\Models\Customer
     */
    public function getAllCustomers()
    {
        return $this->all();
    }

    /**
     * Get all fillable of the models from the database.
     *
     * @return \App\Models\Customer
     */
    public function getFillableCustomers()
    {
        return $this->fillable;
    }

    /**
     * Get all of the models from the database.
     *
     * @return \App\Models\Customer
     */
    public function getCustomerById($customerId)
    {
        return $this->findOrFail($customerId);
    }

    /**
     * Get all of the models from the database.
     *
     * @return \App\Models\Customer
     */
    public function createCustomer(array $customerDetails)
    {
        return $this->create($customerDetails);
    }

    /**
     * Get all of the models from the database.
     *
     * @return \App\Models\Customer
     */
    public function updateCustomerById($customerId, array $customerDetails)
    {
        return $this->where($this->primaryKey, $customerId)->update($customerDetails) > 0;
    }

    /**
     * Get all of the models from the database.
     *
     * @return \App\Models\Customer
     */
    public function deleteCustomerById($customerId)
    {
        return $this->destroy($customerId);
    }
}
