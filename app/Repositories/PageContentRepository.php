<?php

namespace App\Repositories;

use App\Models\PageContent;
use App\Interfaces\PageContentRepositoryInterface;

class PageContentRepository implements PageContentRepositoryInterface
{
    /**
     * Get all PageContents with pagination and filtering.
     *
     * @param string $keyword
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllPageContents($keyword = '')
    {
        $query = PageContent::query();

        $query->when($keyword, function($query) use ($keyword) {
            $query->where('page', 'like', '%' . $keyword . '%');
        });

        return $query->orderBy('page', 'ASC')->paginate(25);
    }

    /**
     * Store a new PageContent.
     *
     * @param array $data
     * @return PageContent
     */
    public function storePageContent($data)
    {
        $page_content = new PageContent();
        $page_content->page = $data['page'];
        $page_content->title = $data['title'];
        $page_content->short_description = $data['short_description'];
        $page_content->description = $data['description'];
        $page_content->slug = pageContentSlug($data['page'], 'page_contents');
        $page_content->meta_title = $data['meta_title'];
        $page_content->meta_description = $data['meta_description'];
        $page_content->meta_keyword = $data['meta_keyword'];
        
        if (isset($data['image'])) {
            $page_content->image = $data['image'];
        }

        $page_content->save();

        return $page_content;
    }

    /**
     * Get a PageContent by ID.
     *
     * @param int $id
     * @return PageContent
     */
    public function getPageContentById($id)
    {
        return PageContent::findOrFail($id);
    }

    /**
     * Update a PageContent.
     *
     * @param PageContent $page_content
     * @param array $data
     * @return PageContent
     */

  

    public function updatePageContent($page_content, array $data)
    {
        $page_content->page = $data['page'];
        $page_content->title = $data['title'];
        $page_content->short_description = $data['short_description'];
        $page_content->description = $data['description'];
        $page_content->meta_title = $data['meta_title'];
        $page_content->meta_description = $data['meta_description'];
        $page_content->meta_keyword = $data['meta_keyword'];

        if ($page_content->page !== $data['page']) {
            $page_content->slug = pageContentSlugUpdate($data['page'], 'page_contents');
        }

        if (isset($data['image'])) {
            if ($page_content->image && file_exists(public_path($page_content->image))) {
                unlink(public_path($page_content->image));
            }

            $page_content->image = $data['image'];
        }

        $page_content->save();
        return $page_content;
    }

    /**
     * Delete a PageContent.
     *
     * @param PageContent $page_content
     * @return bool
     */
    // public function deletePageContent(PageContent $page_content)
    // {
    //     if ($page_content->custom_field == 1) {
    //         return $page_content->delete();
    //     }
    //     return false;
    // }

    public function deletePageContent($id)
    {
        $article = PageContent::findOrFail($id);
        $article->delete();
        return true;
    }
}
