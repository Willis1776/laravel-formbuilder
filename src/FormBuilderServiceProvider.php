<?php
/*--------------------
https://github.com/jazmy/laravelformbuilder
Licensed under the GNU General Public License v3.0
Author: Jasmine Robinson (jazmy.com)
Last Updated: 12/29/2018
----------------------*/

namespace jazmy\FormBuilder;

use jazmy\FormBuilder\Middlewares\FormAllowSubmissionEdit;
use jazmy\FormBuilder\Middlewares\PublicFormAccess;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;


class FormBuilderServiceProvider extends ServiceProvider
{
  public function register()
  {
    $this->mergeConfigFrom(
      __DIR__ . '/../config/config.php',
      'formbuilder'
    );
  }

  public function boot()
  {
    // load custom route overrides
    if (env('LOAD_FORM_ROUTES', true))
      $this->loadRoutesFrom(__DIR__ . '/../routes.php');

    // register the middleware
    Route::aliasMiddleware('public-form-access', PublicFormAccess::class);
    Route::aliasMiddleware('submisson-editable', FormAllowSubmissionEdit::class);

    // load migrations
    // $this->loadMigrationsFrom( __DIR__.'/../migrations/tenant' );

    // load the views
    $this->loadViewsFrom(__DIR__ . '/../views', 'formbuilder');

    // publish config files
    $this->publishes([
      __DIR__ . '/../config/config.php' => config_path('formbuilder.php', 'formbuilder'),
    ], 'formbuilder-config');

    // publish view files
    $this->publishes([
      __DIR__ . '/../views' => resource_path('views/vendor/formbuilder', 'formbuilder::'),
    ], 'formbuilder-views');

    // publish public assets
    $this->publishes([
      __DIR__ . '/../public' => public_path('vendor/formbuilder'),
    ], 'formbuilder-public');

    // publish migration assets
    $this->publishes([
      __DIR__ . '/../migrations/' => database_path('migrations/tenant'),
    ], 'migrations/tenant');
  }
}
