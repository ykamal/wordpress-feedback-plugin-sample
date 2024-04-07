jQuery(document).ready(function ($) {
	$(".wpfb-button").on("click", function () {
		const btn = $(this);
		const container = btn.closest(".wpfb-container");
		const hasVoted = container.data("has-voted");
		const postId = btn.closest(".wpfb-container").data("post-id");

		if (!hasVoted) {
			$(".wpfb-button").attr("disabled", true);

			$.ajax({
				url: wpfb_params.ajax_url,
				type: "POST",
				data: {
					action: "wpfb_add_feedback",
					data: {
						postId,
						isHelpful: btn.data("type") === "helpful",
					},
				},
				success: function (response) {
					const {
						success,
						data: { feedback },
					} = response;

					console.log({ success, feedback });

					if (success) {
						container.data("has-voted", true);

						$('.wpfb-button[data-type="helpful"] span.value').text(
							feedback.helpful
						);
						$(
							'.wpfb-button[data-type="unhelpful"] span.value'
						).text(feedback.unhelpful);

						$(".wpfb-title").text("Thank you for your feedback.");

						btn.addClass("active");
					}
				},
				error: function (xhr, status, error) {
					console.error(xhr.responseText);
				},
				complete: function () {
					$(".wpfb-button").attr("disabled", false);
				},
			});
		}
	});
});
