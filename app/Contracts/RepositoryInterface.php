<?php

namespace App\Contracts;

interface RepositoryInterface
{
    /**
     * Get the model
     *
     * @return \Jenssegers\Mongodb\Eloquent\Model
     */
    public function model();
}
