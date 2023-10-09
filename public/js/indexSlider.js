// function returnValue (object, value) {
//     let p = object.slice(5);
//     $("#"+p).html(" > " + value + "%");
// };

function handleInput(object, value) {
    let p = object.slice(5);
    let aux = 100 - parseInt(value);
    $("#"+p).html(value + "% ou +");
    $("#"+"-"+p).html(aux + "% ou -");
}