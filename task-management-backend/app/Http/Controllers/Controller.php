<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected array $categories = [
        ['id' => 1, 'name' => 'Analysis'],
        ['id' => 2, 'name' => 'Development'],
        ['id' => 3, 'name' => 'Maintenance']
    ];

    protected function getCategories(): array
    {
        return $this->categories;
    }
}
