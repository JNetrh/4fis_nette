var activeTag = 'all';
  //inicializace stránky při onload
  $(document).ready(function(){
      createByParams(activeTag, 0, 0, function(){
          console.log(activeTag);
          inicializaceObjekt.tableFuture();
          inicializaceObjekt.tablePast();
          inicializaceObjekt.button_all();
          inicializaceObjekt.button_vzdel();
          inicializaceObjekt.button_zabav();
      });      
  });
  
var inicializaceObjekt = {
    tableFuture: function () {
        $('#tablePagin_future td').click(function(e){
            var cell = $($(e.target).get(0)).text();
            var page = parseInt(cell);
            var offset = (page - 1) * 2;
            createByParams(activeTag, offset, 0, function(){});
        });
    },
    tablePast: function () {
        $('#tablePagin_past td').click(function(e){
            var cell = $($(e.target).get(0)).text();
            var page = parseInt(cell);
            var offset = (page - 1) * 2;
            createByParams(activeTag, offset, 0, function(){});
        });
    },
    button_all: function () {
        $("#button_all").click(function(){
          console.log(activeTag);
            if(activeTag !== 'all'){
                createByParams(activeTag, 0, 0, function(){});
                activeTag = 'all';
                console.log(activeTag);
            }
        });
    },
    button_vzdel: function () {
        $("#button_vzdel").click(function(){
            console.log(activeTag);
            if(activeTag !== 'vzdělávací'){
                createByParams(activeTag, 0, 0, function(){});
                activeTag = 'vzdělávací';
                console.log(activeTag);
            }
        });
    },
    button_zabav: function () {
        $("#button_zabav").click(function(){
            console.log(activeTag);
            if(activeTag !== 'zábavné'){
                createByParams(activeTag, 0, 0, function(){inicializeHandlers();});
                activeTag = 'zábavné';
                console.log(activeTag);
            }
        });
    }
};
  
  
  

  
  function createByParams(tag, offsetFuture, offsetPast, callback){
      $("#aktualniAkceFill").empty();
      $("#probehleAkceFill").empty();
      var url = 'php/getAkceContent.php';
      var posting = $.post( url, { myData: tag, offsetFuture : offsetFuture, offsetPast : offsetPast } );
      posting.done(function( data ) {
            data = data.data;
            if(data.status == "success"){
                console.log(data);
                var futureNews = data.payload.futureNews;
                var pastNews = data.payload.pastNews;

                futureNews.forEach(showNews);
                var htmlPaginFuture = createPagination('future', data.payload);
                pastNews.forEach(showNews);
                var htmlPaginPast = createPagination('past', data.payload);

                $("#aktualniAkceFill").html(getContent("aktualniAkceFill") + htmlPaginFuture);
                $("#probehleAkceFill").html(getContent("probehleAkceFill") + htmlPaginPast);
                callback();
            }
            else if(data.status == "error"){
                console.log(data);
            }


        });
  }
  
  function showNews(item, index){
      var divLabel = "";
      if(item.isFuture){
          divLabel = '<div class="divAkceLabel bg-oranzova"> ';
      }
      else{
          divLabel = '<div class="divAkceLabel bg-seda"> ';
      }

      var divResponsive = '<div class="col-lg-6 col-md-6 col-sm-12"> ',
          divOuterBanner = '<div class="divAkceBanner',
          divImg = '<div class="divAkceImg"> ',
          emLabel = '<em class="emAkceLabel"> ',
          date = formatDate(new Date(item.time)),
          a = '<a href="' + item.odkaz +'">',
          img = '<img class="imgAkceBanner" src="' + item.img + '" alt="' + item.alt + '"/> ',
          cDiv = '</div> ',
          cA = '</a> ',
          cEm = '</em> ',
          dash = ' &dash; ';

      //inicializace html
      var html = "";
      //začátek html contentu
      html += divResponsive + divOuterBanner;

      //okno bude vlevo nebo vpravo L/R
      if(index % 2 == 0){
          html += 'L">';
      }
      else{
          html += 'R">';
      }

      //dokončení html contentu
      html += divLabel + emLabel + a + item.caption + dash + date + cA + cEm + cDiv + divImg + a + img + cA + cDiv + cDiv + cDiv;


      //vyplňujeme buď aktuální (#aktualniAkceFill) nebo probéhlé akce ("#probehleAkceFill")
      if(item.isFuture){
          $("#aktualniAkceFill").html(getContent("aktualniAkceFill") + html);
      }
      else{
          $("#probehleAkceFill").html(getContent("probehleAkceFill") + html);
      }


  }

  function getContent(elementId){
      return $("#" + elementId).html();
  }

  function formatDate(date) {
      var day = date.getDate();
      var monthIndex = date.getMonth() + 1;
      var year = date.getFullYear();

      return day + '. ' + monthIndex + '. ' + year;
  }


  function createPagination(type, data){
      var newsCount = 0,
          offset = 0;
      if(type === 'future'){
          newsCount = data.countFuture;
          offset = data.offsetFuture;
      }
      else if(type === 'past'){
          newsCount = data.countPast;
          offset = data.offsetPast;
      }

      var begin = '<div class="col-lg-12 col-md-12 col-sm-12"> <div class="divPagination inContainer"> <table id="tablePagin_' + type + '" class="tablePaginDemo"> <tr> ',
          end = '</td> </tr> </table> </div> </div> ',
          tdPagin = '<td class="tdPaginDemo',
          aTag = '<a class="',
//          aHref = 'href="./akce.php?offsetFutre=',
          aHref = 'href="#?offsetFutre=',
          hrefDiv = '#aktualniAkce">',
          cA = '</a>';

      var paginHtml = "";

      paginHtml += begin;

       for(var i = 1; i<=Math.ceil(newsCount/2); i++){
           if(offset/2+1 == i){
               paginHtml += tdPagin + 'A"> ';
               paginHtml += aTag + 'aPaginA" ';
           }
           else{
               paginHtml += tdPagin + '"> ';
               paginHtml += aTag + 'aPagin" ';
           }

//           paginHtml += aHref + (i-1)*2 + hrefDiv + i + cA;
           paginHtml += aHref + (i-1)*2 + hrefDiv + i + cA;
       }

      paginHtml += end;
      return paginHtml;
  }