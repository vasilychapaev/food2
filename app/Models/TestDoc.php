<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class TestDoc extends Model
{
    protected $connection = 'mongodb'; // обязательно!
    protected $collection = 'test_docs'; // имя коллекции в MongoDB
    protected $fillable = ['name', 'value'];
}