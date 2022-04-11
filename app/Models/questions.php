<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class questions extends Model
{
    use HasFactory;

    public function getAll(){
        $questions = json_decode(file_get_contents(database_path() . "/json/questions.json"), true);
        return $questions;
    }
}
