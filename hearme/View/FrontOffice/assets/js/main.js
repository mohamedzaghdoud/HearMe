document.addEventListener('DOMContentLoaded', function(){
  var reg = document.getElementById('registerForm');
  if (reg) {
    reg.addEventListener('submit', function(e){
      var u = document.getElementById('username').value.trim();
      var p = document.getElementById('password').value;
      var pc = document.getElementById('password_confirm').value;
      var errs = [];
      if (u.length < 3) errs.push('Nom trop court (3+).');
      if (p.length < 8) errs.push('Mot de passe trop court (8+).');
      if (p !== pc) errs.push('Confirmation différente.');
      if (errs.length) { e.preventDefault(); alert(errs.join('\n')); }
    });
  }
});
