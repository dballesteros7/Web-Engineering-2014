/**
 * 
 */

function interactive($mainDiv, $thumbDiv){
    var currentX = 0;
    var startX = -1;
    var $imgObj = $mainDiv.find("img");
    var $thumbDivOverlay = $thumbDiv.find(".overlay");
    var containerWidth = $mainDiv.width();
    var imgWidth = $imgObj.width();
    var ratio = containerWidth / imgWidth;
    $thumbDivOverlay.width(ratio * $thumbDiv.width());
    $(window).on("resize", function(){
        containerWidth = $mainDiv.width();
        $imgObj.css("left", "0px");
        ratio = containerWidth / imgWidth;
        $thumbDivOverlay.width(ratio * $thumbDiv.width());
    });
    $mainDiv.on("mousemove",
            function(event){
                currentX = event.pageX;
                if (startX != -1) {
                    var horizontalMovement = currentX - startX;
                    horizontalMovement = horizontalMovement / 10;
                    var newPosition = Number($imgObj.css("left").slice(0, -2))
                            + horizontalMovement;
                    if (newPosition >= (containerWidth - imgWidth)
                            && newPosition <= 0) {
                        $imgObj.css("left", newPosition + "px");
                    }
                    var thumbNailPosition = (-newPosition / imgWidth)
                            * $thumbDiv.width();
                    var upperLimit = $thumbDiv.width()
                            - $thumbDivOverlay.width() - 0.4
                            * Number($thumbDiv.css("font-size").slice(0, -2));
                    if (thumbNailPosition < 0) {
                        thumbNailPosition = 0;
                    } else if (thumbNailPosition > upperLimit) {
                        thumbNailPosition = upperLimit;
                    }
                    $thumbDivOverlay.css("left", thumbNailPosition + "px");
                }
            });
    $mainDiv.on("mousedown", function(){
        startX = currentX;
    });
    $mainDiv.on("mouseup", function(){
        startX = -1;
    });
    $mainDiv.on("mouseleave", function(){
        startX = -1;
    });
    $thumbDiv.on("click", function(event){
        var xClickPosition = event.pageX;
        var xDivPosition = $thumbDiv.offset().left;
        var leftOverlayPos = (xClickPosition - xDivPosition)
                - $thumbDivOverlay.width() / 2;
        var upperLimit = $thumbDiv.width() - $thumbDivOverlay.width() - 0.4
                * Number($thumbDiv.css("font-size").slice(0, -2));
        if (leftOverlayPos < 0) {
            leftOverlayPos = 0;
        } else if (leftOverlayPos > upperLimit) {
            leftOverlayPos = upperLimit;
        }
        $thumbDivOverlay.css("left", leftOverlayPos + "px");
        var relPos = leftOverlayPos / $thumbDiv.width();
        var newPosition = -relPos * imgWidth;
        $imgObj.css("left", newPosition + "px");
    });
    var currentPos = 0;
    var direction = false; // False is left, true is right
    var speed = 200;
    var movementRange = 10;
    function animate(){
        if (startX == -1) {
            if (currentPos > (containerWidth - imgWidth + movementRange)
                    && !direction) {
                currentPos -= movementRange;
                $imgObj.animate({
                    left : currentPos + "px"
                }, movementRange * 1000 / speed, "linear", animate);
            } else if (currentPos < -movementRange && direction) {
                currentPos += movementRange;
                $imgObj.animate({
                    left : currentPos + "px"
                }, movementRange * 1000 / speed, "linear", animate);
            } else {
                direction = !direction;
                animate();
            }
        }
    }
    animate();
}
