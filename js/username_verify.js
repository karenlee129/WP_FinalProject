var xhr;
      if (window.ActiveXObject){
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
      }
      else if (window.XMLHttpRequest){
        xhr = new XMLHttpRequest();
      }

      function callServer(){
        // Create the phone number
        var user = document.getElementById("username").value;

        // Only make the server call if there is data
        if ((user == null) || (user == "")) return;

        // Build the URL to connect to
        var url = "../php/ajax_username_availibility.php?user=" +escape(user);

        // Open a connection to the server
        xhr.open("GET", url, true);

        // Setup a function for the server to run when it is done
        xhr.onreadystatechange = updatePage;

        // Send the request
        xhr.send(null);
      }

      function updatePage(){
         if ((xhr.readyState == 4) && (xhr.status == 200)){
            var response = xhr.responseText;
         }
         if(response){
            window.alert("Sorry! This username has already been taken.");
         }
      }