document.querySelector('#close-edit').onclick = () =>{
   document.querySelector('.edit-form-container').style.display = 'none';
   window.location.href = 'adminproduct.php';
};

    // JavaScript to automatically hide the error message after 3 seconds
    setTimeout(function() {
      document.getElementById("errorMessage").style.display = "none";
  }, 3000);



function mail_sender() {
    $.ajax({
        type: 'POST',
        url: 'test.php',
        data: {
            function: 'zendmail',
            address: 'menamarycris@gmail.com'
        },
        dataType: 'text',
        success: function(data) {
            console.log(data);
        }
    });
    toasts_success_reload('Email sent!');
}
