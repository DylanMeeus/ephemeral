/**
 * Fetches the data from the shoutbox upon initial load.
 */
function populateShoutbox()
{
    // check if the shoutbox exists (shoutbox is on the page)
    if($("#shoutbox").length)
    {

        $.ajax({
            type: "GET",
            url: "index.php?action=loadshouts",
            success : function (res){
                console.log("result: " + res);
            }
        })

    }
}

$("#shoutboxinput").keypress(function(key)
{
    // 13 == Enter
    console.log("pressed the shoutbox!");
    var shoutboxInput = $("#shoutboxinput").val();
    if(key.which == 13 && shoutboxInput){
        postMessage(shoutboxInput);
    }
});

function postMessage(messageBody)
{
    console.log("posting message: " + messageBody);
    $.ajax({
        type: "POST",
        url: "index.php?action=postshout",
        data: { "shout" : messageBody }, // notice I only need to pass the message body, the user we can fetch from the servlet.
        success: function (res) {
                console.log(res);
            }
        });
    console.log("message posted!");
}


// function to run after our div became visible.
$("#shoutbox").load(populateShoutbox());