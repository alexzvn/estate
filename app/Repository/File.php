<?php

namespace App\Repository;

use App\Models\File as ModelsFile;

class File extends BaseRepository
{
    public function __construct(ModelsFile $model) {
        $this->setModel($model);
    }
}
