// JavaScript Document
// 
// HANDLOVANI FILTR AKCE -----------------------------------------------------------------------

var activeTag = '';

$(document).ready(function(){
    createByParams(activeTag, 0, 0);
//    console.log(activeTag);
    inicializaceObjekt.button_all();
    inicializaceObjekt.button_vzdel();
    inicializaceObjekt.button_zabav();
});


  function createByParams(tag, offsetFuture, offsetPast){
      $("#aktualniAkceFill").empty();
      $("#probehleAkceFill").empty();
      var url = 'php/getAkceContent.php';
      var posting = $.post( url, { myData: tag, offsetFuture : offsetFuture, offsetPast : offsetPast } );
      posting.done(function( data ) {
            data = data.data;
            if(data.status == "success"){
//                console.log(data);
                $("#aktualniAkceFill").html(data.htmlNewsFuture);
                $("#probehleAkceFill").html(data.htmlNewsPast);
                
            }
            else if(data.status == "error"){
//                console.log(data);
            }


        });
  }
  
  
  function changePage(isFuture, page){
      if(isFuture == 'true'){          
          var offset = (page - 1) * 2;
          createByParams(activeTag, offset, 0);
      }
      else{        
          var offset = (page - 1) * 2;
          createByParams(activeTag, 0, offset);
      }
  }
  
  
  var inicializaceObjekt = {
//    tableFuture: function () {
//        $('#tablePagin_future td').click(function(e){
//            var cell = $($(e.target).get(0)).text();
//            var page = parseInt(cell);
//            var offset = (page - 1) * 2;
//            createByParams(activeTag, offset, 0);
//        });
//    },
//    tablePast: function () {
//        $('#tablePagin_past td').click(function(e){
//            var cell = $($(e.target).get(0)).text();
//            var page = parseInt(cell);
//            var offset = (page - 1) * 2;
//            createByParams(activeTag, 0, offset);
//        });
//    },
    button_all: function () {
        $("#button_all").click(function(){
//          console.log(activeTag);
            if(activeTag !== ''){
                activeTag = '';
                createByParams(activeTag, 0, 0);
//                console.log(activeTag);
            }
        });
    },
    button_vzdel: function () {
        $("#button_vzdel").click(function(){
//            console.log(activeTag);
            if(activeTag !== 'vzdělávací'){
                activeTag = 'vzdělávací';
                createByParams(activeTag, 0, 0);
//                console.log(activeTag);
            }
        });
    },
    button_zabav: function () {
        $("#button_zabav").click(function(){
//            console.log(activeTag);
            if(activeTag !== 'zábavné'){
                activeTag = 'zábavné';
                createByParams(activeTag, 0, 0);
//                console.log(activeTag);
            }
        });
    }
};