/**
 * Rame JS
 */


function rame() {
    
    this.api = function(request, $ele) {
        $.get(request, function(data) {
            $ele.fadeOut(200, function() {
                $ele.html(data).fadeIn(200);
            });
        });
    };
    
    this.delete = function(request) {
        $.ajax({
            url: request,
            type: 'DELETE',
            success: function(result) {
                document.location = document.location;
            }
        });
    };
    
}