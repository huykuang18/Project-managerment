<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Repositories\User\InterfaceUserRepository',
            'App\Repositories\User\UserRepository',
            'App\Repositories\Project\InterfaceProjectRepository',
            'App\Repositories\Project\ProjectRepository',
            'App\Repositories\Module\InterfaceModuleRepository',
            'App\Repositories\Module\ModuleRepository',
            'App\Repositories\Item\InterfaceItemRepository',
            'App\Repositories\Item\ItemRepository',
            'App\Repositories\Issue\InterfaceIssueRepository',
            'App\Repositories\Issue\IssueRepository',
            'App\Repositories\Document\InterfaceDocumentRepository',
            'App\Repositories\Document\DocumentRepository',
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
