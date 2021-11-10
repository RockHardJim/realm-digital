<?php
namespace App\Domains\Employees\Services;

use App\Domains\Employees\Repositories\NotificationsRepository;
use Illuminate\Support\Facades\Log;

class NotificationService {

    public function __construct(
        private NotificationsRepository $repository, private ApiService $apiService
    ){}


    /**
     *Layman's implementation of sending notifications first save in db then allow an event to handle the rest in the background
     * this way client application is not bogged down by requests that load for a long time while processing the user request.
     */
    public function sendNotification(array $data){

        //First check if i have sent a notification to the employee already if not proceed next validation step
        if(!$this->repository->employeeBirthdayNotification($data['employee'], $data['year'])){
            try {
                    $no_wishes = $this->apiService->noBirthdayWishes();

                    Log::info($no_wishes);

                    if(is_null($data['termination']) && !is_null($data['engagement'])) {
                        $this->repository->insert($data);
                        Log::info('Created birthday for ' . $data['name']);
                    }else{
                        Log::info('Employee ' . $data['name'] . ' Not illegible for notification');
                    }

            } catch(\Exception $e) {
                Log::critical($e->getMessage());
            }
        }
    }
}
