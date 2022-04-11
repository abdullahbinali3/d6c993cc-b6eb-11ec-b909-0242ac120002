<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class students extends Model
{
    use HasFactory;

    public function getAll(){
        $students = json_decode(file_get_contents(database_path() . "/json/students.json"), true);
        return $students;
    }
}
