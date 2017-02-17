$(document).ready(function () {


    $("#submit-email-btn").click(function () {
        var subject = $("input[name='subject']").val();
        var message = $("textarea[name='message']").val();
        var email = $("input[name='email']").val();

        $("#alert-container").empty().removeClass().addClass("alert");

        if (validateText(subject) && validateText(message) && validateEmail(email)) {

            var request = $.ajax({
                url: "contact.php",
                type: "POST",
                data: { data: JSON.stringify({ email: email, subject: subject, message: message }) },
                dataType: "json"
            });

            request.done(function (data) {
                $("#alert-container").html(data.text).addClass(data.class);
            });

            request.fail(function () {
                $("#alert-container").html("Hiba történt a kiszolgálónál, próbálja újra!").addClass("alert-danger");
            });

        } else {
            var errorMsg = "<strong>There were errors in your form:</strong><br/>";

            if (!validateEmail(email))
                errorMsg += "E-mail address is missing or invalid.<br/>";
            if (!validateText(subject))
                errorMsg += "Subject is missing.<br/>";
            if (!validateText(message))
                errorMsg += "Message is missing.<br/>";

            $("#alert-container").html(errorMsg).addClass("alert-danger");
        }

    });

});

$(document).ajaxStart(function () {
    $("#loading-indicator").show();
    $("#submit-email-btn").prop("disabled", true);
    $("input").prop("disabled", true);
    $("textarea").prop("disabled", true);
}).ajaxStop(function () {
    $("#loading-indicator").hide();
    $("#submit-email-btn").prop("disabled", false);
    $("input").prop("disabled", false);
    $("textarea").prop("disabled", true);
});


function validateText(text) {
    return (text.trim() != "");
}

function validateEmail(text) {
    var regex = /(.+)@(.+)/;
    return (text.match(regex) != null);
}

//e-mail validálás működik
//helyretenni az alertet formailag
