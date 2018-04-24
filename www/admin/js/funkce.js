// JavaScript Document
$(function () {
    $.nette.init();
    // And you fly...
});


// -------MAZÁNÍ OBRÁZKŮ Z GALERIE---------------
var deleteGaleryImage = function(link, json){
    $(function () {
        $(".divDeleteImg").click(function(){
            var id = $(this).attr('id');
            json.imageId = id;
            $.getJSON(link, json, function(payload) {

                console.log(payload)
                if(payload.success){
                    $("#imgBlock_" + id).fadeOut(function () {
                        $(this).remove()
                        setTimeout(function () {
                            $('.equal-height-panels .samePanel').matchHeight();
                        }, 100)
                    })
                }
                // else {
                //     $("#divErrors").show().append('<li>Nelze smazat poslední záznam</li>')
                //     setTimeout(function () {
                //         $("#divErrors").slideUp( 650 ).delay(800).html('');
                //     }, 3000)
                // }


            });
        })
    });
}


/*----------------------------------------------------------------------------*/
/* Confirm box before delete: */
var confirmBox = '<div class="modal fade confirm-modal">' +
    '<div class="modal-dialog modal-sm" role="document">' +
    '<div class="modal-content">' +
    '<button type="button" class="close m-4 c-pointer" data-dismiss="modal" aria-label="Close">' +
    '<span aria-hidden="true">&times;</span>' +
    '</button>' +
    '<div class="modal-body pb-5"></div>' +
    '<div class="modal-footer pt-3 pb-3">' +
    '<a href="#" class="btn btn-primary yesBtn btn-sm">OK</a>' +
    '<button type="button" class="btn btn-secondary abortBtn btn-sm" data-dismiss="modal">Do nothing</button>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>';

var dialog = function(el, text, trueCallback, abortCallback) {

    el.click(function(e) {

        var thisConfirm = $(confirmBox).clone();

        thisConfirm.find('.modal-body').text(text);

        e.preventDefault();
        $('body').append(thisConfirm);
        $(thisConfirm).modal('show');

        if (abortCallback) {
            $(thisConfirm).find('.abortBtn').click(function(e) {
                e.preventDefault();
                abortCallback();
                $(thisConfirm).modal('hide');
            });
        }

        if (trueCallback) {
            $(thisConfirm).find('.yesBtn').click(function(e) {
                e.preventDefault();
                trueCallback();
                $(thisConfirm).modal('hide');
            });
        } else {

            if (el.prop('nodeName') == 'A') {
                $(thisConfirm).find('.yesBtn').attr('href', el.attr('href'));
            }

            if (el.attr('type') == 'submit') {
                $(thisConfirm).find('.yesBtn').click(function(e) {
                    e.preventDefault();
                    el.off().click();
                });
            }
        }

        $(thisConfirm).on('hidden.bs.modal', function(e) {
            $(this).remove();
        });

    });
};


/*----------------------------------------------------------------------------*/
/* Flash messages: */

var position = $("#flashMessage").position();
var c = 0;
var t;
var timer_is_on = 0;
function flashMessage(message){
    startCount();
    c = 0
    ToggleMessage(true)
    $("#flashMessage").find('ul').append('<li>' + message + '</li>');
}

function ToggleMessage(bol) {
    if(bol) {
        $("#flashMessage").animate({
            top: '0px'
        }, 400);
    }
    else {
        $("#flashMessage").animate({
            top: '-400px'
        }, 400);
    }
}

function timedCount() {

    c = c + 1;
    t = setTimeout(function(){
        if(c > 3){
            stopCount()
            ToggleMessage(false)
            $("#flashMessage").find('ul').html('')
        }
        timedCount()
    }, 1000);
}


function startCount() {
    if (!timer_is_on) {
        timer_is_on = 1;
        timedCount();
    }
}

function stopCount() {
    clearTimeout(t);
    timer_is_on = 0;
}



// -------PŘEPÍNÁNÍ UPDATOVATELNÝCH VĚCÍ V GALERII---------------
$(document).ready(function(){
    $(".btnViews").click(function(){
        var clicked = $(this).attr("id").toString();
        $("#allImages, #uploadPhoto, #changeCover, #changeInfo").animate({
            opacity: '0'
        }, 200, function(){
            var id = clicked.substring(0,clicked.length-3);
            $("#" + id).animate({
                opacity: '1'
            }, 200).slideDown(230);

        }).slideUp(230);                            
    });
});

// -------VYTVOŘENÍ FORMULÁŘE OPRÁVNĚNÍ---------------
$("#submit").click(function (){
    // Get values from elements on the page:
    var $form = $( "#selectUserForm" ),
      term = $form.find( "input[name='users']" ).val(),
      url = 'process/getUserProcess.php';

    // Send the data using post
    var posting = $.post( url, { myData: term, site: "opravneni" } );

    // Put the results in a div
    posting.done(function( data ) {
        data = data.data;
        if(data.status == "success"){
            $("#divMessage").html("");
            $("#selectedUser").val("");
            $("#showPerson").html(createPersonView(data));
            assignRights(data.rights);
            console.log(data);
        }
        else if(data.status == "error"){
            var error = "<div class=\"alert alert-warning alert-dismissable fade in\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>Člen nebyl v databázi nalezen.</div>"
            $("#divMessage").html(error);
        }


    });
});

function createPersonView(data){
    var labels = getLabels();
    var credentials = '<h2 class="text-left">' + data.name + ' ' + data.surname + '</h2><p class="text-left"><strong>email:</strong> ' + data.email + '</p><h3 class="text-left">Práva</h3>';
    var pre = '<form name="vyberClena" method="POST" action="process/rewriteRightsProcess.php" id="selectUserForm"><div class="divPravo"><ul class="list-group">';
    var liStart = '<li class="list-group-item">';
    var labelStart = '<label class="custom-control custom-checkbox"><input type="checkbox" name="';
    var labelEnd = '" class="custom-control-input hidden"><span class="custom-control-indicator"></span></label></li>';
    var post = '</ul></div><div class=\"row\"><div class=\"offset-md-2 col-sm-10\"><button id=\"rewriteRights\" type=\"submit\" '
             + 'class=\"btn btn-primary btnSubmit\">Updatovat pravomoce</button></div><input type="hidden" name="userId" value="' + data.id 
             + '"></div></form><form name="smazClena" method="POST" action="process/deleteUser.php" id="deleteUserForm"><button id=\"rewriteRights\" type=\"submit\" '
             + 'class=\"btn btn-primary btnDelete\">Smazat člena</button><input type="hidden" name="userId" value="' + data.id 
             + '"></form></div>';
    
    var finalString = '';
    finalString += credentials;
    finalString += pre;
    for (i = 0; i < labels[1].length; i++){
        finalString += liStart;
        finalString += labels[0][i];
        finalString += labelStart;
        finalString += labels[1][i];
        finalString += labelEnd;
    }
    finalString += post;
    
    return finalString;
}

function getLabels(){
    var labels = [
        ["Pozastaven", "Aktuality", "Stáže", "Prohlížení", "Přidávání členů", "Administrázor"],
        ["inPresigned", "inAktuality", "inStaze", "inProhlizeni", "inPridavaniClenu", "inAdministrator"]
    ];
    return labels;
}

function assignRights(right){
    right = parseFloat(right);
    var labels = getLabels();
    var forRights = [
        [0, -1],                                  //presigned
        [3, 15, 21, 33, 105, 231, 165, 2520, 24], //aktuality
        [5, 15, 35, 55, 105, 358, 165, 2520, 24], //stáže
        [7, 35, 77, 21, 105, 358, 231, 2520, 24], //prohlížení
        [11, 77, 55, 33, 385, 231, 165, 2520,24], //přidávání nových členů
        [24]                                      //administrátor
        ];
    
    for(i = 0; i < forRights.length; i++){
        var checkIt = forRights[i];
        if(checkIt.indexOf(right) != -1 || checkIt.indexOf(right*(-1)) != -1){
            $('[name="' + labels[1][i] + '"]').attr('checked', true);
        }        
    }
    if(right < 0){
        $('[name="inPresigned"]').attr('checked', true);
    }
    
}





// -------KONTROLA OBSAHU---------------
function kontrolaObsahu(div)
{
  var vyskaOkna = window.innerHeight;
  var top = document.getElementById("divTop").offsetHeight;
  var obsah = document.getElementById("divObsah").offsetHeight;
  var patka = document.getElementById("divPatka").offsetHeight;
  
  if(vyskaOkna > (top+obsah+patka))
  {
    var vyskaObsahu = (vyskaOkna-top-patka);
    document.getElementById("divObsah").style.height = vyskaObsahu+'px';
  }
}



// -------PRIDANI POLOZKY DO CASTI PLANU---------------
function pridatCast()
{
  var aktObsah = document.getElementById("divCasti").innerHTML;
  var pocetKolonek = document.forms["pridatPlan"]["pocetCasti"].value;
  var novyObsah = new Array();
  var vkladObsah = "";
  var cislo = 0;
  var cisloH = 0;
  
  pocetKolonek = (pocetKolonek*1)+1;
  
  novyObsah.push(aktObsah);
  
  vkladObsah = '<table class="tableForm"> \
                  <tr> \
                      <td>Část '+pocetKolonek+':</td> \
                      <td> \
                        od  \
                        <select name="hod'+pocetKolonek+'">';
  novyObsah.push(vkladObsah);                       
                          
  for(cislo = 0; cislo < 24; cislo++)
  {
    if(cislo < 10) 
      cisloH = "0"+cislo;
    else 
      cisloH = cislo;

    vkladObsah = '<option value="'+cislo+'">'+cisloH+'</option>';
    novyObsah.push(vkladObsah);
  }
  
  vkladObsah = '</select>: \
                  <select name="mod'+pocetKolonek+'" class="selectKonec">';
  novyObsah.push(vkladObsah);
                          
  for(cislo = 0; cislo < 60; cislo = cislo+10)
  {
    if(cislo < 10) 
      cisloH = "0"+cislo;
    else 
      cisloH = cislo;

    vkladObsah = '<option value="'+cislo+'">'+cisloH+'</option>';
    novyObsah.push(vkladObsah);
  }
  
  vkladObsah = '</select> do  \
                  <select name="hdo'+pocetKolonek+'">';
  novyObsah.push(vkladObsah);
  
  for(cislo = 0; cislo < 24; cislo++)
  {
    if(cislo < 10) 
      cisloH = "0"+cislo;
    else 
      cisloH = cislo;

    vkladObsah = '<option value="'+cislo+'">'+cisloH+'</option>';
    novyObsah.push(vkladObsah);
  }
  
  vkladObsah = '</select>: \
                  <select name="mdo'+pocetKolonek+'">';
  novyObsah.push(vkladObsah);
                          
  for(cislo = 0; cislo < 60; cislo = cislo+10)
  {
    if(cislo < 10) 
      cisloH = "0"+cislo;
    else 
      cisloH = cislo;

    vkladObsah = '<option value="'+cislo+'">'+cisloH+'</option>';
    novyObsah.push(vkladObsah);
  }
          
  vkladObsah = '</select> \
                    </td> \
                    <td class="tdPozn"></td> \
                  </tr> \
                </table>';
  novyObsah.push(vkladObsah);

  
  var novyObsahCely = novyObsah.join(''); 
  
  document.getElementById("divCasti").innerHTML = novyObsahCely;
  document.forms["pridatPlan"]["pocetCasti"].value = pocetKolonek;
}



// -------PRIDANI POLOZKY DO POPISU---------------
function pridatPopis(co,idblok)
{
  var pocetBloku = document.forms["struktura"]["pocetBloku"].value;
  var sledBloku = document.forms["struktura"]["sledBloku"].value;
  var starySled = new Array();
  var celaStruktura = new Array();
  var vkladStruktury = '';
  var typStBloku = '';
  var hodnotaStBloku = '';
  var urovenStBloku = 0;
  
  if(sledBloku != "")
    starySled = sledBloku.split(",");
  
  var novySled = new Array();
  var celySled = '';
  var cisloBloku = 0;
  var novyBlok = 1;
  
  pocetBloku++;
  
  vkladStruktury = '<input type="hidden" name="pocetBloku" value="'+pocetBloku+'" /> \
                        <table class="tableFormPopis"> \
                          <tr> \
                            <td colspan="4"> \
                              <div class="divPopNovyPrvni"> \
                                <button class="buttonPopNovy" type="button" onclick=\'pridatPopis("nadpis","0")\;\'>nadpis</button> \
                              </div> \
                            </td> \
                          </tr>';
  celaStruktura.push(vkladStruktury);
  
  for(aktBlok = 0; aktBlok < pocetBloku; aktBlok++)
  {
    
    if(aktBlok > 0) // ZAPSAT BLOK, CO JE NA ŘADĚ
    { 
       typStBloku = starySled[cisloBloku]; 
       
       if(typStBloku == "nadpis")
       {   
          hodnotaStBloku = document.forms["struktura"]["nadpis"+aktBlok].value;
          urovenStBloku = document.forms["struktura"]["uroven"+aktBlok].value;
          
          vkladStruktury = '<tr> \
                              <td>Nadpis:</td> \
                              <td> \
                                <input class="inputNadpis" type="text" name="nadpis'+novyBlok+'" value="'+hodnotaStBloku+'" /> \
                                <select class="selectPopis" name="uroven'+novyBlok+'">';
          celaStruktura.push(vkladStruktury);
          
          if(urovenStBloku == 1)
          {
            vkladStruktury = '<option value="1" selected>Úroveň 1</option> \
                              <option value="2">Úroveň 2</option> \
                              <option value="3">Úroveň 3</option> \
                              <option value="4">Úroveň 4</option>';
            celaStruktura.push(vkladStruktury);
          }
          else if(urovenStBloku == 2)
          {
            vkladStruktury = '<option value="1">Úroveň 1</option> \
                              <option value="2" selected>Úroveň 2</option> \
                              <option value="3">Úroveň 3</option> \
                              <option value="4">Úroveň 4</option>';
            celaStruktura.push(vkladStruktury);
          }
          else if(urovenStBloku == 3)
          {
            vkladStruktury = '<option value="1">Úroveň 1</option> \
                              <option value="2">Úroveň 2</option> \
                              <option value="3" selected>Úroveň 3</option> \
                              <option value="4">Úroveň 4</option>';
            celaStruktura.push(vkladStruktury);
          }
          else
          {
            vkladStruktury = '<option value="1">Úroveň 1</option> \
                              <option value="2">Úroveň 2</option> \
                              <option value="3">Úroveň 3</option> \
                              <option value="4" selected>Úroveň 4</option>';
            celaStruktura.push(vkladStruktury);
          }
          
          vkladStruktury = '  </select> \
                              </td> \
                              <td><button class="buttonPopOdstranit" type="button" onclick=\'odstranitPopis('+novyBlok+')\;\'>X</button></td>';
          celaStruktura.push(vkladStruktury);
        }
        else if(typStBloku == "odstavec")
        {
          hodnotaStBloku = document.forms["struktura"]["odstavec"+aktBlok].value;
          
          vkladStruktury = '<tr> \
                              <td>Odstavec:</td> \
                              <td> \
                                <textarea class="inputOdstavec" name="odstavec'+novyBlok+'">'+hodnotaStBloku+'</textarea> \
                              </td> \
                              <td><button class="buttonPopOdstranit" type="button" onclick=\'odstranitPopis('+novyBlok+')\;\'>X</button></td>';
          celaStruktura.push(vkladStruktury);
        }
        else
        {
          hodnotaStBloku = document.forms["struktura"]["seznam"+aktBlok].value;
          
          vkladStruktury = '<tr> \
                              <td>Odrážka:</td> \
                              <td> \
                                <input class="inputSeznam" type="text" name="seznam'+novyBlok+'" value="'+hodnotaStBloku+'" /> \
                              </td> \
                              <td><button class="buttonPopOdstranit" type="button" onclick=\'odstranitPopis('+novyBlok+')\;\'>X</button></td>';
          celaStruktura.push(vkladStruktury);
        }
        
      
      vkladStruktury = '<td> \
                              <div class="divPopNovy"> \
                                <button class="buttonPopNovy" type="button" onclick=\'pridatPopis("nadpis",'+novyBlok+')\;\'>nadpis</button> \
                                <button class="buttonPopNovy" type="button" onclick=\'pridatPopis("odstavec",'+novyBlok+')\;\'>odstavec</button> \
                                <button class="buttonPopNovy" type="button" onclick=\'pridatPopis("seznam",'+novyBlok+')\;\'>odrážka</button> \
                              </div> \
                            </td> \
                          </tr>';
      celaStruktura.push(vkladStruktury);

      
      novySled.push(typStBloku);
        
      
      
      novyBlok++;
      cisloBloku++;

    } // konec ZAPSAT BLOK, CO JE NA ŘADĚ
    
    if(aktBlok == idblok) // ZAPSAT NOVÝ BLOK, POKUD JE NA ŘADĚ
    {  

      if(co == "nadpis") // ZAPSAT NOVÝ BLOK
      {
        vkladStruktury = '<tr> \
                              <td>Nadpis:</td> \
                              <td> \
                                <input class="inputNadpis" type="text" name="nadpis'+novyBlok+'" value="" /> \
                                <select class="selectPopis" name="uroven'+novyBlok+'"> \
                                  <option value="1">Úroveň 1</option> \
                                  <option value="2">Úroveň 2</option> \
                                  <option value="3">Úroveň 3</option> \
                                  <option value="4">Úroveň 4</option> \
                                </select> \
                              </td> \
                              <td><button class="buttonPopOdstranit" type="button" onclick=\'odstranitPopis('+novyBlok+')\;\'>X</button></td>';
        celaStruktura.push(vkladStruktury);
      }
      else if(co == "odstavec")
      {
        vkladStruktury = '<tr> \
                            <td>Odstavec:</td> \
                            <td> \
                              <textarea class="inputOdstavec" name="odstavec'+novyBlok+'"></textarea> \
                            </td> \
                            <td><button class="buttonPopOdstranit" type="button" onclick=\'odstranitPopis('+novyBlok+')\;\'>X</button></td>';
        celaStruktura.push(vkladStruktury);
      }
      else
      {
        vkladStruktury = '<tr> \
                            <td>Odrážka:</td> \
                            <td> \
                             <input class="inputSeznam" type="text" name="seznam'+novyBlok+'" value="" /> \
                            </td> \
                            <td><button class="buttonPopOdstranit" type="button" onclick=\'odstranitPopis('+novyBlok+')\;\'>X</button></td>';
        celaStruktura.push(vkladStruktury);
      }
      
      vkladStruktury = '<td> \
                              <div class="divPopNovy"> \
                                <button class="buttonPopNovy" type="button" onclick=\'pridatPopis("nadpis",'+novyBlok+')\;\'>nadpis</button> \
                                <button class="buttonPopNovy" type="button" onclick=\'pridatPopis("odstavec",'+novyBlok+')\;\'>odstavec</button> \
                                <button class="buttonPopNovy" type="button" onclick=\'pridatPopis("seznam",'+novyBlok+')\;\'>odrážka</button> \
                              </div> \
                            </td> \
                          </tr>';
      celaStruktura.push(vkladStruktury);
    
      
      
      novySled.push(co);
      
      novyBlok++;
      
    } // konec ZAPSAT NOVÝ BLOK, POKUD JE NA ŘADĚ

  } // konec FOR NA VYPIS BLOKŮ
  
  celySled = novySled.join(',');  
  
  vkladStruktury = '<tr> \
                          <td class="tdButton" colspan="4"><button type="submit">ULOŽIT</button></td> \
                        </tr> \
                      </table> \
                      <input type="hidden" name="sledBloku" value="'+celySled+'" />';
  celaStruktura.push(vkladStruktury);
  
  var vklad = celaStruktura.join('');    

  document.getElementById("divStruktura").innerHTML = vklad;  
}



function odstranitPopis(idbloksm)
{
  var pocetBloku = document.forms["struktura"]["pocetBloku"].value;
  var sledBloku = document.forms["struktura"]["sledBloku"].value;
  var starySled = new Array();
  var celaStruktura = new Array();
  var vkladStruktury = '';
  var typStBloku = '';
  var hodnotaStBloku = '';
  var urovenStBloku = 0;
  var staryPocet = pocetBloku;
  
  if(sledBloku != "")
    starySled = sledBloku.split(",");
  
  var novySled = new Array();
  var celySled = '';
  var cisloBloku = 0;
  var novyBlok = 1;
  
  pocetBloku--;
  
  vkladStruktury = '<input type="hidden" name="pocetBloku" value="'+pocetBloku+'" /> \
                        <table class="tableFormPopis"> \
                          <tr> \
                            <td colspan="4"> \
                              <div class="divPopNovyPrvni"> \
                                <button class="buttonPopNovy" type="button" onclick=\'pridatPopis("nadpis","0")\;\'>nadpis</button> \
                                <button class="buttonPopNovy" type="button" onclick=\'pridatPopis("odstavec","0")\;\'>odstavec</button> \
                                <button class="buttonPopNovy" type="button" onclick=\'pridatPopis("seznam","0")\;\'>odrážka</button> \
                              </div> \
                            </td> \
                          </tr>';
  celaStruktura.push(vkladStruktury);
  
  for(aktBlok = 1; aktBlok <= staryPocet; aktBlok++)
  {
    if(aktBlok != idbloksm) // ZAPSAT BLOK, CO JE NA ŘADĚ
    { 
       typStBloku = starySled[cisloBloku]; 
       
       if(typStBloku == "nadpis")
       {   
          hodnotaStBloku = document.forms["struktura"]["nadpis"+aktBlok].value;
          urovenStBloku = document.forms["struktura"]["uroven"+aktBlok].value;
          
          vkladStruktury = '<tr> \
                              <td>Nadpis:</td> \
                              <td> \
                                <input class="inputNadpis" type="text" name="nadpis'+novyBlok+'" value="'+hodnotaStBloku+'" /> \
                                <select class="selectPopis" name="uroven'+novyBlok+'">';
          celaStruktura.push(vkladStruktury);
          
          if(urovenStBloku == 1)
          {
            vkladStruktury = '<option value="1" selected>Úroveň 1</option> \
                              <option value="2">Úroveň 2</option> \
                              <option value="3">Úroveň 3</option> \
                              <option value="4">Úroveň 4</option>';
            celaStruktura.push(vkladStruktury);
          }
          else if(urovenStBloku == 2)
          {
            vkladStruktury = '<option value="1">Úroveň 1</option> \
                              <option value="2" selected>Úroveň 2</option> \
                              <option value="3">Úroveň 3</option> \
                              <option value="4">Úroveň 4</option>';
            celaStruktura.push(vkladStruktury);
          }
          else if(urovenStBloku == 3)
          {
            vkladStruktury = '<option value="1">Úroveň 1</option> \
                              <option value="2">Úroveň 2</option> \
                              <option value="3" selected>Úroveň 3</option> \
                              <option value="4">Úroveň 4</option>';
            celaStruktura.push(vkladStruktury);
          }
          else
          {
            vkladStruktury = '<option value="1">Úroveň 1</option> \
                              <option value="2">Úroveň 2</option> \
                              <option value="3">Úroveň 3</option> \
                              <option value="4" selected>Úroveň 4</option>';
            celaStruktura.push(vkladStruktury);
          }
          
          vkladStruktury = '  </select> \
                              </td> \
                              <td><button class="buttonPopOdstranit" type="button" onclick=\'odstranitPopis('+novyBlok+')\;\'>X</button></td>';
          celaStruktura.push(vkladStruktury);
        }
        else if(typStBloku == "odstavec")
        {
          hodnotaStBloku = document.forms["struktura"]["odstavec"+aktBlok].value;
          
          vkladStruktury = '<tr> \
                              <td>Odstavec:</td> \
                              <td> \
                                <textarea class="inputOdstavec" name="odstavec'+novyBlok+'">'+hodnotaStBloku+'</textarea> \
                              </td> \
                              <td><button class="buttonPopOdstranit" type="button" onclick=\'odstranitPopis('+novyBlok+')\;\'>X</button></td>';
          celaStruktura.push(vkladStruktury);
        }
        else
        {
          hodnotaStBloku = document.forms["struktura"]["seznam"+aktBlok].value;
          
          vkladStruktury = '<tr> \
                              <td>Odrážka:</td> \
                              <td> \
                                <input class="inputSeznam" type="text" name="seznam'+novyBlok+'" value="'+hodnotaStBloku+'" /> \
                              </td> \
                              <td><button class="buttonPopOdstranit" type="button" onclick=\'odstranitPopis('+novyBlok+')\;\'>X</button></td>';
          celaStruktura.push(vkladStruktury);
        }
        
      
      vkladStruktury = '<td> \
                              <div class="divPopNovy"> \
                                <button class="buttonPopNovy" type="button" onclick=\'pridatPopis("nadpis",'+novyBlok+')\;\'>nadpis</button> \
                                <button class="buttonPopNovy" type="button" onclick=\'pridatPopis("odstavec",'+novyBlok+')\;\'>odstavec</button> \
                                <button class="buttonPopNovy" type="button" onclick=\'pridatPopis("seznam",'+novyBlok+')\;\'>odrážka</button> \
                              </div> \
                            </td> \
                          </tr>';
      celaStruktura.push(vkladStruktury);

      
      novySled.push(typStBloku);
        

      novyBlok++;

    } // konec ZAPSAT BLOK, CO JE NA ŘADĚ

    cisloBloku++;
  
  } // konec FOR NA VYPIS BLOKŮ
  
  celySled = novySled.join(',');  
  
  vkladStruktury = '<tr> \
                          <td class="tdButton" colspan="4"><button type="submit">ULOŽIT</button></td> \
                        </tr> \
                      </table> \
                      <input type="hidden" name="sledBloku" value="'+celySled+'" />';
  celaStruktura.push(vkladStruktury);
  
  var vklad = celaStruktura.join('');    

  document.getElementById("divStruktura").innerHTML = vklad;  
}







// -------UPOUTAVKA---------------
function nastavUpoutavku(div)
{
  var vyskaOkna = window.innerHeight;
  var pozice = (vyskaOkna-400)/2;
  
  
    document.getElementById(div).style.top = pozice+'px';     
  
}

function schovejUpoutavku()
{
  document.getElementById("divUpoutavka").style.visibility = 'hidden';
}

// -------HLASKY---------------
function ukazHlasku(polozka,IDPolozky,provadeciSoubor)
{
  document.getElementById("divUpoutavkaUvnitr").innerHTML = '<p class="pHlaska">Opravdu chcete smazat '+polozka+'?</p> \
                                                            <form action="'+provadeciSoubor+'" accept-charset="utf-8" method="post" enctype="multipart/form-data" name="smazat"> \
                                                            <input type="hidden" name="idPolozka" value="'+IDPolozky+'" /> \
                                                            <button type="button" value="storno" onclick="schovejHlasku()\;">NE</button> \
                                                            <button type="submit" value="ok"">ANO</button> \
                                                            </form>';
  
  document.getElementById("divUpoutavka").style.visibility = 'visible';
}

function ukazHlaskuStrojSmazat(polozka,idPolozka,provadeciSoubor,kategorie)
{
  document.getElementById("divUpoutavkaUvnitr").innerHTML = '<p class="pHlaska">Opravdu chcete smazat '+polozka+'?</p> \
                                                            <form action="'+provadeciSoubor+'" accept-charset="utf-8" method="post" enctype="multipart/form-data" name="smazat"> \
                                                            <input type="hidden" name="kategorie" value="'+kategorie+'" /> \
                                                            <input type="hidden" name="idPolozka" value="'+idPolozka+'" /> \
                                                            <button type="button" value="storno" onclick="schovejHlasku()\;">NE</button> \
                                                            <button type="submit" value="ok"">ANO</button> \
                                                            </form>';
  
  document.getElementById("divUpoutavka").style.visibility = 'visible';
}

function ukazHlaskuFotoSmazat(polozka,soubor,provadeciSoubor,kategorie,idPolozka)
{
  document.getElementById("divUpoutavkaUvnitr").innerHTML = '<p class="pHlaska">Opravdu chcete smazat '+polozka+'?</p> \
                                                            <form action="'+provadeciSoubor+'" accept-charset="utf-8" method="post" enctype="multipart/form-data" name="smazat"> \
                                                            <input type="hidden" name="soubor" value="'+soubor+'" /> \
                                                            <input type="hidden" name="kategorie" value="'+kategorie+'" /> \
                                                            <input type="hidden" name="idPolozka" value="'+idPolozka+'" /> \
                                                            <button type="button" value="storno" onclick="schovejHlasku()\;">NE</button> \
                                                            <button type="submit" value="ok"">ANO</button> \
                                                            </form>';
  
  document.getElementById("divUpoutavka").style.visibility = 'visible';
}

function ukazHlaskuFotoSpotr(polozka,soubor,provadeciSoubor,idPolozka)
{
  document.getElementById("divUpoutavkaUvnitr").innerHTML = '<p class="pHlaska">Opravdu chcete smazat '+polozka+'?</p> \
                                                            <form action="'+provadeciSoubor+'" accept-charset="utf-8" method="post" enctype="multipart/form-data" name="smazat"> \
                                                            <input type="hidden" name="soubor" value="'+soubor+'" /> \
                                                            <input type="hidden" name="idPolozka" value="'+idPolozka+'" /> \
                                                            <button type="button" value="storno" onclick="schovejHlasku()\;">NE</button> \
                                                            <button type="submit" value="ok"">ANO</button> \
                                                            </form>';
  
  document.getElementById("divUpoutavka").style.visibility = 'visible';
}

function schovejHlasku()
{
  document.getElementById("divUpoutavka").style.visibility = 'hidden';  
}

// -------INPUTY---------------
function dopisInputy()
{
  var typ = document.forms["pridatPrilohu"]["typ"].value;
  
  var inpOdkaz = '<table class="tableForm"> \
                <tr> \
                <td class="tdFormPopisek"><em>Odkaz:*</em></td> \
                <td class="tdFormInput"> \
                <input class="inputText" type="text" name="cesta" value="http://" /> \
                </td> \
                </tr> \
                <tr> \
                <td class="tdFormPopisek">Popis odkazu:</td> \
                <td class="tdFormInput"> \
                <input class="inputText" type="text" name="popis" value="" /> \
                </td> \
                </tr> \
                </table>';
  var inpObr = '<table class="tableForm"> \
                <tr> \
                <td class="tdFormPopisek"><em>Obrázek:*</em></td> \
                <td class="tdFormInput"> \
                <input class="inputFile" type="file" name="cesta" value="" /> \
                </td> \
                </tr> \
                <tr> \
                <td class="tdFormPopisek">Popis obrázku:</td> \
                <td class="tdFormInput"> \
                <input class="inputText" type="text" name="popis" value="" /> \
                </td> \
                </tr> \
                </table>';
  var inpPdf = '<table class="tableForm"> \
                <tr> \
                <td class="tdFormPopisek"><em>PDF:*</em></td> \
                <td class="tdFormInput"> \
                <input class="inputFile" type="file" name="cesta" value="" /> \
                </td> \
                </tr> \
                <tr> \
                <td class="tdFormPopisek">Popis PDF:</td> \
                <td class="tdFormInput"> \
                <input class="inputText" type="text" name="popis" value="" /> \
                </td> \
                </tr> \
                </table>'; 
  
  if(typ == "1")
  {
    document.getElementById("divInputy").innerHTML = inpOdkaz;
    document.forms["pridatPrilohu"]["odeslat"].className = "buttonPridani";
  } 
  if(typ == "2")
  {
    document.getElementById("divInputy").innerHTML = inpObr;
    document.forms["pridatPrilohu"]["odeslat"].className = "buttonPridani";
  } 
  if(typ == "3")
  {
    document.getElementById("divInputy").innerHTML = inpPdf;
    document.forms["pridatPrilohu"]["odeslat"].className = "buttonPridani";
  }
  if(typ == "nic")
  {
    document.getElementById("divInputy").innerHTML = "";
    document.forms["pridatPrilohu"]["odeslat"].className = "buttonPridaniHidden";
  } 
}


// AJAX VYPIS STROJU  -------------------------------------------------------------------------------------
  
function vypisStroje(kategorie)
{
  if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
    }
  else
    {// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
  xmlhttp.onreadystatechange=function()
    {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
      {
        document.getElementById("divVypis").innerHTML=xmlhttp.responseText;
      }
    }
  xmlhttp.open("GET","stroje-vypis.php?kategorie="+kategorie,true);
  xmlhttp.send();
}