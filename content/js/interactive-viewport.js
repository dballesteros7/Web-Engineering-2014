/**
 * Define an object that contains all the functionality of the interactive
 * viewport without polluting the global namespace. This object requires two
 * arguments for initialization:<br>
 * 1. $mainDiv refers to a div containing a img object that the defines the
 * image that can be panned and zoomed.<br>
 * 2. $thumbDiv is another div containing a img object which is a smaller
 * version of the previous one and allows selection of the current viewport.
 * E.g. var interactiveViewport = new interactivePlugin($("#main-image"),
 * $("#thumbnail"));
 */
function interactivePlugin($mainDiv, $thumbDiv){

    // ========================================================================
    // Private members
    // ========================================================================
    /**
     * jQuery element containing the main image in the page
     */
    var $imgObj = $mainDiv.find("img");
    /**
     * jQuery element that refers to the overlayed box in the thumbnail
     */
    var $thumbDivOverlay = $thumbDiv.find(".overlay");
    /**
     * Width of the main image container. Number (units are pixels).
     */
    var containerWidth = $mainDiv.width();
    /**
     * Width of the contained image object in the main div. Number (units are
     * pixels).
     */
    var imgWidth = $imgObj.width();
    /**
     * Width of the container div for the thumbnail. Number (units are in
     * pixels).
     */
    var thumbContainerWidth = $thumbDiv.width();
    /**
     * Width of the thumbnail overlay element. Number (units are in pixels).
     */
    var overlayWidth = $thumbDivOverlay.width();
    /**
     * Utility variable that refers to the fractional ratio between the
     * container and the main image width.
     */
    var ratio = containerWidth / imgWidth;
    /**
     * Direction of the animation (-1 or +1).
     */
    var direction = -1;
    /**
     * Result of the last movement in the animation (to find the edges).
     */
    var lastMoveResult = true;
    /**
     * Absolute start point of the movement
     */
    var startPoint = {};
    /**
     * Absolute end point of the movement
     */
    var endPoint = {};
    // ========================================================================
    // Private functions
    // ========================================================================
    /**
     * Function that recalculates the widths of the objects used by the viewport
     * after the window has been resized. This function allows caching these
     * widths to avoid calling them on every response to an event.
     * 
     * This function also give the proper size to the overlay object using the
     * ratio from the main div and image as reference.
     * 
     * Finally it resets the position of the viewport and the overlay.
     */
    var adaptToResize = function(){
        // Recalculate widths and ratio
        containerWidth = $mainDiv.width();
        imgWidth = $imgObj.width();
        ratio = containerWidth / imgWidth;

        // Resize the thumb div accordingly
        thumbContainerWidth = $thumbDiv.width();
        $thumbDivOverlay.width(ratio * thumbContainerWidth);
        overlayWidth = $thumbDivOverlay.width();

        // Finally, reset the viewport and overlay
        $imgObj.css("left", "0px");
        $thumbDivOverlay.css("left", "0px");
    };

    /**
     * Utility function to convert a CSS pixel value such as "0px" into a number
     * like 0.
     */
    var pxtonum = function(value){
        return Number(value.slice(0, -2));
    };
    /**
     * Function that moves the viewport in the horizontal direction for the
     * amount of pixels given in the offset argument divided by the speed.
     * Positive values indicate that the image and viewport is moved to the
     * left, negative values indicate the opposite.
     */
    var panViewport = function(offset, speed, animated, animationDuration){
        var successfulMovement = true;
        if (typeof speed === "undefined") {
            speed = 10; // Default speed of 1 pixel of image per pixel of
            // movement
        }
        var movementUnits = offset / speed;
        var mainImgPosition = pxtonum($imgObj.css("left")) + movementUnits;
        if (mainImgPosition < (containerWidth - imgWidth)) {
            mainImgPosition = (containerWidth - imgWidth);
            successfulMovement = false;
        } else if (mainImgPosition > 0) {
            mainImgPosition = 0;
            successfulMovement = false;
        }
        if (!animated) {
            $imgObj.css("left", mainImgPosition + "px");
        } else {
            $imgObj.animate({
                left : mainImgPosition + "px"
            }, animationDuration, "linear");
        }
        var thumbNailPosition = (-mainImgPosition / imgWidth)
                * thumbContainerWidth;
        var upperLimit = thumbContainerWidth - overlayWidth - 0.8
                * pxtonum($thumbDiv.css("font-size"));
        if (thumbNailPosition < 0) {
            thumbNailPosition = 0;
        } else if (thumbNailPosition > upperLimit) {
            thumbNailPosition = upperLimit;
        }
        if (!animated) {
            $thumbDivOverlay.css("left", thumbNailPosition + "px");
        } else {
            $thumbDivOverlay.animate({
                left : thumbNailPosition + "px"
            }, animationDuration, "linear");
        }
        return successfulMovement;
    };

    /**
     * Animation function that moves the viewport left and right 10px on every
     * call, this checks if the end of movement has been reached to changed the
     * direction of the movement.
     */
    var animate = function(){
        if (lastMoveResult) {
            lastMoveResult = panViewport(direction * 10, 1);
        } else {
            direction = -direction;
            lastMoveResult = true;
        }
    };

    /**
     * Inertia function, this moves the viewport for a while after the movement
     * ends using negative acceleration and some given stopping time. This
     * depends on the setting of the endPoint and startPoint variables.
     */
    var inertia = function(acceleratioMagnitude, millisecondsToStop){
        if (startPoint.time && startPoint.position) {
            // Calculate the initial speed based on the user movement
            var initialSpeed = (endPoint.position - startPoint.position)
                    / (endPoint.time - startPoint.time);
            // Get the direction of the movement
            var speedSign = initialSpeed ? initialSpeed < 0 ? -1 : 1 : 0;
            // Set the acceleration with direction
            var acceleration = -speedSign * acceleratioMagnitude;
            // The movement before stopping can be calculated using linear
            // motion equation x = vt + at^2/2
            var finalOffset = initialSpeed * millisecondsToStop + acceleration
                    * millisecondsToStop * millisecondsToStop / 2;
            // Call the pan viewport with animation
            panViewport(finalOffset, 1, true, millisecondsToStop);
            // Reset the start point
            startPoint = {};
        }
    };

    // ========================================================================
    // Public functions
    // ========================================================================
    this.activate = function(){
        var currentX = 0;
        var startX = -1;

        // Initialize the size variables
        adaptToResize();
        // Initialize the animation
        // Run animate every 10ms for a speed of 5px/ms
        var animationId = window.setInterval(animate, 50);

        // Install listeners
        $(window).on("resize", adaptToResize); // On resize, adapt the
        // variables and objects
        $mainDiv.on("mousemove", function(event){
            currentX = event.pageX; // Get the current movement change
            if (startX != -1) { // Only do anything if the user is dragging
                var horizontalMovement = currentX - startX; // Calculate
                // movement so far
                panViewport(horizontalMovement, 1); // Pan the viewport by that
                // amount
                startX = currentX; // For the next movement, change the
                // starting point
            }
        });
        $mainDiv.on("mousedown", function(){
            startX = currentX; // Start tracking movement when the user clicks
            window.clearInterval(animationId); // Stop any ongoing animation
            // Record the absolute start point of the movement
            startPoint.time = new Date().getTime();
            startPoint.position = startX;
        });
        $mainDiv.on("mouseup", function(){
            startX = -1; // Stop tracking movement when the user stops the
            // drag
            endPoint.time = new Date().getTime();
            endPoint.position = currentX;
            // Call the inertia function with an acceleration of 1000px/s^2 and
            // a stop time of 400ms
            inertia(1e-9, 400);
        });
        $mainDiv.on("mouseleave", function(){
            startX = -1; // Stop tracking if the user leaves the viewport
            endPoint.time = new Date().getTime();
            endPoint.position = currentX;
            // Call the inertia function with an acceleration of 1000px/s^2 and
            // a stop time of 400ms
            inertia(1e-9, 400);
        });
        $thumbDiv.on("click", function(event){
            var xClickPosition = event.pageX; // This is the desired new
            // center for the viewport
            var currentCenter = $thumbDivOverlay.offset().left + overlayWidth
                    / 2; // This is the current center of the overlay
            // This is the amount of pixels for the displacement, transformed to
            // the scale of the main container
            var horizontalDisplacement = (currentCenter - xClickPosition)
                    * containerWidth / thumbContainerWidth;
            panViewport(horizontalDisplacement, 1);
            window.clearInterval(animationId); // Stop any ongoing animation
        });
    };
}
