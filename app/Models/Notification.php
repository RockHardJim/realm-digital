<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    //            $table->uuid('notification', 100)->unique();
    //            $table->string('name');
    //            $table->string('surname');
    //            $table->BigInteger('employee')->index('employee');
    //            $table->string('year');
    //            $table->enum('status', ['sent', 'not-sent'])->default('not-sent');

    protected $fillable = ['notification', 'name', 'surname', 'employee', 'year', 'status'];
}
