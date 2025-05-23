<?php

namespace App\Interfaces;

interface PopularPackagesRepositoryInterface
{
    public function getAll();
    public function findById($id);
}