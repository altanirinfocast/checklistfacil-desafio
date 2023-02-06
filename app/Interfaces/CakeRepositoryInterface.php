<?php

namespace App\Interfaces;

interface CakeRepositoryInterface
{
    public function getAllCakes();
    public function getCakeById($cakeId);
    public function createCake(array $cakeDetails);
    public function updateCakeById($cakeId, array $cakeDetails);
    public function deleteCakeById($cakeId);
}
