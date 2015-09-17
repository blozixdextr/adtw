$(function(){

    $('a.confirm-delete').click(function(e){
        if (confirm('Are you sure?')) {
            return true;
        } else {
            e.preventDefault();
            return false;
        }
    });

});