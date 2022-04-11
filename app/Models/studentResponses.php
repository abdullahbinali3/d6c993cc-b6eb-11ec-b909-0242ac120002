<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class studentResponses extends Model
{
    use HasFactory;

    public function getAll(){
        $studentResponses = json_decode(file_get_contents(database_path() . "/json/student_responses.json"), true);
        return $studentResponses;
    }
}
