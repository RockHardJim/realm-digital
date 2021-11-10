<?php
namespace App\Domains\Employees\Services;

use Illuminate\Support\Facades\Http;


class ApiService {

    private string $endpoint = 'https://interview-assessment-1.realmdigital.co.za';


    /**
     * Returns the employees in array form using curl
     * @return mixed
     */
    public function employees(): mixed
    {

        //Using laravel's builtin http client we request the employees from the api and turn them into arrays the ['verify' => false] is a security risk because i have an expired certificate on my laptop to properly request the api so im allowing it to work without that.

        return Http::withOptions([
            'verify' => false,
        ])->get($this->endpoint . '/employees')->json();

    }


    /**
     * Returns all employee ID's that must not receive birthday wish emails.
     * @return mixed
     */
    public function noBirthdayWishes(): mixed
    {
        return Http::withOptions([
            'verify' => false,
        ])->get($this->endpoint . '/do-not-send-birthday-wishes')->json();
    }

}
