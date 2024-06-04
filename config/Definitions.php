<?php
use App\DataBase;

return[
    DataBase::class => function () {
        return new DataBase(
            host: '10.128.0.5',
            name: 'inhala', //Equilibrio la vieja ahora es inhala para el commit
            user: 'root',
            password: 'admin'
        );
    }
];
