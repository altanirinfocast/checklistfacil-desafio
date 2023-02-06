<?php

namespace App\Repositories;

use App\Models\Cake;
use App\Interfaces\CakeRepositoryInterface;

class CakeRepository extends Cake implements CakeRepositoryInterface
{

    /**
     * Get all of the models from the database.
     *
     * @return \App\Models\Cake
     */
    public function getAllCakes()
    {
        return $this->all();
    }

    /**
     * Get all of the models from the database.
     *
     * @return \App\Models\Cake
     */
    public function getCakeById($cakeId)
    {
        return $this->findOrFail($cakeId);
    }

    /**
     * Get all of the models from the database.
     *
     * @return \App\Models\Cake
     */
    public function createCake(array $cakeDetails)
    {
        return $this->create($cakeDetails);
    }

    /**
     * Get all of the models from the database.
     *
     * @return \App\Models\Cake
     */
    public function updateCakeById($cakeId, array $cakeDetails)
    {
        return $this->where($this->primaryKey, $cakeId)->update($cakeDetails) > 0;
    }

    /**
     * Get all of the models from the database.
     *
     * @return \App\Models\Cake
     */
    public function deleteCakeById($cakeId)
    {
        return $this->destroy($cakeId);
    }
}
