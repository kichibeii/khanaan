const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// Admin Css

mix.styles([
    'resources/css/admin/plugins.bundle.css',
  ], 'public/css/admin/plugins.bundle.css');

  mix.styles([
    'resources/css/admin/style.bundle.css',
    'resources/css/admin/styles.css'
  ], 'public/css/admin/style.bundle.css');


mix.styles([
    'resources/css/admin/style.bundle.css',
    'resources/css/admin/skins/header/base/light.css',
    'resources/css/admin/skins/header/menu/light.css',
    'resources/css/admin/skins/brand/dark.css',
    'resources/css/admin/skins/aside/dark.css'
  ], 'public/css/admin/app-login.css');

mix.styles([
    'resources/css/admin/pages/login/login-3.css'
  ], 'public/css/admin/login.css');

  mix.styles([
    'resources/css/admin/pages/invoices/invoice-2.css'
  ], 'public/css/admin/invoice.css');


// Dashboard
mix.scripts('resources/js/admin/pages/custom/dashboard.js', 'public/js/admin/dashboard.js').version();

// Profile
mix.scripts('resources/js/admin/pages/custom/profile.js', 'public/js/admin/profile.js').version();

// Permission
mix.scripts('resources/js/admin/pages/custom/permission/list-datatable.js', 'public/js/admin/permission/list-datatable.js').version();
mix.scripts('resources/js/admin/pages/custom/permission/add.js', 'public/js/admin/permission/add.js').version();

// Role
mix.scripts('resources/js/admin/pages/custom/role/list-datatable.js', 'public/js/admin/role/list-datatable.js').version();
mix.scripts('resources/js/admin/pages/custom/role/add.js', 'public/admin/js/role/add.js').version();

// User
mix.scripts('resources/js/admin/pages/custom/user/list-datatable.js', 'public/js/admin/user/list-datatable.js').version();
mix.scripts('resources/js/admin/pages/custom/user/add.js', 'public/js/admin/user/add.js').version();

// Dropdown
mix.scripts('resources/js/admin/pages/custom/dropdown/list-datatable.js', 'public/js/admin/dropdown/list-datatable.js').version();
mix.scripts('resources/js/admin/pages/custom/dropdown/add.js', 'public/js/admin/dropdown/add.js').version();
mix.scripts('resources/js/admin/pages/custom/dropdown/item-list-datatable.js', 'public/js/admin/dropdown/item-list-datatable.js').version();
mix.scripts('resources/js/admin/pages/custom/dropdown/item-add.js', 'public/js/admin/dropdown/item-add.js').version();

// Color Group
mix.scripts('resources/js/admin/pages/custom/group_color/list-datatable.js', 'public/js/admin/group_color/list-datatable.js').version();
mix.scripts('resources/js/admin/pages/custom/group_color/add.js', 'public/js/admin/group_color/add.js').version();

// Color
mix.scripts('resources/js/admin/pages/custom/color/list-datatable.js', 'public/js/admin/color/list-datatable.js').version();
mix.scripts('resources/js/admin/pages/custom/color/add.js', 'public/js/admin/color/add.js').version();

// Brand
mix.scripts('resources/js/admin/pages/custom/brand/list-datatable.js', 'public/js/admin/brand/list-datatable.js').version();
mix.scripts('resources/js/admin/pages/custom/brand/add.js', 'public/js/admin/brand/add.js').version();

// Bank
mix.scripts('resources/js/admin/pages/custom/bank/list-datatable.js', 'public/js/admin/bank/list-datatable.js').version();
mix.scripts('resources/js/admin/pages/custom/bank/add.js', 'public/js/admin/bank/add.js').version();

// Slideshow
mix.scripts('resources/js/admin/pages/custom/slideshow/list-datatable.js', 'public/js/admin/slideshow/list-datatable.js').version();
mix.scripts('resources/js/admin/pages/custom/slideshow/add.js', 'public/js/admin/slideshow/add.js').version();

// Article
mix.styles('resources/css/admin/pages/article/article.css', 'public/css/admin/show.css');
mix.scripts('resources/js/admin/pages/custom/article/list-datatable.js', 'public/js/admin/article/list-datatable.js').version();
mix.scripts('resources/js/admin/pages/custom/article/add.js', 'public/js/admin/article/add.js').version();

// Page
mix.scripts('resources/js/admin/pages/custom/page/list-datatable.js', 'public/js/admin/page/list-datatable.js').version();
mix.scripts('resources/js/admin/pages/custom/page/add.js', 'public/js/admin/page/add.js').version();

// Banner
mix.scripts('resources/js/admin/pages/custom/banner/list-datatable.js', 'public/js/admin/banner/list-datatable.js').version();
mix.scripts('resources/js/admin/pages/custom/banner/add.js', 'public/js/admin/banner/add.js').version();

// product
mix.scripts('resources/js/admin/pages/custom/product/list-datatable.js', 'public/js/admin/product/list-datatable.js').version();
mix.scripts('resources/js/admin/pages/custom/product/add.js', 'public/js/admin/product/add.js').version();
mix.scripts('resources/js/admin/pages/custom/product/additional-list-datatable.js', 'public/js/admin/product/additional-list-datatable.js').version();
mix.scripts('resources/js/admin/pages/custom/product/additional-add.js', 'public/js/admin/product/additional-add.js').version();

// Admin Javascript
mix.scripts([
    'resources/js/admin/plugins.bundle.js'
  ], 'public/js/admin/plugins.bundle.js');

  mix.scripts([
    'resources/js/admin/scripts.bundle.js'
  ], 'public/js/admin/scripts.bundle.js');

  mix.scripts([
    'resources/js/admin/pages/custom/login/login-general.js'
  ], 'public/js/admin/login-general.js');


// copy images folder into laravel public folder
mix.copyDirectory('resources/demo12/src/assets/media', 'public/assets/admin/media');
mix.copyDirectory('resources/plugins', 'public/admin/plugins');
mix.copyDirectory('resources/css/admin/fonts', 'public/css/admin/fonts');
mix.copy('resources/css/admin/noimage.jpg', 'public/assets/noimage.jpg');

/**
* plugins specific issue workaround for webpack
* @see https://github.com/morrisjs/morris.js/issues/697
* @see https://stackoverflow.com/questions/33998262/jquery-ui-and-webpack-how-to-manage-it-into-module
*/
mix.webpackConfig({
   resolve: {
       alias: {
           'morris.js': 'morris.js/morris.js',
           'jquery-ui': 'jquery-ui',
       },
   },
});
