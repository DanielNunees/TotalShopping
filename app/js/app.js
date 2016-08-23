function loadjscssfile(filename, filetype){
    if (filetype=="js"){ //if filename is a external JavaScript file
        var fileref=document.createElement('script')
        fileref.setAttribute("type","text/javascript")
        fileref.setAttribute("src", filename)
    }
    else if (filetype=="css"){ //if filename is an external CSS file
        var fileref=document.createElement("link")
        fileref.setAttribute("rel", "stylesheet")
        fileref.setAttribute("type", "text/css")
        fileref.setAttribute("href", filename)
    }
    if (typeof fileref!="undefined")
        document.getElementsByTagName("head")[0].appendChild(fileref)
}

$(document).on("pageshow", "#all_departaments ",function(){
    //initialize swiper when document ready  
    $('.multiple-items').slick({
      infinite: false,
      slidesToShow: 3,
      slidesToScroll: 3,
      accessibility: true,
      arrows: false
    });       
  });

$(document).on("pageshow", "#home",function(){  
       $("#carousel-example-generic").swiperight(function() {  
          $(this).carousel('prev');  
          });  
       $("#carousel-example-generic").swipeleft(function() {  
          $(this).carousel('next');  
     });  
  });


  