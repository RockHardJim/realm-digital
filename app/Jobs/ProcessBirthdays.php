<?php

namespace App\Jobs;

use App\Domains\Employees\Services\ApiService;
use App\Domains\Employees\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProcessBirthdays implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private array $employees, private NotificationService $service, private ApiService $apiService
    )
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $excludes = $this->apiService->noBirthdayWishes();


        //Loop all the employees and find ones whose birthdays are today this is done in the background
        foreach($this->employees as $employee){

            //The beauty of queues means i can run long processes at ease this will probably be faster if i was using SQS or something but oh well.
            foreach($excludes as $no_wishes){
                if($no_wishes !== $employee['employee']){
                    $system_date_today_month = Carbon::now()->format('m');

                    $system_date_today_day = Carbon::now()->format('d');

                    $employee_date_month = date("m",strtotime($employee['birth']));

                    $employee_date_day = date("d",strtotime($employee['birth']));

                    //a queue tracker and notifications system can be made but log works just fine
                    Log::info('System month today -> ' . $system_date_today_month .' Employee month today -> ' . $employee_date_month . ' System day today -> ' . $system_date_today_day . ' Employee date -> '. $employee_date_day);

                    //There are neater ways of doing this but this seems to get the job done of doing comparisons and N* time is small
                    if($system_date_today_month === $employee_date_month && $system_date_today_day === $employee_date_day){

                        //Create an array to parse into the service.
                        $package = array(
                            'notification' => Str::orderedUuid(),
                            'name' => $employee['name'],
                            'surname' => $employee['surname'],
                            'employee' => $employee['employee'],
                            'year' => Date('Y'),
                            'termination' => $employee['termination'],
                            'engagement' => $employee['engagement'],
                        );

                        $this->service->sendNotification($package);
                    }
                }else{
                    Log::info('Employee ' . $employee['name'] . 'Found in exclude data');
                }
            }
        }
    }
}
