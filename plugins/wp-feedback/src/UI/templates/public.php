<!-- Custom template for rendering the Public UI -->

<div class="wpfb-container" data-has-voted="<?= $has_voted ? 'true' : 'false'; ?>" data-post-id="<?= get_the_ID(); ?>">
    <div class="wpfb-title">
        <p><?= $has_voted ? __( 'Thank you for your feedback.', 'wp-feedback' ) : __( 'Was this article helpful?', 'wp-feedback' ) ?></p>
    </div>
    <div class="wpfb-buttons">
        <button class="wpfb-button <?= $has_voted && $the_vote->is_helpful ? 'active' : ''; ?>" data-type="helpful">
            <i class="fa-solid fa-face-smile"></i>
            <span class="value"><?= $has_voted? "{$feedback['helpful']}%" : __( 'Yes', 'wp-feedback' ) ?></span>
        </button>
        <button class="wpfb-button <?= $has_voted && !$the_vote->is_helpful ? 'active' : ''; ?>"" data-type="unhelpful">
            <i class="fa-solid fa-face-meh"></i>
            <span class="value"><?= $has_voted? "{$feedback['unhelpful']}%" : __( 'No', 'wp-feedback' ) ?></span>
        </button>
    </div>
</div>
