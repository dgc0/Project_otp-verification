function getOTP() {
    $(".err").html("").hide();
    var number = $("#mobile").val();
    var name = $("#name").val();
    if (number.length == 10 && number != null) {
        var user_input = {
        "mobile_number" : number,
        "name" : name,
        "action" : "get_otp"
    };
    $('#loading-image').show();
    $.ajax({
        url : 'server.php',
        type : 'POST',
        data : user_input,
        success : function(response) {
          $(".container").html(response);
        },
        complete: function(){         
          $('#loading-image').hide();
        }
        });
        } 
        else {
            $(".err").html('Please enter a valid number!')
            $(".err").show();
        }
    }
    
    function verifyOTP() {
        $(".error").html("").hide();
        $(".success").html("").hide();
        var otp = $("#mobileOtp").val();
        var user_input = {
            "otp" : otp,
            "action" : "verify_otp"
        };
        if (otp.length == 6 && otp != null) {
            $('#loading-image').show();
            $.ajax({
             url : 'server.php',
             type : 'POST',
             dataType : "json",
             data : user_input,
             success : function(response) {
                $("." + response.type).html(response.message)
                $("." + response.type).show();
                $("#mobile-verification").html("").hide();
             },
             complete: function(){
                 $('#loading-image').hide();
             },
             error : function() {
                 alert("Error");
            }
            });
        } else {
            $(".err").html('You have entered wrong OTP.')
            $(".err").show();
        }
    }