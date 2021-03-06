// JavaScript Document
//---------------MENU----------------------

function drzAktivni(aktivniMenu)
{
  document.getElementById("liMenu"+aktivniMenu).className = "liMenuA"; 
}

function scrollMain(){
   
   

}

function popUpWindow() {
    var date = Date.now();

    if (typeof(Storage) !== "undefined") {
        var lastSeen = localStorage.getItem("lastSeen");
        if(lastSeen){
            var elapsed = date - lastSeen;
            var difference = new Date(elapsed);
            var diff_hours = difference.getHours();
            var diff_mins = difference.getMinutes();
            if (diff_hours > 22) {
                scrolling(false);
                $("#popUp").show();
                localStorage.setItem("lastSeen", date);

            }

        }
        else {
            scrolling(false);
            var popUp = $("#popUp").show();
            localStorage.setItem("lastSeen", date);

        }

    } else {
        console.log('no localStorage supported')
    }

}

function closePopUpWindow() {
    scrolling(true);
    $("#popUp").hide();
}

function ukazMenu()
{
    var display =  document.getElementById("mobileMenu").style.display;
    console.log(display);
    if (display == "none" || display == "") {
      $( "#mobileMenu" ).show();
    } else{
      $( "#mobileMenu" ).hide();
    }
}

function nahrajDataONas(){
    var globalData;
    
    this.doPost = function(callback){
        var url = 'spravaWebu/php/process/getUserProcess.php';
        var posting = $.post( url, { myData: "all", site: "onas" } );
        
        // Put the results in a div
        posting.done(function( data ) {
            data = data.data;
            globalData = data;
            if(data.status == "success"){
                callback(data);
            }

        });
    };
    this.getPopUpById = function (id){
        var popUp = globalData.personMetadata[id];
        return popUp;
    };
}

function showPersonInfo(osoba)
{
  var dataOnas = new nahrajDataONas();
  dataOnas.doPost(function(){
      $("#personPopUpContent").empty();
      $("#personPopUpContent").html(dataOnas.getPopUpById(osoba));
      $("#personPopup").show();
  });
  
  scrolling(false);
}
function hidePersonInfo()
{
  $("#personPopup").hide();
  scrolling(true);
}

function showNameOpen(osoba)
{
  var cislo = osoba.toString();
  $("#" + cislo).show();
}

function showNameClose()
{
  
//  for(i=0; i < 10; i++){
//    var cislo = i.toString();
//    $("#person" + cislo).hide();
//  }
  $("div.person").hide();
}

function scrolling(floater)
{
      var top = $(window).scrollTop();
      var left = $(window).scrollLeft();
      if(!floater){
        $('body').css('overflow', 'hidden');
        $(window).scroll(function(){
          $(this).scrollTop(top).scrollLeft(left);
        });
      } else {
        $('body').css('overflow', 'auto');
        $(window).unbind('scroll');
      }

}

//může se smazat:
function getContentByPerson(personId){
    var mainText = "";
    var oborText = "";
    var jmenoText = "";
    var imgText = "";
    if(personId == 0 ){
        mainText = "Studuji obor Statistika v&nbsp;posledním ročníku a&nbsp;jako vedlejší specializaci Datové inženýrství. Jsem vášnivý cyklista a&nbsp;lyžař, v&nbsp;létě si&nbsp;zajedu rád na&nbsp;vodu, zahraji si&nbsp;tenis či&nbsp;cestuji.";
        oborText = "5. ročník - Statistika";
        jmenoText = "Vítek Hanzal";
        imgText = "vitek_hanzal2";}
    else if(personId == 1){
        mainText = "Zajímám se o&nbsp;programování, manažerské dovednosti, deskové hry, čtení, tance, kaligrafii, párty. Vždycky jsem&nbsp;chtěl pomáhat lidem kolem sebe. Ctím VŠE a&nbsp;chci aby na ní byli hrdí i&nbsp;její studenti a&nbsp;miluji párty. Toto se&nbsp;mi díky 4FISu povedlo. Dávam možnost se&nbsp;kamarádům/spolužákům vzdělat, užít&nbsp;si neskutečnou zábavu a&nbsp;dostat zajímavé příležitosti. Díky tomu jsou studenti FISu lepší&nbsp;- znají se, spolupracují, mají větší rozhled a&nbsp;samožřejmě, mají šanci to&nbsp;dotáhnout dál. No&nbsp;a&nbsp;kdo proslaví VŠE nejvíc? No&nbsp;přeci úspěšní lidé. ";
        oborText = "5. ročník - Informační systémy a technologie";
        jmenoText = "Jan Černý";
        imgText = "jan_cerny";}
    else if(personId == 2){
        mainText = "Mám ráda tanec, výlety do&nbsp;přírody, hraní deskových her, čtení, psaní, seberozvoj. Nikdy jsem neměla velké ambice, ani cíle, nikdy jsem&nbsp;si moc nevěřila a&nbsp;nových věcí se&nbsp;bála. Náhodou jsem&nbsp;se dostala do&nbsp;4FIS, a&nbsp;nyní, ačkoli mi&nbsp;není příjemné něco říkat většímu množství lidí, moje příspěvky jich čtou desítky. Nakonec dělám, co&nbsp;mám ráda - píšu. Chci dělat svět kolem sebe lepší a&nbsp;pomáhání ostatním ať&nbsp;už&nbsp;prostřednictvím 4FIS nebo jinak, k&nbsp;tomu určitě patří. Jaký svět si&nbsp;vytvoříme, v&nbsp;takovém budeme žít.";
        oborText = "Dostudováno - Ekonometrie a operační výzkum";
        jmenoText = "Zuzka Somolová";
        imgText = "zuzana_somolova";}
    else if(personId == 3){
        mainText = "Jsem cílevědomý a&nbsp;ambiciózní člověk, který si&nbsp;jde za svými sny. Rád sportuju, hrál&nbsp;jsem 12 let tenis a&nbsp;také se&nbsp;rád všemožně vzdělávám. Baví&nbsp;mě poznávat nové věci a&nbsp;to ať&nbsp;gastronomické tak&nbsp;kulturní. Baví mě být ve&nbsp;fakultním spolku a&nbsp;rozvíjet fakultu. Společně s&nbsp;mým týmem jsme pro&nbsp;prváky připravili příručky a&nbsp;chystáme navazující projekt kruhů. ";
        oborText = "2.ročník - Informační média a služby";
        jmenoText = "Adam Dolejš";
        imgText = "adam_dolejs";}
    else if(personId == 4){
        mainText = "Mezi mé&nbsp;nejoblíbenější aktivity patří Hiking, volejbal, čtení, organizování&nbsp;(čehokoli!). Jsem&nbsp;typ holky, která přesvědčuje ostatní slečny, aby se&nbsp;nebály informatiky. Že&nbsp;počítače nekoušou a&nbsp;ano, že občas to&nbsp;stojí hodně úsilí, ale&nbsp;že je&nbsp;to hlavně zábava a&nbsp;především, že&nbsp;informatika není jen programování, ale&nbsp;obrovská škála činností. Protože ráda píšu, blogování a&nbsp;podobné činnosti mi nejsou vůbec cizí, proto byly příručky pro&nbsp;prváky jasnou volbou, co&nbsp;dělat&nbsp;ve 4FISu - můžu psát a&nbsp;hlavně pomáhat lidem, aby&nbsp;nebyli ztraceni v&nbsp;prvních dnech školy.";
        oborText = "3.ročník - Aplikovaná Informatika VŠE";
        jmenoText = "Jája Nyklíčková";
        imgText = "jarmila_nyklickova_1";}
    else if(personId == 5){
        mainText = "Zajímám&nbsp;se o&nbsp;filmy, sporty (volejbal,&nbsp;kolo), posezení s&nbsp;přáteli, počítačové hry a&nbsp;cestování.Mám velice rád filmy a&nbsp;čas od&nbsp;času rád vypnu z&nbsp;hektického světa v&nbsp;podobě jízdy na kole, nebo volejbalu. Jsem člověk, který nerad mluví před mnoha lidmi, ale 4FIS mi&nbsp;v&nbsp;tomto ohledu neuveřitelně pomohl se&nbsp;zlepšit. 4Fisáci mi&nbsp;pomohli sám sebe zdokonalit v&nbsp;mnoha ohledech a&nbsp;věřím, že ještě pomůžou.";
        oborText = "2.ročník - Statistické metody v ekonomii";
        jmenoText = "Dominik Sagl";
        imgText = "dominik_sagl";}
    else if(personId == 6){
        mainText = "Baví&nbsp;mě tančit, navštěvovat divadla, být kreativní a tvořit, vařit dobré jídlo a cestovat. Jsem&nbsp;estét a&nbsp;snílek, který se&nbsp;ocitl ve&nbsp;světě informací a&nbsp;logiky. I&nbsp;když to&nbsp;může znít zvláštně, mého rozhodnutí jít na&nbsp;FIS nelituji. Během dvou let, co&nbsp;na&nbsp;FISu studuji, jsem se&nbsp;potkala se&nbsp;spoustou inspirativních a&nbsp;cílevědomých lidí, kteří mě&nbsp;svým myšlením obohatili. Proto mě&nbsp;hodně těší, že&nbsp;vznikají studentské spolky, které pomáhají studentům se&nbsp;navzájem poznat, něco spolu budovat a&nbsp;rozvíjet&nbsp;se.";
        oborText = "3.ročník - Aplikovaná Informatika VŠE";
        jmenoText = "Denisa Tomanová";
        imgText = "denisa_tomanova";}
    else if(personId == 7){
        mainText = "K&nbsp;informatice jsem se&nbsp;dostal vlastně náhodou, ale nakonec jsme&nbsp;si skoro i&nbsp;padli do&nbsp;oka. Na&nbsp;VŠE se&nbsp;mi náramně zalíbilo a&nbsp;podařilo se&nbsp;mi úspěšně prokousat do&nbsp;druháku. Mám rád sport a&nbsp;cestování. Mým velkým koníčkem&nbsp;je také dobré jídlo, pití a&nbsp;společnost. Právě kvůli dobré společnosti jsem se&nbsp;rozhodl přidat k&nbsp;4FISu. Starám se o&nbsp;nevzdělváácí akce a&nbsp;snažím&nbsp;se jak o&nbsp;propagaci spolku veřejnosti, tak o&nbsp;posílení vztahů uvnitř něj.";
        oborText = "2.ročník - Aplikovaná Informatika VŠE";
        jmenoText = "Štěpán Staněk";
        imgText = "stepan_stanek";
    }
    else if(personId == 8){
        mainText = "Baví mě dělat věci, které mi&nbsp;přijdou smysluplné a&nbsp;mají nějaký přínos. Na&nbsp;VŠE studuji Aplikovanou informatiku a&nbsp;musím přiznat, že&nbsp;jsem narazil jak na&nbsp;suprové, tak&nbsp;mizerné předměty. Každý si&nbsp;ale může vybrat to&nbsp;své, a&nbsp;pokud přijmete, že v&nbsp;životě je nutné se&nbsp;učit i&nbsp;věci, které vás tolik nebaví, můžete si&nbsp;hodně odnést. Mám vedle školy plno volného času, takže není problém pracovat nebo se&nbsp;rozvíjet jiným způsobem. Baví mě kódit dlouho do&nbsp;noci, protože to se&nbsp;mi&nbsp;pracuje ze&nbsp;všeho nejlépe. A&nbsp;finální pocit, když to&nbsp;najednou začne fungovat, je k&nbsp;nezaplacení.";
        oborText = "3.ročník - Aplikovaná Informatika VŠE";
        jmenoText = "Jakub Netrh";
        imgText = "jakub_netrh";}
    
    
    var finalString = "<div class=\"col-lg-3 col-md-4 col-xs-4\"><div class=\"divSingleFoto\"><img class=\"imgSingleFoto\" src=\"img/clenove/" + imgText + ".jpg\" alt=\"" + jmenoText + "\"/></div></div><div class=\"col-lg-9 col-md-8 col-xs-8\"><h3>" + jmenoText + "</h3><label>" + oborText + "</label></div><div class=\"col-xs-12\"><p class=\"pPopisOsoby\">" + mainText + "</p></div>";
    
    return finalString;
}

// PREPINANI AKCE (ZABAVNE/VZDELAVACI) ---------------------------------------------------------

$("#akce_0").click(function(){ //IBM
    $("#akce_1").removeClass("divButtonAkceA");
    $("#akce_2").removeClass("divButtonAkceA");
    $("#akce_1").addClass("divButtonAkce");
    $("#akce_2").addClass("divButtonAkce");
    
    $("#akce_1 span").removeClass("spanAkceButtonA");
    $("#akce_2 span").removeClass("spanAkceButtonA");
    $("#akce_1 span").addClass("spanAkceButton");
    $("#akce_2 span").addClass("spanAkceButton");

    
    $("#akce_0").removeClass("divButtonAkce");
    $("#akce_0").addClass("divButtonAkceA");
    
    $("#akce_0 span").removeClass("spanAkceButton");
    $("#akce_0 span").addClass("spanAkceButtonA");

});
$("#akce_1").click(function(){
    $("#akce_0").removeClass("divButtonAkceA");
    $("#akce_2").removeClass("divButtonAkceA");
    $("#akce_0").addClass("divButtonAkce");
    $("#akce_2").addClass("divButtonAkce");
    
    $("#akce_0 span").removeClass("spanAkceButtonA");
    $("#akce_2 span").removeClass("spanAkceButtonA");
    $("#akce_0 span").addClass("spanAkceButton");
    $("#akce_2 span").addClass("spanAkceButton");

    $("#akce_1").removeClass("divButtonAkce");
    $("#akce_1").addClass("divButtonAkceA");
    
    $("#akce_1 span").removeClass("spanAkceButton");
    $("#akce_1 span").addClass("spanAkceButtonA");

});
$("#akce_2").click(function(){ //IBM
    $("#akce_1").removeClass("divButtonAkceA");
    $("#akce_0").removeClass("divButtonAkceA");
    $("#akce_1").addClass("divButtonAkce");
    $("#akce_0").addClass("divButtonAkce");
    
    $("#akce_1 span").removeClass("spanAkceButtonA");
    $("#akce_0 span").removeClass("spanAkceButtonA");
    $("#akce_1 span").addClass("spanAkceButton");
    $("#akce_0 span").addClass("spanAkceButton");

    $("#akce_2").removeClass("divButtonAkce");
    $("#akce_2").addClass("divButtonAkceA");
    
    $("#akce_2 span").removeClass("spanAkceButton");
    $("#akce_2 span").addClass("spanAkceButtonA");

});
// ZAROVNANI MAIN ------------------------------------------------------------------------------


function zarovnejUvitani(){
    var vyskaOkna = window.innerHeight;
//    var vyskaOkna = document.getElementById("imgNahled").offsetHeight;
    var sirkaOkna = window.innerWidth;
    var mainContent = document.getElementById("mainContent");
    var widthNadpis = document.getElementById("h1Spolek").offsetWidth;
    
    mainContent.style.height = (vyskaOkna / 2.7) + "px";
    mainContent.style.top = ((vyskaOkna / 100) * 34) + "px";
    mainContent.style.width = widthNadpis + 5 + "px";
    mainContent.style.left = ((sirkaOkna - widthNadpis) / 2) + "px";
//    console.log(widthNadpis);
    
}


// UVODNI OBRAZEK -------------------------------------------------------------------------------
function nastavMainFoto(nazevStranky)
{
    var nazev = nazevStranky;
    var sirkaOkna = window.innerWidth;
    var vyskaOkna = window.innerHeight; 
    
    
    
    var sipka = document.getElementById("imgMainSipka");
    sipka.style.top = ((vyskaOkna / 100)* 85) + "px";
    sipka.style.left = ((sirkaOkna - ((sirkaOkna / 100) * 3)) / 2) + "px";
    

//    document.getElementById("divMainImg").style.width = sirkaOkna + 'px';
    document.getElementById("divMainImg").style.height = vyskaOkna + 'px';

    var pomer = sirkaOkna / vyskaOkna;

//    var popis1 = document.getElementById("divPopSl1").innerHTML;
//    var popis2 = document.getElementById("divPopSl2").innerHTML;
//    var popis3 = document.getElementById("divPopSl3").innerHTML;
//    var popis4 = document.getElementById("divPopSl4").innerHTML;
//    var popis5 = document.getElementById("divPopSl5").innerHTML;
//
//    var odkaz1 = document.getElementById("divOdkSl1").innerHTML;
//    var odkaz2 = document.getElementById("divOdkSl2").innerHTML;
//    var odkaz3 = document.getElementById("divOdkSl3").innerHTML;
//    var odkaz4 = document.getElementById("divOdkSl4").innerHTML;
//    var odkaz5 = document.getElementById("divOdkSl5").innerHTML;

    if (pomer <= 1.78)
    {
        if (vyskaOkna < 603)
        {
            document.getElementById("divMainImg").innerHTML = '<img class="imgMain" src="img/' + nazev + '_800.jpg" alt="náhled" />';
//            document.getElementById("divSlSirka").style.width = "800px";
        } else if (vyskaOkna >= 603 && vyskaOkna < 670)
        {
            document.getElementById("divMainImg").innerHTML = '<img class="imgMain" src="img/' + nazev + '_1000.jpg" alt="náhled" />';
//            document.getElementById("divSlSirka").style.width = "1000px";

        } else if (vyskaOkna >= 670 && vyskaOkna < 871)
        {
            document.getElementById("divMainImg").innerHTML = '<img class="imgMain" src="img/' + nazev + '_1300.jpg" alt="náhled" />';
//            document.getElementById("divSlSirka").style.width = "1300px";
        } else if (vyskaOkna >= 871 && vyskaOkna < 938)
        {
            document.getElementById("divMainImg").innerHTML = '<img class="imgMain" src="img/' + nazev + '_1400.jpg" alt="náhled" />';
//            document.getElementById("divSlSirka").style.width = "1400px";
        } 
        else if (vyskaOkna >= 938 && vyskaOkna < 1099)
        {
            document.getElementById("divMainImg").innerHTML = '<img class="imgMain" src="img/' + nazev + '_1640.jpg" alt="náhled" />';
//            document.getElementById("divSlSirka").style.width = "1640px";
        }
        else if (vyskaOkna >= 1099 && vyskaOkna < 1336)
        {
            document.getElementById("divMainImg").innerHTML = '<img class="imgMain" src="img/' + nazev + '_2000.jpg" alt="náhled" />';
//            document.getElementById("divSlSirka").style.width = "1640px";
        } 
        else if (vyskaOkna >= 1336 && vyskaOkna < 1752)
        {
            document.getElementById("divMainImg").innerHTML = '<img class="imgMain" src="img/' + nazev + '_2600.jpg" alt="náhled" />';
//            document.getElementById("divSlSirka").style.width = "1640px";
        } 
        else
        {
            document.getElementById("divMainImg").innerHTML = '<img class="imgMain" src="img/' + nazev + '.jpg" alt="náhled" />';
//            document.getElementById("divSlSirka").style.width = "2000px";
        }
    } else
    {
        if (sirkaOkna < 900)
        {
            document.getElementById("divMainImg").innerHTML = '<img class="imgMain" src="img/' + nazev + '_800.jpg" alt="náhled" />';
//            document.getElementById("divSlSirka").style.width = "800px";
        } else if (sirkaOkna >= 900 && sirkaOkna < 1000)
        {
            document.getElementById("divMainImg").innerHTML = '<img class="imgMain" src="img/' + nazev + '_1000.jpg" alt="náhled" />';
//            document.getElementById("divSlSirka").style.width = "1000px";
        } else if (sirkaOkna >= 1000 && sirkaOkna < 1300)
        {
            document.getElementById("divMainImg").innerHTML = '<img class="imgMain" src="img/' + nazev + '_1300.jpg" alt="náhled" />';
//            document.getElementById("divSlSirka").style.width = "1300px";
        } 
        else if (sirkaOkna >= 1300 && sirkaOkna < 1400)
        {
            document.getElementById("divMainImg").innerHTML = '<img class="imgMain" src="img/' + nazev + '_1400.jpg" alt="náhled" />';
//            document.getElementById("divSlSirka").style.width = "1400px";
        } 
        else if (sirkaOkna >= 1400 && sirkaOkna < 1640)
        {
            document.getElementById("divMainImg").innerHTML = '<img class="imgMain" src="img/' + nazev + '_1640.jpg" alt="náhled" />';
//            document.getElementById("divSlSirka").style.width = "1640px";
        } 
        else if (sirkaOkna >= 1640 && sirkaOkna < 2000)
        {
            document.getElementById("divMainImg").innerHTML = '<img class="imgMain" src="img/' + nazev + '_2000.jpg" alt="náhled" />';
//            document.getElementById("divSlSirka").style.width = "1640px";
        } 
        else if (sirkaOkna >= 2000 && sirkaOkna < 2600)
        {
            document.getElementById("divMainImg").innerHTML = '<img class="imgMain" src="img/' + nazev + '_2600.jpg" alt="" />';
//            document.getElementById("divSlSirka").style.width = "1640px";
        } 
        else
        {
            document.getElementById("divMainImg").innerHTML = '<img class="imgMain" src="img/' + nazev + '.jpg" alt="náhled" />';
//            document.getElementById("divSlSirka").style.width = "2000px";
        }

    }
    
    
    
    
//    document.getElementById("divMainImg").style.width = sirkaOkna + "px";
//    document.getElementById("sliderFrame").style.visibility = 'visible';
}

// ANIMACE  -------------------------------------------------------------------------------------


function spustAnimaci()
{
  document.getElementById("divVyberLevy").className = "divAnimace";
  document.getElementById("divVyberText").className = "divAnimace";
  document.getElementById("divVyberPravy").className = "divAnimace";
  document.getElementById("divVybFb").className = "divAnimace";
  document.getElementById("imgLogoVyber").className = "imgLogoAnimace";
}

function aktivniLeva()
{
  document.getElementById("divVyberLevy").style.backgroundImage = "url('img/eshopA.jpg')";
}
function pasivniLeva()
{
  document.getElementById("divVyberLevy").style.backgroundImage = "url('img/eshopP.jpg')";
}
function aktivniPrava()
{
  document.getElementById("divVyberPravy").style.backgroundImage = "url('img/dilnaA.jpg')";
}
function pasivniPrava()
{
  document.getElementById("divVyberPravy").style.backgroundImage = "url('img/dilnaP.jpg')";
}

// KONTROLY OBSAHU  -------------------------------------------------------------------------------------


function kontrolaObsahu(div)
{
  var vyskaOkna = window.innerHeight;
  var top = document.getElementById("divTop").offsetHeight;
  var menu = document.getElementById("divMenu").offsetHeight;
  var obsah = document.getElementById(div).offsetHeight;
  var bottom = document.getElementById("divBottom").offsetHeight;
  var patka = document.getElementById("divPatka").offsetHeight;
  
  if(vyskaOkna > (top+obsah+patka+bottom+menu))
  { 
    var vyskaObsahu = (vyskaOkna-patka-top-bottom-menu-25);
    document.getElementById(div).style.height = vyskaObsahu+'px';
  }
  
}

//---------------SCHOVEJ AKTUALITY----------------------
function schovejAktuality()
{
  document.getElementById("divAktuality").style.right = '-340px';
  document.getElementById("divAktualityOusko").innerHTML = '<img class="imgOusko" src="img/akt-ouskoPP.png" alt="ousko" onclick=\'ukazAktuality();\' onmouseover=\'this.src="img/akt-ouskoPA.png"\;povysunAkt()\;\' onmouseout=\'this.src="img/akt-ouskoPP.png"\;zasunAkt()\;\' />';
}

function ukazAktuality()
{
  document.getElementById("divAktuality").style.right = '0';
  document.getElementById("divAktualityOusko").innerHTML = '<img class="imgOusko" src="img/akt-ouskoAP.png" alt="ousko" onclick=\'schovejAktuality();\' onmouseover=\'this.src="img/akt-ouskoAA.png"\' onmouseout=\'this.src="img/akt-ouskoAP.png"\' />';
}

function povysunAkt()
{
    document.getElementById("divAktuality").style.right = '-335px';
}

function zasunAkt()
{
    document.getElementById("divAktuality").style.right = '-340px';

}

//---------------NASTAV UPOUTAVKU----------------------
function nastavUpoutavku(divUpoutavka)
{
  var vyskaOkna = window.innerHeight;
  
    var vyskaUpoutavky = document.getElementById(divUpoutavka).offsetHeight;
  
  var pozice = (vyskaOkna-vyskaUpoutavky)/2;
  
  if(divUpoutavka == "divUpoutavkaUvnitr")
  {
    document.getElementById(divUpoutavka).style.top = (pozice)+'px';
  }
  else
  {
    document.getElementById(divUpoutavka).style.top = pozice+'px';
  }     
  
}

//---------------SCHOVEJ UPOUTAVKU----------------------
function ukazPrihlaseni(divPrihl)
{
  
  document.getElementById(divPrihl).style.visibility = 'visible';
}

function schovejPrihlaseni(divPrihlS)
{
  
  document.getElementById(divPrihlS).style.visibility = 'hidden';
}

// --------VYCISTI POLE ---------------------------------------------
function vycistiPole(formular,pole,neponechat)
{
  var soucasnaHodnota = document.forms[formular][pole].value;
  
  if(soucasnaHodnota == neponechat || soucasnaHodnota == "")
  {
    document.forms[formular][pole].value = '';  
  }
}

// --------VRAT POLE ---------------------------------------------
function vratPole(formularV,poleV)
{
  if(formularV == "formular")
  {
    var mail = document.forms[formularV]["mail"].value;
    var zprava = document.forms[formularV]["zprava"].value;
    var kontrola = document.forms[formularV]["kontrola"].value;

    if(mail == "" && poleV != "mail")
    {
      document.forms[formularV]["mail"].value = "váš e-mail (povinné)";   
    }
    if(zprava == "" && poleV != "zprava")
    {
      document.forms[formularV]["zprava"].value = "vaše zpráva (povinné)";   
    }
    if(kontrola == "" && poleV != "kontrola")
    {
      document.forms[formularV]["kontrola"].value = "7 + 2 =";   
    }
  }
}

//--------FCE NA VALIDACI DATUMU----------
function validaceDatumu(udalostDen,udalostMesic,udalostRok)
{
  var skore = 0;
  
  var datumDnes = new Date();
  var rokDnes = datumDnes.getFullYear();
  var mesicDnes = (datumDnes.getMonth() + 1);
  var denDnes = datumDnes.getDate();
  
  for(cisliceDen = 1; cisliceDen < 10; cisliceDen++)
  {
    if(udalostDen == "0"+cisliceDen) udalostDen = cisliceDen;
  }
  
  for(cisliceMesic = 1; cisliceMesic < 10; cisliceMesic++)
  {
    if(udalostMesic == "0"+cisliceMesic) udalostMesic = cisliceMesic;
  }

  if(udalostMesic > 12) skore++;
  if(udalostDen > 31) skore++;
  if(udalostRok < rokDnes) skore++;
  if(udalostRok == rokDnes && udalostMesic < mesicDnes) skore++;
  if(udalostRok == rokDnes && udalostMesic == mesicDnes && udalostDen < denDnes) skore++;
  if(udalostMesic == 2 && ((udalostRok%4) != 0) && udalostDen > 28) skore++;
  if(udalostMesic == 2 && ((udalostRok%4) == 0) && udalostDen > 29) skore++;
  if(udalostMesic == 4 && udalostDen > 30) skore++;
  if(udalostMesic == 6 && udalostDen > 30) skore++;
  if(udalostMesic == 9 && udalostDen > 30) skore++;
  if(udalostMesic == 11 && udalostDen > 30) skore++;
  
  return skore;
}
// VALIDACE  REZERVACE -------------------------------------------------------------------------------------
function validaceRezervace()
{
  var jmeno = document.forms["rezervace"]["jmeno"].value;
  var prijmeni = document.forms["rezervace"]["prijmeni"].value;
  var email = document.forms["rezervace"]["email"].value;
  var telefon = document.forms["rezervace"]["tel"].value;
  var pocetOsob = document.forms["rezervace"]["pocetOs"].value;
  var kontrola = document.forms["rezervace"]["kontrola"].value;
  
  var udalostDenP = document.forms["rezervace"]["denP"].value;
  var udalostMesicP = document.forms["rezervace"]["mesicP"].value;
  var udalostRokP = document.forms["rezervace"]["rokP"].value;
  
  var udalostDenO = document.forms["rezervace"]["denO"].value;
  var udalostMesicO = document.forms["rezervace"]["mesicO"].value;
  var udalostRokO = document.forms["rezervace"]["rokO"].value;
  
  var skoreValidace = 0;
  
  // -------------VALIDACE TELEFONU---------------------
  if(telefon == "")
  {
    skoreValidace++; 
  }
  
  // -------------VALIDACE POCTU LIDI---------------------
  if(pocetOsob == "")
  {
    skoreValidace++; 
  }
  else
  {
    if(isNaN(pocetOsob) == true) skoreValidace++; 
  }
  
  // -------------VALIDACE DATUMU PRIJEZDU---------------------
  if(isNaN(udalostDenP) == true || isNaN(udalostMesicP) == true || isNaN(udalostRokP) == true)
  {
    skoreValidace++;  
  }
  else
  {
    skoreValidace = skoreValidace + validaceDatumu(udalostDenP,udalostMesicP,udalostRokP);
  }

  // -------------VALIDACE DATUMU ODJEZDU---------------------
  if(isNaN(udalostDenO) == true || isNaN(udalostMesicO) == true || isNaN(udalostRokO) == true)
  {
    skoreValidace++;  
  }
  else
  {
    skoreValidace = skoreValidace + validaceDatumu(udalostDenO,udalostMesicO,udalostRokO);
    
    if(udalostRokO < udalostRokP) skoreValidace++;
    if(udalostRokO == udalostRokP && udalostMesicO < udalostMesicP) skoreValidace++;
    if(udalostRokO == udalostRokP && udalostMesicO == udalostMesicP && udalostDenO <= udalostDenP) skoreValidace++; 
  }

  // -------------VALIDACE JMENA---------------------
  if(jmeno == "")
  {
    skoreValidace++; 
  }
  // -------------VALIDACE JMENA---------------------
  if(prijmeni == "")
  {
    skoreValidace++; 
  }
  // -------------VALIDACE MAILU---------------------
  if(email == "")
  {
    skoreValidace++; 
  }
  else
  {
    var zavinac = /\w{1,}[@]{1}\w{1,}[.]{1}\w{2,4}$/i;
    
    if(zavinac.test(email) == false) skoreValidace++;
  }
  
  
  // -------------VALIDACE CAPTCHA---------------------
  if(kontrola != "7") skoreValidace++;
  
  // -------------VYSLEDEK---------------------
  if(skoreValidace == 0)
  {
    document.forms["rezervace"]["odeslat"].disabled="";
    document.forms["rezervace"]["odeslat"].className="buttonOdeslat";  
  }
  else
  {
    document.forms["rezervace"]["odeslat"].disabled="disabled";
    document.forms["rezervace"]["odeslat"].className="buttonOdeslatD";  
  }
}

// VALIDACE  KONTAKTNIHO FORMULARE -------------------------------------------------------------------------------------
function validaceKontakt()
{
  
  var emailK = document.forms["formular"]["mail"].value;
  var zpravaK = document.forms["formular"]["zprava"].value;
  var kontrola = document.forms["formular"]["kontrola"].value;
  
  var skoreValidace = 0;

  // -------------VALIDACE zpravy---------------------
  if(zpravaK == "")
  {
    skoreValidace++; 
  }
  // -------------VALIDACE MAILU---------------------
  if(emailK == "")
  {
    skoreValidace++; 
  }
  else
  {
    var zavinac = /\w{1,}[@]{1}\w{1,}[.]{1}\w{2,4}$/i;
    
    if(zavinac.test(emailK) == false) skoreValidace++;
  }
  
  
  // -------------VALIDACE CAPTCHA---------------------
  if(kontrola != "9") skoreValidace++;
  
  // -------------VYSLEDEK---------------------
  if(skoreValidace == 0)
  {
    document.forms["formular"]["odeslat"].disabled="";
    document.forms["formular"]["odeslat"].className="buttonOdeslat";  
  }
  else
  {
    document.forms["formular"]["odeslat"].disabled="disabled";
    document.forms["formular"]["odeslat"].className="buttonOdeslatD";  
  }
}

//--------------------FOTOGALERIE-------------------------------------
function plneFoto(foto)
{
  var celkemFoto = 5;
  var divPlneFoto = "divVelkeFoto";
  var divSipkaVL = "divSipkaLV";
  var divSipkaVP = "divSipkaPV";
  var cestaPlne = "img/galerie/";
  
  if(foto < celkemFoto)
  {
    
    document.getElementById(divPlneFoto).innerHTML='<img src="'+cestaPlne+foto+'.jpg" alt="foto-'+foto+'" onclick=\'plneFoto('+(foto+1)+')\' />';
    
  }
  else
  {
    document.getElementById(divPlneFoto).innerHTML='<img src="'+cestaPlne+foto+'.jpg" alt="foto-'+foto+'" onclick=\'plneFoto(1)\' />';
    
  }
  
  if(foto < celkemFoto)
  {
    if(foto == 1)
    {
      document.getElementById(divSipkaVL).innerHTML='<img src="img/sipkaLVP.png" alt="šipka levá" onmouseover=\'this.src="img/sipkaLVA.png"\;\' onmouseout=\'this.src="img/sipkaLVP.png"\;\' onclick=\'plneFoto('+celkemFoto+')\;\' />';
      document.getElementById(divSipkaVP).innerHTML='<img src="img/sipkaPVP.png" alt="šipka pravá" onmouseover=\'this.src="img/sipkaPVA.png"\;\' onmouseout=\'this.src="img/sipkaPVP.png"\;\' onclick=\'plneFoto('+(foto+1)+')\;\' />';
    }
    else
    {
      document.getElementById(divSipkaVL).innerHTML='<img src="img/sipkaLVP.png" alt="šipka levá" onmouseover=\'this.src="img/sipkaLVA.png"\;\' onmouseout=\'this.src="img/sipkaLVP.png"\;\' onclick=\'plneFoto('+(foto-1)+')\;\' />';
      document.getElementById(divSipkaVP).innerHTML='<img src="img/sipkaPVP.png" alt="šipka pravá" onmouseover=\'this.src="img/sipkaPVA.png"\;\' onmouseout=\'this.src="img/sipkaPVP.png"\;\' onclick=\'plneFoto('+(foto+1)+')\;\' />';
    }
  }
  else
  {
    document.getElementById(divSipkaVL).innerHTML='<img src="img/sipkaLVP.png" alt="šipka levá" onmouseover=\'this.src="img/sipkaLVA.png"\;\' onmouseout=\'this.src="img/sipkaLVP.png"\;\' onclick=\'plneFoto('+(foto-1)+')\;\' />';
    document.getElementById(divSipkaVP).innerHTML='<img src="img/sipkaPVP.png" alt="šipka pravá" onmouseover=\'this.src="img/sipkaPVA.png"\;\' onmouseout=\'this.src="img/sipkaPVP.png"\;\' onclick=\'plneFoto(1)\;\' />';  
  }
  
  napisNavigaci(foto);
}

function napisNavigaci(aktNav)
{
  document.getElementById("ulNavigace").innerHTML = ' \
                  <li id="galNav1"><img src="img/puntikP.png" /></li> \
                  <li id="galNav2"><img src="img/puntikP.png" /></li> \
                  <li id="galNav3"><img src="img/puntikP.png" /></li> \
                  <li id="galNav4"><img src="img/puntikP.png" /></li> \
                  <li id="galNav5"><img src="img/puntikP.png" /></li> \
                 \
                ';
  document.getElementById("galNav"+aktNav).innerHTML = '<img src="img/puntikA.png" />';
}

