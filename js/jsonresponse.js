function showResponse(resultJson, outputDiv, dataDiv){

    var outputString = "";
    var obj = determineJson(resultJson);

    if(obj.success){

        $(outputDiv).addClass("modal-positive-result");

    }else {

        $(outputDiv).addClass("modal-negative-result");

    }

    if(obj.messages){

        $.each(obj.messages, function (key, value) {

            outputString += value + "\n";

        });

    }

    if($.type(dataDiv) !== "undefined"){
        if(dataDiv.length > 0){
            $(dataDiv).html = obj.data;
        }
    }

    $(outputDiv).html(outputString);

    return true;

}

function determineJson(json){

    if($.type(json) === "object"){
        return json;
    }else{
        return $.parseJSON(json);
    }

}