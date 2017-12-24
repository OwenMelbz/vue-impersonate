<?php

namespace OwenMelbz\VueImpersonate\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use OwenMelbz\VueImpersonate\Services\VueImpersonateService;

class VueImpersonateController extends Controller {

    public function __invoke(Request $request)
    {
        if (!optional($request->user())->canImpersonate()) {
            return [];
        }

        return (new VueImpersonateService)->getImpersonatable();
    }
}