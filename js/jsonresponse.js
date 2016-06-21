function showResponse(resultJson, outputDiv, dataDiv){

    var outputString = "";
    var obj = determineJson(resultJson);

    if(obj.success){

        $(outputDiv).addClass("modal-positive-result");

    }else {

        $(outputDiv).addClass("modal-negative-result");

    }

    if(obj.messages){

        if($.isArray(obj.messages)){

            $.each(obj.messages, function (key, value) {

                outputString += value + "\n";

            });

        }else{
            outputString += obj.messages;
        }

    }

    if(dataDiv != null && dataDiv != false){
        $(dataDiv).html(obj.data);
    }

    $(outputDiv).html(outputString);

    return true;

}

function determineJson(json){

    if($.type(json) === "object"){
        return json;
    }else{
        return JSON.parse(json);
    }

}