<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Interfaces\UserInterface;
// use App\Interfaces\OrderInterface;
// use App\Interfaces\CategoryInterface;
// use App\Interfaces\EventInterface;
// use App\Interfaces\DepartmentInterface;
use App\Interfaces\ProductInterface;
// use App\Repositories\ArticleRepositoryInterface;

use App\Repositories\UserRepository;
// use App\Repositories\OrderRepository;
// use App\Repositories\CategoryRepository;
// use App\Repositories\EventRepository;
// use App\Repositories\DepartmentRepository;
use App\Repositories\ProductRepository;
// use App\Repositories\ArticleRepository;

use App\Interfaces\ArticleRepositoryInterface;
use App\Repositories\ArticleRepository;

use App\Interfaces\PartnerRepositoryInterface;
use App\Repositories\PartnerRepository;

use App\Interfaces\BannerRepositoryInterface;
use App\Repositories\BannerRepository;

use App\Interfaces\TripCategoryRepositoryInterface;
use App\Repositories\TripCategoryRepository;

use App\Interfaces\TripCategoryBannerRepositoryInterface;
use App\Repositories\TripCategoryBannerRepository;

use App\Interfaces\SocialRepositoryInterface;
use App\Repositories\SocialRepository;

use App\Interfaces\ChooseUsRepositoryInterface;
use App\Repositories\ChooseUsRepository;

use App\Interfaces\BlogRepositoryInterface;
use App\Repositories\BlogRepository;

use App\Interfaces\PageContentRepositoryInterface;
use App\Repositories\PageContentRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
        $this->app->bind(PageContentRepositoryInterface::class, PageContentRepository::class);
        $this->app->bind(BlogRepositoryInterface::class, BlogRepository::class);
        $this->app->bind(PartnerRepositoryInterface::class, PartnerRepository::class);
        $this->app->bind(SocialRepositoryInterface::class, SocialRepository::class);
        $this->app->bind(BannerRepositoryInterface::class, BannerRepository::class);
        $this->app->bind(TripCategoryRepositoryInterface::class, TripCategoryRepository::class);
        $this->app->bind(TripCategoryBannerRepositoryInterface::class, TripCategoryBannerRepository::class);
        $this->app->bind(ChooseUsRepositoryInterface::class, ChooseUsRepository::class);

        $this->app->bind(UserInterface::class, UserRepository::class);
        // $this->app->bind(OrderInterface::class, OrderRepository::class);
        // $this->app->bind(CategoryInterface::class, CategoryRepository::class);
        $this->app->bind(ProductInterface::class, ProductRepository::class);
        // $this->app->bind(EventInterface::class, EventRepository::class);
        // $this->app->bind(DepartmentInterface::class, DepartmentRepository::class);

        // $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
