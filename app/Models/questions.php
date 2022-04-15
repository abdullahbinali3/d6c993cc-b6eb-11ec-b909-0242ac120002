<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class questions extends Model
{
    use HasFactory;

    public function __construct(array $students = array())
    {
        $questions = json_decode(file_get_contents(database_path() . "/json/questions.json"), true);
        $this->questions = $questions;
    }

    public function getAll(){
        return $this->questions;
    }

    public function getById(string $questionId){
        foreach($this->questions as $question){
            if($question["id"] === $questionId ){
                return $question;
            }
        }
    }
}
