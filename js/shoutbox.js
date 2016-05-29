/**
 * Fetches the data from the shoutbox upon initial load.
 */
function populateShoutbox()
{
    // check if the shoutbox exists (shoutbox is on the page)
    if($("#shoutbox").length)
    {
        // get the table div

        let tableBody = $("#shoutboxtablebody");

        $.ajax({
            type: "GET",
            url: "index.php?action=loadshouts",
            success : function (res){
                console.log("result: " + res);

                let shouts = JSON.parse(res);
                console.log(shouts);

                shouts.forEach(shout =>
                {
                    let tablerow = document.createElement("tr");
                    let userColumn = document.createElement("td");
                    userColumn.className += "col-xs-4 col-md-2";
                    let userDiv = document.createElement("div");
                    userDiv.className = "pull-right";
                    let messageColumn = document.createElement("td");
                    messageColumn.className += "col-xs-14 col-md-10";
                    let messageDiv = document.createElement("div");
                    userDiv.innerHTML = shout['username'];
                    messageDiv.innerHTML = shout['message'];

                    userColumn.appendChild(userDiv);
                    messageColumn.appendChild(messageDiv);

                    tablerow.appendChild(userColumn);
                    tablerow.appendChild(messageColumn);
                    tableBody.append(tablerow);
                });
            }
        });
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