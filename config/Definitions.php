<?php
use App\DataBase;

return[
    DataBase::class => function () {
        return new DataBase(
            host: '127.0.0.1',
            name: 'Equilibrio',
            user: 'root',
            password: 'admin'
        );
    }
];
