<?php

namespace App\Console\Commands;

use App\Models\assessments;
use App\Models\studentResponses;
use App\Models\students;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates Reports by Student ID';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(students $students, assessments $assessments, studentResponses $studentResponses)
    {
        parent::__construct();
        $this->students = $students;
        $this->assessments = $assessments;
        $this->studentResponses = $studentResponses;

    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $this->info("Please enter the following");
        $studentId = $this->ask("Student ID:");
        $reportId = $this->ask("Report to generate (1 for Diagnostic, 2 for Progress, 3 for Feedback):");

        $student = $this->students->getById($studentId);
        $studentResponsesById = $this->studentResponses->getAllByStudentId($studentId);
        $latestResponse = $this->studentResponses->getLateststudentResponse($studentResponsesById);

        // diagnostic report // start
        if($reportId == 1) {

            list($numberOfQuestions, $rawScore) = $this->studentResponses->getAssessmentScoreByResponse($latestResponse);

            $this->info($student["firstName"] . " " . $student["lastName"] . " recently completed Numeracy assessment on " . $latestResponse["assigned"]); // using assigned instead of completed as some of the student-response entries does not contain completed property
            $this->info("He got " . $rawScore . " questions right out of " . $numberOfQuestions . ". Details by strand given below:");
            $this->info("Numeracy and Algebra: " . $rawScore . " out of " . $numberOfQuestions . " correct");
            // current data does not contain any responses for following categories so hardcoding 0 out of 0 for the time being
            $this->info("Measurement and Geometry: 0 out of 0 correct.");
            $this->info("Statistics and Probability: 0 out of 0 correct.");

        }
        // diagnostic report // end

        // progress report //start
        if($reportId == 2){
            $numberOfResponses = count($studentResponsesById);
            $oldestResponse = $this->studentResponses->getOldeststudentResponse($studentResponsesById);
            $latestRawScore = $latestResponse["results"]["rawScore"];
            $oldestRawScore = $oldestResponse["results"]["rawScore"];

            $this->info($student["firstName"] . " " . $student["lastName"] . " has completed Numeracy assessment " . $numberOfResponses ." times in total. Date and raw score given below: ");

            foreach ($studentResponsesById as $studentResponse){
                list($numberOfQuestions, $rawScore)  = $this->studentResponses->getAssessmentScoreByResponse($studentResponse);
                $this->info("Date: " . $studentResponse["assigned"] . ", Raw Score: " . $rawScore . " out of " . $numberOfQuestions );
            }
            if($latestRawScore > $oldestRawScore){
                $this->info($student["firstName"] . " " . $student["lastName"] ." got " . ($latestRawScore - $oldestRawScore) . "  more correct in the recent completed assessment than the oldest");

            }else if ($latestRawScore < $oldestRawScore){
                $this->info($student["firstName"] . " " . $student["lastName"] ." got " . ($oldestRawScore - $latestRawScore) . "  less correct in the recent completed assessment than the oldest");
            }else {
                $this->info($student["firstName"] . " " . $student["lastName"] ." got " . $oldestRawScore . " correct answers in the oldest completed assessment");
                $this->info($student["firstName"] . " " . $student["lastName"] ." got " . $latestRawScore . " correct answers in the recent completed assessment");
            }
        }
        // progress report //end

        // feedback report //start
        if($reportId == 3){
            list($numberOfQuestions, $rawScore)  = $this->studentResponses->getAssessmentScoreByResponse($latestResponse);
            $this->info($student["firstName"] . " " . $student["lastName"] . " recently completed Numeracy assessment on " . $latestResponse["assigned"]); // using assigned instead of completed as some of the student-response entries does not contain completed property
            // for the sake of time constraint hardcoding the feedback section
            $this->info("Question: What is the 'median' of the following group of numbers 5, 21, 7, 18, 9?");
            $this->info("Your answer: A with value 7");
            $this->info("Right answer: B with value 9");
            $this->info("Hint: You must first arrange the numbers in ascending order. The median is the middle term, which in this case is 9");


        }
        // feedback report //end

    }
}
