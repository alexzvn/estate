<?php

namespace App\Contracts;

interface RepositoryInterface
{
    /**
     * Get the model
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function model();
}
