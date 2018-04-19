$(function() {
  // Initialize form validation on the registration form.
  // It has the name attribute "registration"
  $("form[name='novyUzivatel']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      firstname: "required",
      surname: "required",
      email: {
        required: true,
        // Specify that email should be validated
        // by the built-in "email" rule
        email: true
      }
    },
    // Specify validation error messages
    messages: {
      firstname: "zadejte jméno",
      surname: "Zadejte příjmení",
      email: "Neplatná emailová adresa"
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      form.submit();
    }
  });
  
  
  $("form[name='novaGalerie']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      caption: "required",
      image: "required",
      galery_img_input: "required"
    },
    // Specify validation error messages
    messages: {
      caption: "Není možno vytvořit galerii bez názvu",
      image: "Není možno vytvořit galerii bez coveru",
      galery_img_input: "Vlož do galerie alospoň jednu fotografii"
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      form.submit();
    }
  });
  
   $("form[name='updateGalerie']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      caption: "required"
    },
    // Specify validation error messages
    messages: {
      caption: "Není možno vytvořit galerii bez názvu"
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      form.submit();
    }
  });
  
  $("form[name='zmenaUserUdaju']").validate({
    rules: {
      passwd_1: "required"
    },
    messages: {
      passwd_1: "zadejte heslo pro potvrzení",
      surname: "Zadejte příjmení",
      email: "Neplatná emailová adresa"
    },
    submitHandler: function(form) {
      form.submit();
    }
  });
  
  
  $("form[name='prvniZmenaHesla']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      password_2: "required",
      password_1: "required"      
    },
    // Specify validation error messages
    messages: {
      password_1: " ",
      password_2: "Vyplňte obě pole"
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      form.submit();
    }
  });
  
  $("form[name='smazClena']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      userId: "required"
      
    },
    // Specify validation error messages
    messages: {
      userId: "Není možné smazat člena"
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
        if(!confirm("Uživatel bude smazán")){
            form.submit();
        }
    }
  });
  
  
});