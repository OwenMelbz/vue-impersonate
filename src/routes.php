<?php

Route::get(
    '/impersonate/users',
    'OwenMelbz\VueImpersonate\Controllers\VueImpersonateController')
->name('impersonate.users')
->middleware('web');