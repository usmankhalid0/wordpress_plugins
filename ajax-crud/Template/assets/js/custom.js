jQuery(document).ready(function () {
    jQuery("#btn_submit").on("click", function (e) {
        e.preventDefault();

        var form = jQuery("#form_ajax").serialize() + '&action=my_action'; // Replace #your_form_id with your actual form ID

        jQuery.ajax({
            url: ajax_url,
            data: form,
            type: "POST",
            success: function (res) {
                // Handle success
                console.log("Success:", res);
            },
            error: function (res) {
                // Handle error
                console.log("Error:", res);
            }
        });
    });
});
