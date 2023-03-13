$(document).ready(function() {
    // Submit the login form via AJAX when the submit button is clicked
    var sessionID = localStorage.getItem('session');
    var email = localStorage.getItem('email');
    console.log(sessionID);
    console.log(email);
    if (sessionID && email) {
      $.ajax({
        url: 'php/login.php',
        type: 'POST',
        dataType: 'json',
        data: { session: sessionID, email: email ,type:"check"},
        success: function(response) {
      
            
            window.location.href = 'profile.html';

        
            
          
         
         
        },
        error: function(xhr, textStatus) {
           
          if(xhr.status==200){
            window.location.href = 'profile.html';
          }

        }
      });
    }
    $(".btn,.btn-primary,.submit").click(function(event) {
      if(/\S+@\S+\.\S+/.test($("#email").val())){
  $.ajax({
    url: "php/login.php",
    type: "POST",
    data: {
      email: $("#email").val(),
      password: $("#password").val(),
      type:"auth"
    },
    xhrFields: {
      withCredentials: true
    },
    success: function(response) {
            localStorage.setItem("session", response);
            localStorage.setItem("email", $("#email").val());
            window.location.href = 'profile.html';
            
    
        
    },
    error: function(xhr, status, error) {
      console.log(error);
    }
  });




      }
      else{


        alert("Email is incorrect");
      }
      
        
    });
});