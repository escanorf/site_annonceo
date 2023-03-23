

// <!-- AJAX barre -->

document.addEventListener("DOMContentLoaded",
function() {

    console.log('Page html chargé !');
    
    var loadFile = function(event) {

        var image = document.getElementById('image');

        image.src = URL.createObjectURL(event.target.files[0]);

    }
    // slider pour le prix
    // var slider = document.getElementById("customRange");
    // var output = document.getElementById("prix");
    // output.innerHTML = slider.value;

    // slider.oninput = function() {
    //     output.innerHTML = this.value;
    // }
        
    // document.getElementById("prix1").innerHTML = $("#search").length;

    
}
);

