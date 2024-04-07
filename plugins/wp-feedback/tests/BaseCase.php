<?php

namespace PluginTests;

class BaseCase extends \PHPUnit\Framework\TestCase
{
    public $testPostId;

    public function setUp(): void
    {
        $this->testPostId = wp_insert_post([
            'post_title' => 'TEST - Sample Post',
            'post_content' => 'This is just some sample post content.'
        ]);
    }

    public function tearDown(): void
    {
        wp_delete_post($this->testPostId, true);
    }
}
