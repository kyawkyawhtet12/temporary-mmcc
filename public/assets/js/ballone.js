$(document).ready(function() {

    $("input.fees").on("keypress", function(e) {
        let code = String.fromCharCode(e.keyCode);
        if (code.match(/^[a-z \s]/g)) return false;
    });

});
