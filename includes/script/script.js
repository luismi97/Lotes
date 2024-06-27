var beforePan
var viewportWidth = $(window).width();

screenResize = function (size, viewport = false) {
    const heightSize = viewport ? size : size-200
    $(".mapContainerBox").width(size-10);
    $(".mapContainerBox").height(heightSize);
}

screenResizeLogic = function (size) {
    if(viewportWidth > 1000) {
        screenResize(1000);
    }else{
        screenResize(size-80, viewportWidth);
    }
}
screenResizeLogic(viewportWidth);

$(window).resize(function () {
    viewportWidth = $(window).width();
    screenResizeLogic(viewportWidth);
    panZoom.resize();
    panZoom.fit();
    panZoom.center();
})

beforePan = function (oldPan, newPan) {
    var limitPan = 1000;
    if (viewportWidth > 1000){
        limitPan = 800;
    }else{
        limitPan = viewportWidth-100;
    }
    var stopHorizontal = false
        , stopVertical = false
        , gutterWidth = limitPan
        , gutterHeight = limitPan
        , sizes = this.getSizes()
        , leftLimit = -((sizes.viewBox.x + sizes.viewBox.width) * sizes.realZoom) + gutterWidth
        , rightLimit = sizes.width - gutterWidth - (sizes.viewBox.x * sizes.realZoom)
        , topLimit = -((sizes.viewBox.y + sizes.viewBox.height) * sizes.realZoom) + gutterHeight
        , bottomLimit = sizes.height - gutterHeight - (sizes.viewBox.y * sizes.realZoom)
    customPan = {}
    customPan.x = Math.max(leftLimit, Math.min(rightLimit, newPan.x))
    customPan.y = Math.max(topLimit, Math.min(bottomLimit, newPan.y))

    return customPan
}

var eventsHandler;

        eventsHandler = {
          haltEventListeners: ['touchstart', 'touchend', 'touchmove', 'touchleave', 'touchcancel']
        , init: function(options) {
            var instance = options.instance
              , initialScale = 1
              , pannedX = 0
              , pannedY = 0

            // Init Hammer
            // Listen only for pointer and touch events
            this.hammer = Hammer(options.svgElement, {
              inputClass: Hammer.SUPPORT_POINTER_EVENTS ? Hammer.PointerEventInput : Hammer.TouchInput
            })

            // Enable pinch
            this.hammer.get('pinch').set({enable: true})

            // Handle double tap
            this.hammer.on('doubletap', function(ev){
              instance.zoomIn()
            })

            // Handle pan
            this.hammer.on('panstart panmove', function(ev){
              // On pan start reset panned variables
              if (ev.type === 'panstart') {
                pannedX = 0
                pannedY = 0
              }

              // Pan only the difference
              instance.panBy({x: ev.deltaX - pannedX, y: ev.deltaY - pannedY})
              pannedX = ev.deltaX
              pannedY = ev.deltaY
            })

            // Handle pinch
            this.hammer.on('pinchstart pinchmove', function(ev){
              // On pinch start remember initial zoom
              if (ev.type === 'pinchstart') {
                initialScale = instance.getZoom()
                instance.zoomAtPoint(initialScale * ev.scale, {x: ev.center.x, y: ev.center.y})
              }

              instance.zoomAtPoint(initialScale * ev.scale, {x: ev.center.x, y: ev.center.y})
            })

            // Prevent moving the page on some devices when panning over SVG
            options.svgElement.addEventListener('touchmove', function(e){ e.preventDefault(); });
          }

        , destroy: function(){
            this.hammer.destroy()
          }
        }

var panZoom = window.panZoom = svgPanZoom('#svgImage', {
    zoomEnabled: true,
    controlIconsEnabled: true,
    fit: 0,
    center: 1,
    zoomScaleSensitivity: 0.1,
    minZoom: 1,
    maxZoom: 10,
    customEventsHandler: eventsHandler,
    beforePan: beforePan
});