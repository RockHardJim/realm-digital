<?php

namespace App\Http\Controllers\Api;

use App\Domains\Employees\Mappers\EmployeeMapper;
use App\Domains\Employees\Services\ApiService;
use App\Domains\Employees\Services\NotificationService;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessBirthdays;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\NoReturn;

class BirthdayController extends Controller
{

    public function __construct(
        private ApiService $apiService, private NotificationService $notificationService
    ){}

    /**
     * Honestly making this an api service is my go-to route too bad i'm not necessarily looking at creating a sanctum token for authentication purposes
     * but this takes a request from a user then allows a user controlled 3rd party app to issue send command.
     * @param Request $request
     * @return JsonResponse
     */
    public function send(Request $request): JsonResponse
    {

        try {
            $employees = $this->apiService->employees();

            $mapper = new EmployeeMapper();

            //Map the data to what I preset in the model and send that through to the ProcessBirthdays job because imagine if a user clicks and waits long time while i process that's just bad design anyhow lets continue.
            $employees = $mapper($employees);

            //Process all birthday requests in background and make sure api is extremely performant.
            dispatch(new ProcessBirthdays($employees, $this->notificationService, $this->apiService));
            return response()->json(['status' => true, 'message' => 'Request has been successfully created you will get notified if anyone has a birthday today']);
        }catch(\Exception $e){
            return response()->json(['status' => false, 'message' => 'System exception occurred while processing request please try again later'], 500);
        }

    }
}
