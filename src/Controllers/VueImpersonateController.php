<?php

namespace OwenMelbz\VueImpersonate\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use OwenMelbz\VueImpersonate\Services\VueImpersonateService;

class VueImpersonateController extends Controller {

    protected $service;

    public function __construct(VueImpersonateService $service)
    {
        $this->middleware('web');
        $this->middleware('auth');
        $this->service = $service;
    }

    public function __invoke(Request $request)
    {
        if (!optional($request->user())->canImpersonate()) {
            return [];
        }

        return $this->service->getImpersonatable();
    }
}