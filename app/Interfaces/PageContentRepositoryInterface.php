<?php

namespace App\Interfaces;

interface PageContentRepositoryInterface
{
   
    public function getAllPageContents($keyword = '');
    public function storePageContent($data);
    public function getPageContentById($id);
    public function deletePageContent($page_content);

    // public function getAll();
    // public function findById($id);
    // public function create(array $data);
    public function updatePageContent($id, array $data);
    // public function delete($id);
}
