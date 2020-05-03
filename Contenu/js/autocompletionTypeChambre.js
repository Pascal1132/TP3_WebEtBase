$(function() {
    
    //autocompletion 
    $("#type").autocomplete({
        source: "index.php?action=autocompletionType",
        minLength: 1
    });                

});