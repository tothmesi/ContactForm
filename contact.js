$(document).ready(function () {

    $("#submit-email-btn").click(function () {
        var subject = $("input[name='subject']").val();
        var message = $("textarea[name='message']").val();
        var email = $("input[name='email']").val();

        $("#alert-container").empty().removeClass().addClass("alert");

        // ha a kliens oldali input validálás rendben volt, ajax POST
        if (validateText(subject) && validateText(message) && validateEmail(email)) {
            var request = $.ajax({
                url: "contact.php",
                type: "POST",
                data: { data: JSON.stringify({ email: email, subject: subject, message: message }) },
                dataType: "json"
            });

            // szerver oldali lefutás függvényében jön visszajelzés, ezt jeleníti meg a felületen
            request.done(function (data) {
                $("#alert-container").html(data.text).addClass(data.class);
            });

            // ajax hiba esetén
            request.fail(function (data) {
                $("#alert-container").html("Hiba történt a kiszolgálónál, próbálja újra!").addClass("alert-danger");
                console.log(data);
            });

            // kliens oldali validálás érvénytelen adatot talált
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

// letiltja a gombot, hogy ne spamelhessen több e-maillel
$(document).ajaxStart(function () {
    $("#loading-indicator").show();
    $("#submit-email-btn").prop("disabled", true);

// üríti az inputokat, engedélyezi a gombot
}).ajaxStop(function () {
    $("#loading-indicator").hide();
    $("#submit-email-btn").prop("disabled", false);
    $("#email").val("");
    $("#message").val("");
    $("#subject").val("");
});

function validateText(text) {
    return (text.trim() != "");
}

function validateEmail(text) {
    var regex = /(.+)@(.+)/;
    return (text.match(regex) != null);
}
