<?php
use App\DataBase;

return[
    DataBase::class => function () {
        return new DataBase(
            host: '127.0.0.1', // para local 127.0.0.1
            name: 'Equilibrio', //Equilibrio la vieja ahora es inhala para el commit
            user: 'root',
            password: 'admin'
        );
    }
];
