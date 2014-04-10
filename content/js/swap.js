
			$.touch.triggerMouseEvents = true;
			$.touch.preventDefault = false;

			$.touch.ready(function() {
				$('#header').touchable({
					gesture: function(e, touchHistory) {
						// simple gesture handler
						if (touchHistory.match({ finger: 0, deltaX: '<-100', time: '1..100' })) {
							message('simpleSwipeLeftHandler');
						}

						// more advanced touch handler
						var touches = $(this).touches();
						if (touches.length > 1) {
							
						} else {
						    var h = window.location.pathname.split('/').reverse()[0];						

	
							var th = touchHistory;
							console.log(th.size());
							
							if (th.match({ deltaX: '<-100' }) ) {
								if(h == "index.html")
									window.location.href = "index.html"	
								else if(h == "reviewers.html")
									window.location.href = "weekly.html"	
								else
									window.location.href = "reviewers.html"	
								

				     		} else if (th.match({ deltaX: '>100' })) {
								if(h == "index.html")
									window.location.href = "weekly.html"	
								else if(h == "weekly.html")
									window.location.href = "reviewers.html"	
								else
									window.location.href = "index.html"									
						}
					}
					}
				
			});
			
	    });
			
		$.touch.orientationChanged(function() {
				var width = $(window).width(),
					height = $(window).height();
				$('#header').attr({
					width: width,
					height: height,
				});
	    });