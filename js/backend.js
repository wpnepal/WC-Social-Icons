(function ($) {
    $(function () {
    //all the backend js related with plugin is written here
    
    /* Making sortable */
      $('.wcsi-icons-wrap').sortable({
           handle:'.wcsi-drag-arrow' 
        }); 
        
    $('.wcsi-message .notice-dismiss').click(function(){
       $(this).parent().remove(); 
    });    
    
    });//document.ready close
}(jQuery));
