<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class assessments extends Model
{
    use HasFactory;

    public function __construct(array $assessments = array())
    {
        $assessments = json_decode(file_get_contents(database_path() . "/json/assessments.json"), true);
        $this->assessments = $assessments;
    }

    public function getAll(){
        return $this->assessments;
    }

}
