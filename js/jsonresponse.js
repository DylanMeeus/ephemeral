function showResponse(resultJson, outputDiv, dataDiv){

    var obj;
    var outputString = "";

    if($.type(resultJson) === "object"){
        obj = resultJson;
    }else{
        obj = $.parseJSON(resultJson);
    }

    if(obj.success){

        $(outputDiv).addClass("modal-positive-result");

    }else {

        $(outputDiv).addClass("modal-negative-result");

    }

    $.each(obj.messages, function (key, value) {

        outputString += value + "\n";

    });

    if($.type(dataDiv) !== "undefined"){
        if(dataDiv.length > 0){
            $(dataDiv).html(obj.data);
        }
    }

    $(outputDiv).html(outputString);

    return true;

}