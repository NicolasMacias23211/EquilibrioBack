<?php
use App\databaseConnection\DataBase;

return[
    DataBase::class => function () {
        return new DataBase(
            host: $_ENV['HOST'],
            name: $_ENV['NAME'], 
            user: $_ENV['USER'],
            password: $_ENV['PASSWORD']
        );
    }
];
