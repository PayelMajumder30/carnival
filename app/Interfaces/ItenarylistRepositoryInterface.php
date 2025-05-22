<?php

namespace App\Interfaces;

interface ItenarylistRepositoryInterface
{
    public function getAll();
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);

    public function gallery_create(array $data);
    public function gallery_update(array $data);
}