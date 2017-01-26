/**
 * Created by sydney_manjaro on 26/01/17.
 */

window.onload = function () {
    eventClickListener();
};

function eventClickListener() {
    var events = document.getElementsByClassName('event');
    for( var i=0; i < events.length; i++){
        events[i].addEventListener('click', function (event) {
            console.log('event:', event, 'nodeValue:', event.target.getAttribute('name'));
        });
    }
}