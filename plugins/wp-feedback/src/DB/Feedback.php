<?php

namespace WPFB\DB;

use WPFB\Constants;

/**
 * Handles feedback management
 */
class Feedback {

    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . Constants::DB_NAME_FB_TRACKER;;
    }

    /**
     * Adds feedback for a post.
     *
     * This function inserts feedback data into the database table.
     *
     * @param int    $post_id      The ID of the post for which feedback is being added.
     * @param string $ip           The IP address of the user giving the feedback.
     * @param bool   $is_helpful   (Optional) Whether the feedback is helpful or not. Defaults to true.
     *
     * @throws \Exception When either post ID or IP address is empty, or if the user has already voted.
     *
     * @return bool True on successful insertion, false otherwise.
     */
    public function add_feedback(int $post_id, string $ip, bool $is_helpful = true)
    {
        if(!$post_id || !$ip) throw new \Exception("add_feedback requires a post ID and IP");

        if($this->has_voted($post_id, $ip)) throw new \Exception("Already voted");

        global $wpdb;

        $result = $wpdb->insert($this->table_name, [
            "content_id"    =>  $post_id,
            "is_helpful"    =>  $is_helpful,
            "ip"            =>  $ip
        ]);

        return $result;
        
    }

    /**
     * Retrieves feedback statistics for a post.
     *
     * This function fetches feedback statistics from the database table based on the provided post ID.
     *
     * @param int $post_id The ID of the post for which feedback statistics are being retrieved.
     *
     * @throws \Exception When the post ID is empty.
     *
     * @return array An associative array containing feedback statistics:
     *               - 'count_helpful': The count of helpful feedback.
     *               - 'count_not_helpful': The count of not helpful feedback.
     *               - 'count_total': The total count of feedback.
     */
    public function get_feedback(int $post_id): array
    {
        if(!$post_id) throw new \Exception("get_feedback requires a post ID");

        global $wpdb;

        $sql = "SELECT SUM(is_helpful = 1) AS count_helpful, 
            SUM(is_helpful = 0) as count_not_helpful,
            COUNT(*) as count_total
            FROM {$this->table_name} 
            WHERE content_id = %d
        ";

        $data = $wpdb->get_row(
            $wpdb->prepare($sql, $post_id)
        );

        return $this->calculate_percentages($data);
        
    }

    /**
     * Calculate percentages based on the data provided
     */
    public function calculate_percentages($data): array
    {
        if(!$data) return [];

        $total = $data->count_total;
        $total_helpful = ($data->count_helpful < 1 || $total < 1) ? 0 : round(($data->count_helpful / $total) * 100, 0);
        $total_unhelpful = $total < 1 ? 0 : 100 - $total_helpful;

        return [
            'total' =>  $total,
            'helpful'   =>  $total_helpful,
            'unhelpful' =>  $total_unhelpful
        ];

    }

    public function has_voted(int $post_id, string $ip): bool
    {
        if(!$post_id || !$ip) return false;

        global $wpdb;

        $exists = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) from {$this->table_name} WHERE content_id = %d AND ip = %s",
                $post_id, 
                $ip
            )
        );

        return boolval($exists);
    }

    public function get_vote_by_ip(int $post_id, string $ip)
    {
        if(!$post_id) throw new \Exception("get_vote_by_ip requires a post ID");

        global $wpdb;

        $sql = "SELECT * FROM {$this->table_name} WHERE content_id = %d AND ip = %s LIMIT 1";

        $data = $wpdb->get_row(
            $wpdb->prepare($sql, $post_id, $ip)
        );

        return $data;

    }

}