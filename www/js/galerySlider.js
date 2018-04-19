
//------------POSOUVANI Galerie------------
function posuvGalL(hraniceL, krokL)
  {
    var posunObjekt = "tableFotky";
    
    var aktPozL = document.getElementById(posunObjekt).offsetLeft;
    var novaPozL = (aktPozL - krokL - 70);
    
    if(novaPozL >= hraniceL)
    {
        document.getElementById(posunObjekt).style.left = novaPozL+"px";
    }
    else
    {
        window.clearInterval(posouvaniGalL);  
    } 
  }
function posuvGalR(hraniceR, krokR)
  {
    var posunObjekt = "tableFotky";
    
    var aktPozR = document.getElementById(posunObjekt).offsetLeft;
    var novaPozR = (aktPozR + krokR - 70);
    
    if(novaPozR <= hraniceR)
    {
        document.getElementById(posunObjekt).style.left = novaPozR+"px";
    }
    else
    {
        window.clearInterval(posouvaniGalR);  
    }   
  }
  
function posunGal(stranaG)
{
    var rychlost = 20;
    var krok = 45;
    var blok = 990;
    var posunObjekt = "tableFotky";
    var sirka = document.getElementById(posunObjekt).offsetWidth;
    var hraniceL = ((-1) * sirka) + blok + 70;
    var hraniceR = 0;
    var aktPoz = document.getElementById(posunObjekt).offsetLeft;
    
    if(stranaG == "L")
    {
        if(aktPoz > hraniceL)
        {        
            var hranice = aktPoz - blok - 70;
            
            posouvaniGalL=setInterval(function(){posuvGalL(hranice, krok)},rychlost);           
        }
    }
    else
    {
        if(aktPoz < hraniceR)
        {        
            var hranice = aktPoz + blok - 70;
            
            posouvaniGalR=setInterval(function(){posuvGalR(hranice, krok)},rychlost);           
        }
    }    
}
