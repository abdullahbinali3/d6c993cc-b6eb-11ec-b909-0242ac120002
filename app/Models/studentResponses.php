<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class studentResponses extends Model
{
    use HasFactory;

    public function __construct(array $studentResponses = array())
    {
        $studentResponses = json_decode(file_get_contents(database_path() . "/json/student-responses.json"), true);
        $this->studentResponses = $studentResponses;
    }

    public function getAll(){
        return $this->studentResponses;
    }

    public function getAllByStudentId(string $studentId){

        $studentResponsesById = array();
        foreach($this->studentResponses as $studentResponse){
            if($studentResponse["student"]["id"] == $studentId){
                array_push($studentResponsesById, $studentResponse);
            }
        }

        return $studentResponsesById;
    }

    public function getLateststudentResponse(array $studentResponses){
        $latest = $studentResponses[0]; // set the highest object to the first one in the array

        foreach($studentResponses as $studentResponse) { // loop through every object in the array
            $date = \DateTime::createFromFormat('d/m/Y H:i:s',$studentResponse["assigned"]);
            $latestDate = \DateTime::createFromFormat('d/m/Y H:i:s',$latest["assigned"]);
            if($date > $latestDate) { // If the number of the current object is greater than the maxs number:
                $latest = $studentResponse; // set the max to the current object
            }
        }
        return $latest;
    }

    public function getOldeststudentResponse(array $studentResponses){
        $oldest = $studentResponses[0]; // set the highest object to the first one in the array

        foreach($studentResponses as $studentResponse) { // loop through every object in the array
            $date = \DateTime::createFromFormat('d/m/Y H:i:s',$studentResponse["assigned"]);
            $oldestDate = \DateTime::createFromFormat('d/m/Y H:i:s',$oldest["assigned"]);
            if($date < $oldestDate) { // If the number of the current object is greater than the maxs number:
                $$oldest = $studentResponse; // set the max to the current object
            }
        }
        return $oldest;
    }

    public function getAssessmentScoreByResponse(array $studentResponse){

        $numberOfQuestions = count($studentResponse["responses"]);
        $rawScore = $studentResponse["results"]["rawScore"];

        return [$numberOfQuestions, $rawScore];
    }


}
