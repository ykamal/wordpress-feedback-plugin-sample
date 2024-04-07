<?php

class FeedbackTest extends \PluginTests\BaseCase 
{
    private $feedback_class;

    public function __construct() {
        parent::__construct();
        $this->feedback_class = new WPFB\DB\Feedback();
    }

    public function test_canAddFeedback()
    {
        $result = $this->feedback_class->add_feedback($this->testPostId, '127.0.0.2', true);

        // $feedback = $this->feedback_class->get_feedback($this->testPostId);

        $this->assertNotFalse($result);
    }

    public function test_feedbackCountIsCorrect()
    {
        $result = $this->feedback_class->add_feedback($this->testPostId, '127.0.0.3', true);

        $feedback = $this->feedback_class->get_feedback($this->testPostId);

        $this->assertEquals($feedback['total'], 1);
    }

    public function test_cannotSendFeedbackMultipleTimes()
    {
        $this->expectException(\Exception::class);
        $this->feedback_class->add_feedback($this->testPostId, '127.0.0.3', true);
        $this->feedback_class->add_feedback($this->testPostId, '127.0.0.3', true);
        
    }
}