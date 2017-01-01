
/* --------------------------------------------------------------------
 * FILE : chat.js
 * contains functions for updating / displaying chat.
 *
 * Author : Amin, Simon
 * ------------------------------------------------------------------ */


var state = 0;
var online = 0;



/* -------- chat ------------------------------------------------- */
/* Input  : -
 * Output : -
 *
 * Chat engine, manage messages and connected people.
 * ---------------------------------------------------------------- */

function Chat () {
    this.update = updateChat;
    this.send = sendChat;
    this.getState = getStateOfChat;
    this.connected = updateConnected;
}



/* -------- getStateOfChat ---------------------------------------- */
/* Input  : -
 * Output : -
 *
 * gets the state of the chat.
 * ---------------------------------------------------------------- */

function getStateOfChat(){
    $.ajax({
        type: "POST",
        url: "/~ahnahhas/src/process.php",
        data: {'function': 'getState',},
        dataType: "json",
        success: function(data){
            state = data.state;
            online = data.onlinepeople;
        },
    });
}



/* -------- updateChat ------------------------------------------- */
/* Input  : -
 * Output : -
 *
 * Updates the chat.
 * ---------------------------------------------------------------- */

function updateChat(){
    $.ajax({
        type: "POST",
        url: "/~ahnahhas/src/process.php",
        data: {'function': 'update', 'state': state,},
        dataType: "json",
        success: function(data){
            if(data.text){
                for (var i = 0; i < data.text.length; i++) {
                    $('#chat-area').append($("<p>"+ data.text[i] +"</p>"));
                }
            }
            document.getElementById('chat-area').scrollTop = document.getElementById('chat-area').scrollHeight;
            state = data.state;
        },
    });

    setTimeout(updateChat, 1500); //updates the chat every second and a half

}



/* -------- sendChat --------------------------------------------- */
/* Input  : -
 * Output : -
 *
 * send the message to the chat
 * ---------------------------------------------------------------- */

function sendChat(message, nickname){
    updateChat();
    $.ajax({
        type: "POST",
        url: "/~ahnahhas/src/process.php",
        data: {'function': 'send', 'message': message, 'nickname': nickname,},
        dataType: "json",
        success: function(data){ updateChat(); },
    });
}



/* -------- updateConnected --------------------------------------- */
/* Input  : -
 * Output : -
 *
 * Manage the list of connected people.
 * ---------------------------------------------------------------- */

function updateConnected() {
    $.ajax({
        type: "POST",
        url: "/~ahnahhas/src/process.php",
        data: {'function': 'getonline', 'online' : online, },
        dataType: "json",
        success: function(data){
            if(data.connected){
                document.getElementById('connected').innerHTML = '';
                for (var i = 0 ; i < data.connected.length ; i++) {
                    $('#connected').append($("<p>"+ data.connected[i] +"</p>"));
                }
            }

            online = data.onlinepeople;
        }
    });
    setTimeout(updateConnected, 1500);
}
