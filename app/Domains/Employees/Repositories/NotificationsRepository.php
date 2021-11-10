<?php

namespace App\Domains\Employees\Repositories;

use App\Events\BirthDayWishMail;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Deals with notification creation and wrangling
 */
class NotificationsRepository{


    /**
     * Inject the notification model for ease of access from multiple functions internally. This ensures only the repository has read-write capabilities to the notifications table
     * @param Notification $model
     */
    public function __construct(
        private Notification $model
    ){}


    /**
     * Simple function to create a notification in the table and process the messaging requirement using a background queue.
     * @param array $data
     * @return bool
     */
    public function insert(array $data): bool
    {

        Log::info('Creating birthday wish for -> ' . $data['name']);
        //Create a new database transaction that will roll-back table state before a new notification is created in the database.
        DB::transaction(function() use($data){

            // create a new model of the data sent from the service
            $this->model::create($data);

            //Dispatch a new birthday wish mail that will send a formatted email to everyone in the users table as the employees endpoint returns data without any email nor cellphone number
            event(new BirthDayWishMail($data));
        });

        return true;
    }

    /**
     * Returns all the notifications in the database.
     * @return array|Collection
     */
    public function notifications(): array|Collection
    {
        return $this->model->all();
    }


    /**
     * Allows a service to query the employee and birthday year that employee got sent a notification to ensure no duplicate alerts are sent
     * @param string $id
     * @param string $year
     * @return mixed
     */
    public function employeeBirthdayNotification(string $id, string $year): mixed
    {
        return $this->model::where('employee', $id)
            ->where('year', $year)->first();
    }

    /**
     * Returns all the notifications that belong to an employee.
     * @param string $employee
     * @return mixed
     */
    public function employeeNotifications(string $employee): mixed
    {
        return $this->model::where('employee', $employee)->get();
    }
}
