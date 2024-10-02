$(document).ready(function() {
    var form = $('#myForm'); // contact form
    var submit = $('.submit-btn'); // submit button
    var alert = $('.alert-msg'); // alert div for showing messages

    // Form submit event
    form.on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        $.ajax({
            url: 'contact_process.php', // Path to your PHP mail handler
            type: 'POST', // Form submit method (POST)
            dataType: 'json', // Expect a JSON response from the PHP file
            data: form.serialize(), // Serialize form data
            beforeSend: function() {
                alert.fadeOut(); // Hide alert message before sending
                submit.html('Sending...'); // Change button text to indicate sending
                submit.prop('disabled', true); // Disable submit button during sending
            },
            success: function(response) {
                if (response.status === 'success') {
                    alert.html('<div class="alert alert-success">' + response.message + '</div>').fadeIn(); // Show success message
                    form.trigger('reset'); // Reset form after successful submission
                } else {
                    alert.html('<div class="alert alert-danger">' + response.message + '</div>').fadeIn(); // Show error message
                }
                submit.html('Send Message'); // Reset button text
                submit.prop('disabled', false); // Enable submit button again
            },
            error: function(err) {
                alert.html('<div class="alert alert-danger">There was an error sending your message. Please try again later.</div>').fadeIn();
                submit.html('Send Message'); // Reset button text
                submit.prop('disabled', false); // Enable submit button again
            }
        });
    });
});
