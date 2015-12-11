$(document).ready(function(){
  $("td").hover(function(){
    var sub =  document.getElementById("formval");
    var name = sub.user.value;
    var pwd = sub.pass.value;
    if (name == "" || pwd == "" || name.length > 20 || pwd.length > 20 || !name.match(/^[a-zA-Z0-9_.-]*$/) || !pwd.match(/^[a-zA-Z0-9_.-]*$/)) {
      $('#sub').attr('disabled', 'disabled');
    }
    else {
      $('#sub').removeAttr('disabled');
    }
  });
});
