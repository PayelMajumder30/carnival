<?php
namespace App\Interfaces;

interface TripCategoryRepositoryInterface
{
    public function getAll();
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);

    public function banner_create(array $data);
    public function banner_update(array $data);

    public function destination_create(array $data);
    public function destination_update(array $data);
    
}