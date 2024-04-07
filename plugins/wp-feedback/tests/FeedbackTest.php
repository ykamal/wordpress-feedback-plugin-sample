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
        $this->assertNotFalse($result);
    }

    public function test_feedbackCountIsCorrect()
    {
        $result = $this->feedback_class->add_feedback($this->testPostId, '127.0.0.3', true);

        $feedback = $this->feedback_class->get_feedback($this->testPostId);

        $this->assertEquals($feedback['total'], 1);
        $this->assertEquals($feedback['helpful'], 100);
    }

    public function test_cannotSendFeedbackMultipleTimes()
    {
        $this->expectException(Exception::class);
        $this->feedback_class->add_feedback($this->testPostId, '127.0.0.3', true);
        $this->feedback_class->add_feedback($this->testPostId, '127.0.0.3', true);
        
    }

    public function test_hasVoted()
    {
        $result = $this->feedback_class->add_feedback($this->testPostId, '127.0.0.3', true);

        $this->assertEquals($this->feedback_class->has_voted($this->testPostId, '127.0.0.3'), 1);
        $this->assertEquals($this->feedback_class->has_voted($this->testPostId, '127.0.1.3'), 0);
    }

    public function test_voteByIp()
    {
        $result = $this->feedback_class->add_feedback($this->testPostId, '127.0.0.3', true);

        $vote = $this->feedback_class->get_vote_by_ip($this->testPostId, '127.0.0.3');

        $this->assertEquals($vote->is_helpful, 1);
    }
}