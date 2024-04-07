<!-- Custom template for rendering the Public UI -->

<div class="wpfb-container" data-has-voted="<?= $has_voted ? 'true' : 'false'; ?>" data-post-id="<?= get_the_ID(); ?>">
    <div class="wpfb-title">
        <p><?= $has_voted ? "Thank you for your feedback." : "Was this article helpful?" ?></p>
    </div>
    <div class="wpfb-buttons">
        <button class="wpfb-button <?= $has_voted && $the_vote->is_helpful ? 'active' : ''; ?>" data-type="helpful">
            <i class="fa-solid fa-face-smile"></i>
            <span class="value"><?= $has_voted? "{$feedback['helpful']}%" : "Yes" ?></span>
        </button>
        <button class="wpfb-button <?= $has_voted && !$the_vote->is_helpful ? 'active' : ''; ?>"" data-type="unhelpful">
            <i class="fa-solid fa-face-meh"></i>
            <span class="value"><?= $has_voted? "{$feedback['unhelpful']}%" : "No" ?></span>
        </button>
    </div>
</div>
