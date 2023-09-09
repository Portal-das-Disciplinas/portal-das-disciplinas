function returnValue (object, value) {
    let p = object.slice(5);
    $("#"+p).html(value);
};