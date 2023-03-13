$(document).ready(function() {
	// Submit the login form via AJAX when the submit button is clicked
 
	$(".btn,.btn-primary,.submit").click(function(event) {
       
      if($("#password").val()==$("#conpassword").val() && /\S+@\S+\.\S+/.test($("#email").val())){
    

          $.post("php/register.php",
  {
    email: $("#email").val(),
    password: $("#password").val()
  },
  function(data, status){
    alert("Data: " + data + "\nStatus: " + status);
    window.location.href = 'login.html';
  
    
  });
      }
      else{


        alert("Password Not Matching Or Email is incorrect");
      }
       
		
	});
});