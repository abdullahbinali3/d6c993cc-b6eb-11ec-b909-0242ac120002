<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class reportTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testDiagnosticReport()
    {
        // tests if dignostic report has been successfully executed
        $this->artisan('report:generate')
            ->expectsQuestion('Student ID:', 'student1')
            ->expectsQuestion('Report to generate (1 for Diagnostic, 2 for Progress, 3 for Feedback):', 1)
            ->expectsOutput('Tony Stark recently completed Numeracy assessment on 14/01/2022 10:31:00')
            ->expectsOutput('He got 6 questions right out of 6. Details by strand given below:')
            ->expectsOutput('Numeracy and Algebra: 6 out of 6 correct')
            ->expectsOutput('Measurement and Geometry: 0 out of 0 correct.')
            ->expectsOutput('Statistics and Probability: 0 out of 0 correct.');

    }

    public function testProgressReport()
    {
        // tests if progress report has been successfully executed
        $this->artisan('report:generate')
            ->expectsQuestion('Student ID:', 'student1')
            ->expectsQuestion('Report to generate (1 for Diagnostic, 2 for Progress, 3 for Feedback):', 2)
            ->expectsOutput('Date: 14/12/2019 10:31:00, Raw Score: 6 out of 16')
            ->expectsOutput('Date: 14/12/2020 10:31:00, Raw Score: 10 out of 16')
            ->expectsOutput('Date: 14/12/2021 10:31:00, Raw Score: 15 out of 16')
            ->expectsOutput('Date: 14/01/2022 10:31:00, Raw Score: 6 out of 6')
            ->expectsOutput('Tony Stark got 6 correct answers in the oldest completed assessment')
            ->expectsOutput('Tony Stark got 6 correct answers in the recent completed assessment');

    }

    public function testFeedbackReport()
    {
        // tests if feedback report has been successfully executed
        $this->artisan('report:generate')
            ->expectsQuestion('Student ID:', 'student1')
            ->expectsQuestion('Report to generate (1 for Diagnostic, 2 for Progress, 3 for Feedback):', 3)
            ->expectsOutput('Tony Stark recently completed Numeracy assessment on 14/01/2022 10:31:00')
            ->expectsOutput('Question: What is the \'median\' of the following group of numbers 5, 21, 7, 18, 9?')
            ->expectsOutput('Your answer: A with value 7')
            ->expectsOutput('Right answer: B with value 9')
            ->expectsOutput('Hint: You must first arrange the numbers in ascending order. The median is the middle term, which in this case is 9');

    }


}
