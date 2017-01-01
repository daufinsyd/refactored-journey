/* --------------------------------------------------------------------
 * FILE : Dynlisten.js
 * contains functions for form listener
 *
 * Author : Amin, Simon
 * ------------------------------------------------------------------ */



/* -------- DynListen --------------------------------------------- */
/* Input  : an element to listen
 * Output : true if elt matches a regex, false otherwise.
 *
 * Listen an elt to check whether its value matches a regular
 * expression or not. The value must be 5 to 15 char longs, and
 * may only contain letters or numbers.
 * ---------------------------------------------------------------- */

function DynListen(elt){
    var regex = new RegExp("^[a-zA-Z0-9]{5,15}$");
    elt.className = "valid";

    if (!regex.test(elt.value)) {
         if(elt.value.length != 0){
             elt.className = "invalid";
         }

         return false;

    } else {
        return true;

    }
};
