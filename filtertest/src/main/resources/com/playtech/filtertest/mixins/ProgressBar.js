var ProgressBar = Class.create();
ProgressBar.prototype = {
		initialize: function(element, fullTime) {
			this.fullTime = fullTime;
			this.element = element;
			start = this.start.bind(this);
			new PeriodicalExecuter(function(pe){
        		if(document.loaded == true){
        			pe.stop();
        			start();
        		}
        	}, 0.5);
        },
        
        start: function(){
        	this.statusIndicator = $('progressBarFrame');
        	this.whiteOverlay = $('progressBarWhiteOverlay');
        	Event.observe($(this.element), 'click', this.show.bindAsEventListener(this));
        	Event.observe(window, 'scroll', this.center.bindAsEventListener(this));
        	Event.observe(window, 'resize', this.center.bindAsEventListener(this));
        },
        
        show: function() {
        	this.center(this.statusIndicator);
        	this.statusIndicator.show();
        	whiteOverlay = this.whiteOverlay;
        	progress = 0;
        	width = whiteOverlay.getWidth();
        	time = this.fullTime;
        	updatestep = 0.05; // in second
        	step = (width / time);
        	startTime = (new Date).getTime();
        	new PeriodicalExecuter(function() {
        		newWidth = width - parseInt(((new Date).getTime() - startTime) * step / 1000);
        		if(newWidth > width){
        			newWidth = 0;
        		}
        		newWidth = newWidth + 'px';
        		whiteOverlay.setStyle({width: newWidth});
        	}, updatestep);
        },
        
        center: function(){
        	barHeight = this.statusIndicator.getHeight();
        	barWidth = this.statusIndicator.getWidth();
        	barTop = parseInt((document.viewport.getHeight()/2) - (barHeight/2)) +
        	document.viewport.getScrollOffsets()[1]+ 'px';
        	barLeft = parseInt((document.viewport.getWidth()/2) - (barWidth/2)) +
        	document.viewport.getScrollOffsets()[0] + 'px';
        	this.statusIndicator.setStyle({top: barTop, left: barLeft, zIndex: '5'});
        }
}
