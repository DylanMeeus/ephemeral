$(document).ready(function(){

    // Extra scripts
    $.getScript("js/jsonresponse.js");

    /**
     * Globals
     */
    // Div containing the image
    var profileImage = $("#image-profile");
    // jCrop obj to return
    var jCropAPI;

    // Custom right click bindings
    $("#profile-picture").bind("contextmenu", function(e){

        // Stop the default menu from activating
        e.preventDefault();

        // Display the new menu
        $("#_contextmenu-profile-picture").finish().toggle(100).
            css({
                top: e.pageY,
                left: e.pageX
            });
    });

    // Opposite binds! (Close the context menus when the user clicks elsewhere)
    $(document).bind("mousedown", function(e){

        // Make sure the click wasn't inside a context menu
        if(!$(e.target).parents("._contextmenu").length > 0){
            // So we're here, that means we can hide all context menus
            $("._contextmenu").hide(100);
        }
    });

    // Context menu click assignments here
    $("._contextmenu li").click(function(){

        var avatar;
        avatar = $("#profile-picture").attr("src");

        switch($(this).attr("data-action")){

            //Change Profile Picture
            //Display the modal
            case "change-profile-picture":
                $("._contextmenu").hide(100);
                $("#profile-picture-modal").modal("show");
                break;
            case "cropped-profile-picture":
                $("._contextmenu").hide(100);
                window.open(avatar, "_blank");
                break;
        }
    });

    // Profile Picture stuff

    // Div containing the image
    var profileImage = $("#image-profile");

    // jCrop obj to return
    var jCropAPI;

    // When it is loaded
    /*profileImage.load(function(){
        setJCrop();
    });*/

    // Send the Coords and upload the new image
    $("#send-coords").click(function(){

        // Get width / height of the image
        var width = profileImage.width();
        var height = profileImage.height();

        // Set the width and height of the div, to send backend
        setOthers(width, height);

        $.ajax({
            type: "POST",
            url: "index.php?action=uploadprofilepicture",
            data: {
                coordString: $("#coords").text() + $("#coords2").text(),
                imgSrc: $(profileImage).attr("src")
            },
            success: function(response){

                var obj = determineJson(response);

                if(obj.success == false){
                    alert(obj.messages);
                    return false;
                }

                if(obj.data == "no-work") {
                    alert("Can not work with this image type, please try with another image");
                }else{

                    $("#profile-picture").attr("src", obj.data + "?" + new Date().getTime());

                    // Hide the modal
                    $("#profile-picture-modal").modal("hide");
                }
            }
        });
    })

    // Upload the image for cropping (Crop this Image!)
    $("#image-upload").click(function(){

        // File data
        var fileData = $("#image-select").prop("files")[0];

        // Set up a form
        var formData = new FormData();

        // Append the file to the new form for submission
        formData.append("file", fileData);

        // Send the file to be uploaded
        $.ajax({

            // Set the params
            cache: false,
            contentType: false,
            processData: false,

            // Page & file information
            url: "index.php?action=uploadimage",
            dataType: "text",
            type: "POST",

            // The data to send
            data: formData,

            // On success...
            success: function(response){

                // Manually get the json as we are using an image
                var obj = determineJson(response);

                // If no image was returned
                if(obj.messages == "not-image"){
                    alert("That's not an image, please upload an image file.");
                    return false;
                }

                // Reset the sizes of the profileImage
                $(profileImage).css("width", "");
                $(profileImage).css("height", "");

                // Else, load the image on to the page so we don't need to reload
                $(profileImage).attr("src", obj.data);

                // Initialise jCrop
                resetJCrop();
                setJCrop();

                //$("#image-profile").show();
                $("#send-coords").show();
            }
        })
    });

    //Change Password submission
    $('#changepassword').submit(function(e){
        e.preventDefault();

        $.ajax({
            url: "index.php?action=changepassword",
            type: "post",
            data: $("#changepassword").serialize(),
            success: function(data){
                if(data == "password_changed"){
                    var div = "#password-result";
                    displayResult(div, "Password changed successfully");
                }else if(data == "no_password_match"){
                    var div = "#password-result-negative";
                    displayResult(div, "Your new passwords do not match");
                }else if(data == "no_old_password_match"){
                    var div = "#password-result-negative";
                    displayResult(div, "Your old password is incorrect, please try again");
                }else{
                    alert("Password not changed, please try again.");
                };
            }
        });
    });

    // When the "Update Personal Message" form is sent
    $("#changepersonalmessage").submit(function(e){
        e.preventDefault();

        $.ajax({
            url: "index.php?action=changepersonalmessage",
            type: "post",
            data: $(this).serialize(),
            dataType: "json",
            success: function(response){
                if(response){
                    var resultDiv = "#pm-result";
                    showResponse(response, resultDiv, "#personal-message");
                    displayDiv(resultDiv)
                }
            }
        });
    });

    $("#changesignature").submit(function(e){
        e.preventDefault();

        $.ajax({
            url: "index.php?action=changesignature",
            type: "post",
            data: $(this).serialize(),
            success: function(response){
                if(response){
                    var resultDiv = "#signature-result";
                    showResponse(response, resultDiv, "#signature");
                    displayDiv(resultDiv);
                }
            }
        });
    });

    /**
     * FUNCTIONS
     */

    // Function to reset jCrop
    function resetJCrop(){
        if(jCropAPI){
            jCropAPI.destroy();
        }
    }

    // Set up the jCrop plugin
    function setJCrop(){

        // Set up the option to jCrop it
        $(profileImage).Jcrop({
            onSelect: setCoords,
            onChange: setCoords,
            setSelect: [0, 0, 51, 51],
            aspectRatio: 1,             // This locks it to a square image, so it fits the site better
        }, function(){jCropAPI = this});
    }

    //Set the coords with this method, that is called every time the user makes / changes a selection on the crop panel
    function setCoords(c){
        $("#coords").text(c.x + "," + c.y + "," + c.w + "," + c.h + ",");
    }

    //This one adds the other parts to the second div; they will be concatenated in to the POST string
    function setOthers(width, height, origWidth, origHeight){
        $("#coords2").text(width + "," + height);
    }

    //Function to show the password-result div
    function showDiv(div){
        $(div).slideDown(500);
    }

    //Function to close the password-result div
    function hideDiv(div){
        $(div).slideUp(500);
    }

    function displayDiv(div){
        showDiv(div);
        setTimeout(function(){hideDiv(div)}, 5000);
    }

    //Aaaand function to do everything for us
    function displayResult(div, ajaxReturn){
        $(div).text(ajaxReturn);
        showDiv(div);
        setTimeout(function(){hideDiv(div)}, 5000);
    }
})












