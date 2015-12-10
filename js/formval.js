$(document).ready(function(){
  $("td").hover(function(){
    var sub =  document.getElementById("formval");
    var name = sub.user.value;
    var pwd = sub.pass.value;
    if (name == "" || pwd == "" || name.length > 20 || pwd.length > 20 || !name.match(/^[0-9a-z]+$/) || !pwd.match(/^[0-9a-z]+$/)) {
      $('#sub').attr('disabled', 'disabled');
    }
    else {
      $('#sub').removeAttr('disabled');
    }
  });
});
