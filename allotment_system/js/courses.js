$('.courseDependencyLink').click(function(e){
 e.preventDefault();
$(this).parent().find('.courseDependencies').toggle();
});