$(function(){$("form[name='novyUzivatel']").validate({rules:{firstname:"required",surname:"required",email:{required:true,email:true}},messages:{firstname:"zadejte jméno",surname:"Zadejte příjmení",email:"Neplatná emailová adresa"},submitHandler:function(form){form.submit();}});$("form[name='novaGalerie']").validate({rules:{caption:"required",image:"required",galery_img_input:"required"},messages:{caption:"Není možno vytvořit galerii bez názvu",image:"Není možno vytvořit galerii bez coveru",galery_img_input:"Vlož do galerie alospoň jednu fotografii"},submitHandler:function(form){form.submit();}});$("form[name='updateGalerie']").validate({rules:{caption:"required"},messages:{caption:"Není možno vytvořit galerii bez názvu"},submitHandler:function(form){form.submit();}});$("form[name='zmenaUserUdaju']").validate({rules:{passwd_1:"required"},messages:{passwd_1:"zadejte heslo pro potvrzení",surname:"Zadejte příjmení",email:"Neplatná emailová adresa"},submitHandler:function(form){form.submit();}});$("form[name='prvniZmenaHesla']").validate({rules:{password_2:"required",password_1:"required"},messages:{password_1:" ",password_2:"Vyplňte obě pole"},submitHandler:function(form){form.submit();}});$("form[name='smazClena']").validate({rules:{userId:"required"},messages:{userId:"Není možné smazat člena"},submitHandler:function(form){if(!confirm("Uživatel bude smazán")){form.submit();}}});});