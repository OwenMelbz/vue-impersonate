# VueJS Frontend Component for integration for Laravel Impersonate

This package adds a really simple frontend component written as a self contained Vuejs 2 component to allow you users to easily impersonate others via the API provided by https://github.com/404labfr/laravel-impersonate.

## Preview

![Preview](/docs/preview.jpg)

## Usage

1. Install via composer `composer require owenmelbz/vue-impersonate`

2. Register the service provider (if the auto discovery does not work) - typically done inside the `app.php` providers array e.g `OwenMelbz\VueImpersonate\VueImpersonateServiceProvider::class`

3. There are 3 publishable components - the only `required` component is the javascript which you can publish with `php artisan vendor:publish` then select `vue-impersonate-javascript (required)`. This will copy the .vue component into `resources/js/components/vue-impersonate.vue` - feel free to move this.

4. You must then include the component in your javascript bundle, typically achieved from within an entry point such as `app.js` e.g `Vue.component('vue-impersonate', require('./components/vue-impersonate.vue'));`

5. Once published you should make sure you've set up laravel-impersonate correctly by including the correct traits, and methods and routes to allow impersonating.

6. Once laravel-impersonate is set up you should include the blade directive within your template `@vueImpersonate` - this should be housed within your vuejs container e.g `#app` - it will only render for users who possess the `canImpersonate` permissions.

## Configuration

### Display name

You can change what is displayed in the dropdown by changing the `display_name_field` from within the published config - you can use accessors/mutators here or normal database columns.

### Custom vue directive

If you want to rename the component from `vue-impersonate` you must publish the config file via `php artisan vendor:publish` and change it within the config under the `custom_directive` param.

### Custom user provider

By default laravel uses providers to log in users, this is defined within the `config/auth.php` then within the `providers` array. We use the class defined within the `model` property of the provider you define. By default it is called `users` so that we'll also uses the configured class to populate the list of users on the frontend.

### Custom list of users

If you want more fine-grain control over which users show in the dropdown, you can set your own route and define it within the `custom_route` and we'll use that to display the users. You must return a JSON array with each user object containing at least an `id` and `display_name` field. e.g

```js
[
    {
        id: 12,
        display_name: 'User X :: user.x@gmail.com',
    },
    {
        id: 14,
        display_name: 'User Y :: user.y@gmail.com',
    },
];
```
