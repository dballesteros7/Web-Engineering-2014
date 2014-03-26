/**
 * 
 */

$(window).ready(
        function(){
            // Initialize a new interactive viewport wrapper around the given
            // images and activate the functionality
            var interactiveViewport = new interactivePlugin($("#main-image"),
                    $("#thumbnail"));
            interactiveViewport.activate();
        });