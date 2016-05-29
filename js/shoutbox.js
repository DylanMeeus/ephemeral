
shoutCount = 0;

/**
 * Fetches the data from the shoutbox upon initial load.
 */
function populateShoutbox()
{
    // check if the shoutbox exists (shoutbox is on the page)
    if($("#shoutbox").length)
    {
        ajaxShouts(); // ensure quicker loading at the start.
        // don't update when there are no new shouts!
        setInterval( () => {
            ajaxShouts();

        },10000);
    }
}

function ajaxShouts()
{
    $.ajax({
        type: "GET",
        url: "index.php?action=loadshouts",
        success : function (res){
            processShouts(res);
        },
        fail : function(res)
        {
            console.log(res);
        }
    });
}

function processShouts(res)
{
    let tableBody = $("#shoutboxtablebody");
    let shouts = JSON.parse(res);
    if(shouts.length > shoutCount)
    {
        // we only need the new shouts
        let newShouts = shouts.length - shoutCount;
        // update the length
        shoutCount = shouts.length;


        shouts = shouts.slice(-newShouts);

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


}


$("#shoutboxinput").keypress(function(key)
{
    let shoutboxInput = $("#shoutboxinput");
    let shoutboxInputVal = shoutboxInput.val();
    if(key.which == 13 && shoutboxInputVal){
        postMessage(shoutboxInputVal);
        shoutboxInput.val("");
    }
});

function postMessage(messageBody)
{
    $.ajax({
        type: "POST",
        url: "index.php?action=postshout",
        data: { "shout" : messageBody }, // notice I only need to pass the message body, the user we can fetch from the servlet.
        success: function (res) {
            //    postToSlack();
            }
        });

    // also post it to slack
}



// function to run after our div became visible.
$("#shoutbox").load(populateShoutbox());