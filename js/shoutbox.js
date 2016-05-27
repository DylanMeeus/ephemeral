/**
 * Fetches the data from the shoutbox upon initial load.
 */
function populateShoutbox()
{
    console.log("alive!");
}

$("#shoutboxinput").keypress(function(key)
{
    // 13 == Enter
    if(key.which == 13){
        console.log("User pressed enter!");
    }
});