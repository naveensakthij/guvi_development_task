$(document).ready(function() {
    // Submit the login form via AJAX when the submit button is clicked
    var sessionID = localStorage.getItem('session');
    var email = localStorage.getItem('email');
   
    if (sessionID && email) {
      $.ajax({
        url: 'php/login.php',
        type: 'POST',
        dataType: 'json',
        data: { session: sessionID, email: email ,type:"check"},
        success: function(response) {
        },
        error: function(xhr, textStatus) {
           
          if(xhr.status!=200){
            window.location.href = 'login.html';
          }
          else{

            console.log("You are already Logged in");
            $.ajax({
              url: 'php/profile.php',
              type: 'POST',
              dataType: 'json',
              data: { email: email , type:"getinfo"},
              success: function(data) {
                $('#name').val(data.name);
                $('#dob').val(data.dob);
                $('#contact').val(data.contact);
          
                if(data.name.length>0){
                  $('#username').text(data.name);

                }


              },
              error: function(xhr, status, error) {
             
                console.log('Error: ' + error);
               
              }
            });
          }

        }
      });
    }
    else{

      window.location.href = 'login.html';
    }
    $("#logout").click(function(event) {
      var email = localStorage.getItem('email');
 
      console.log(email);
      if (sessionID && email) {
        $.ajax({
          url: 'php/profile.php',
          type: 'POST',
          dataType: 'json',
          data: {email: email ,type:"logout"},
          success: function(response) {
            localStorage.removeItem("email");
            localStorage.removeItem("session");
            window.location.href = 'login.html';
          },
          error: function(xhr, textStatus) {
             
            if(xhr.status==200){
              localStorage.removeItem("email");
              localStorage.removeItem("session");
              window.location.href = 'login.html';
            }
            else{
            console.log(textStatus)

            }
  
          }
        });
      }
    });
    $("#update").click(function(event) {
      var email = localStorage.getItem('email');

      console.log(email);
      if (sessionID && email) {
        $.ajax({
          url: 'php/profile.php',
          type: 'POST',
          dataType: 'json',
          data: {
            email: email ,
            type:"update",
         
            dob: $("#dob").val(),
            contact: $("#contact").val(),
            name:$("#name").val(),
          
          },
          success: function(response) {
           alert("Updated");
           window.location.reload();
          },
          error: function(xhr, textStatus) {
             
            alert("Updated");
            window.location.reload();
  
          }
        });
      }
    });
});