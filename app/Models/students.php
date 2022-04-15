<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class students extends Model
{
    use HasFactory;

    public function __construct(array $students = array())
    {
        $students = json_decode(file_get_contents(database_path() . "/json/students.json"), true);
        $this->students = $students;
    }

    public function getAll(){
        return $this->students;
    }

    public function getById(string $studentId){

        foreach($this->students as $student){
            if($student["id"] === $studentId ){
                return $student;
            }
        }
    }
}
