<?php

namespace Tiketux\RancherProjects;

use Illuminate\Support\ServiceProvider;

class RancherProjectsServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap services.
   *
   * @return void
   */
  public function boot()
  {
    $this->loadRoutesFrom(__DIR__.'/routes.php');
    $this->publishes([
        __DIR__.'/migrations' => database_path('migrations'),
    ],"Migration_Package_Rancher_Project");
    $this->publishes([
        __DIR__.'/components' => resource_path('js/components'),
    ],"Rancher_Component");
  }

  /**
   * Register services.
   *
   * @return void
   */
  public function register()
  {
  }
}
