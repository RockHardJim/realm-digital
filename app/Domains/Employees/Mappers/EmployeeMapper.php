<?php
namespace App\Domains\Employees\Mappers;

class EmployeeMapper {


    public function __invoke(
        array $data
    ): array
    {
            return $this->map($data);
    }

    private function map(array $data): array
    {

        $employees = array();

        /**
         * I like neatness so the array keys have to be nice and memorable hence I did this.
         */
        foreach($data as $employee){
            //Hehehe the api returns a customer object so use isset() to determine if $employee["name"] is defined.
            if(isset($employee["name"])) {
                $employees[] = array("employee" => $employee['id'],
                    "name" => $employee["name"],
                    "surname" => $employee["lastname"],
                    "birth" => $employee["dateOfBirth"],
                    "termination" => $employee["employmentEndDate"],
                    "engagement" => $employee["employmentStartDate"]);
            }
        }
        return $employees;
    }
}
