/* -------------------------------------------------------------------- */
/* FILE : ScriptSign.js
 * listening forms in register and signin pages.
 *
 * Author : Amin, Simon
 * Date : december 2016 (last update)
 * ------------------------------------------------------------------ */


(function main(){
    // Get main variables to be listened
    var login = document.getElementById("login");
    var pass = document.getElementById("password");
    
    // listeners on variables
    login.addEventListener("input", function(){DynListen(login);}, false);
    pass.addEventListener("input", function(){DynListen(pass);}, false);
 


})()
