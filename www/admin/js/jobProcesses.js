// JavaScript Document
var tagNr = 0;
var datalistTag_end = '</datalist>';


var option = "";
for(var i = 0; i < allTagList.length; i++){
    option += '<option value="' + allTagList[i] + '">';
}

//proběhne v případě, že uživatel není na stránce novaAktualita.php
//vypíše všechny tagy, které již byly v minulosti k dané aktualitě přiřazeny
if(!newJob){
    currTagList.forEach(function(){
        var inputTag = '<input id="selectedTag_' + tagNr + '" class="form-control" list="tags_' + tagNr + '" name="tag_' + tagNr + '" placeholder="Vyberte tag"/>';
        var datalistTag_start = '<datalist id="tags_' + tagNr + '">';
        $("#tagsToProcessed").append(inputTag+datalistTag_start+option+datalistTag_end);
        $("#selectedTag_" + tagNr).val(currTagList[tagNr]);
        tagNr++;
        console.log("gfds")
    });
}

$("#appendField").click(function(){
    var inputTag = '<input id="selectedTag_' + tagNr + '" class="form-control" list="tags_' + tagNr + '" name="tag_' + tagNr + '" placeholder="Vyberte tag"/>';
    var datalistTag_start = '<datalist id="tags_' + tagNr + '">';
    $("#tagsToProcessed").append(inputTag+datalistTag_start+option+datalistTag_end);
    tagNr++;
});


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


