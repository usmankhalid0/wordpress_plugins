jQuery(document).ready(function ($) {
    $('#csv-data-uploader').on('submit', function (e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: cdu_object.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false, // ✅ THIS was incorrect before
            dataType: 'json',
            success: function (response) {
                console.log('Success:', response);
                alert(response.message);
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    });
});
