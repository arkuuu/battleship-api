<?php

declare(strict_types=1);

namespace App\Model;

abstract class Model
{

    /**
     * @var \App\Database
     */
    protected $db;


    public function __construct(\App\Database $db)
    {
        $this->db = $db;
    }
}
