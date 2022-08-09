<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendLicenseRequest;
use App\Models\ContainerHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;

class SendLicenseController extends Controller
{
    protected ?Controller $controller;
    protected ?\App\Models\Request $request;
    protected ?ContainerHistory $containerHistory;

    public function __construct()
    {

    }

    public function __invoke(SendLicenseRequest $request)
    {
        return 1;
    }
}
