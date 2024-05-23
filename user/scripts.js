document.querySelector('#close-edit').onclick = () =>{
   document.querySelector('.edit-form-container').style.display = 'none';
   window.location.href = 'adminproduct.php';
};

    // JavaScript to automatically hide the error message after 3 seconds
    setTimeout(function() {
      document.getElementById("errorMessage").style.display = "none";
  }, 3000);


