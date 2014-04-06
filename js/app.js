;
(function (appName) {
	var app = window[appName] = window[appName] || {};
	if (window[appName] && window[appName].ready) {
		return app.run();
	}

	function getScrollXY() {
		var scrOfX = 0, scrOfY = 0;
		if (typeof( window.pageYOffset ) == 'number') {
			//Netscape compliant
			scrOfY = window.pageYOffset;
			scrOfX = window.pageXOffset;
		} else if (document.body && ( document.body.scrollLeft || document.body.scrollTop )) {
			//DOM compliant
			scrOfY = document.body.scrollTop;
			scrOfX = document.body.scrollLeft;
		} else if (document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop )) {
			//IE6 standards compliant mode
			scrOfY = document.documentElement.scrollTop;
			scrOfX = document.documentElement.scrollLeft;
		}
		return {left: scrOfX, top: scrOfY };
	}

	var getScript = function (src, callback) {
		var el = document.createElement('script');

		el.type = 'text/javascript';
		el.async = false;
		el.src = src;

		/**
		 * Ensures callbacks work on older browsers by continuously
		 * checking the readyState of the request. This is defined once
		 * and reused on subsequeent calls to getScript.
		 *
		 * @param  {Element}   el      script element
		 * @param  {Function} callback onload callback
		 */
		getScript.ieCallback = getScript.ieCallback || function (el, callback) {
			if (el.readyState === 'loaded' || el.readyState === 'complete') {
				callback();
			} else {
				setTimeout(function () {
					getScript.ieCallback(el, callback);
				}, 100);
			}
		};

		if (typeof callback === 'function') {
			if (typeof el.addEventListener !== 'undefined') {
				el.addEventListener('load', callback, false);
			} else {
				el.onreadystatechange = function () {
					el.onreadystatechange = null;
					getScript.ieCallback(el, callback);
				};
			}
		}

		// This is defined once and reused on subsequeent calls to getScript
		getScript.firstScriptEl = getScript.firstScriptEl || document.getElementsByTagName('script')[0];
		getScript.firstScriptEl.parentNode.insertBefore(el, getScript.firstScriptEl);
	};

	var JSONP = function (global) {
		function JSONP(uri, callback) {
			function JSONPResponse() {
				try {
					delete global[src];
				} catch (e) {
					global[src] = null;
				}
				documentElement.removeChild(script);
				callback.apply(this, arguments);
			}

			var src = prefix + id++;
			var script = document.createElement("script");
			global[src] = JSONPResponse;
			documentElement.insertBefore(script, documentElement.lastChild).src = uri + "=" + src;
		}

		var
			id = 0,
			prefix = "__JSONP__",
			document = global.document,
			documentElement = document.documentElement
			;
		return JSONP;
	}(window);

	function getClientSize() {
		if (window.innerWidth != undefined) {
			return {
				width: window.innerWidth,
				height: window.innerHeight
			};
		}
		else {
			var B = document.body,
				D = document.documentElement;
			return {
				width: Math.max(D.clientWidth, B.clientWidth),
				height: Math.max(D.clientHeight, B.clientHeight)
			};
		}
	}

	function stringfy(obj) {
		var str = "";
		for (var key in obj) {
			if (str != "") {
				str += "&";
			}
			str += key + "=" + encodeURIComponent(obj[key]);
		}
		return str;
	}

	function bind(el, evt, func, bool) {
		if (el.addEventListener) {
			el.addEventListener(evt, func, bool || false);
		} else if (el.attachEvent) {
			el.attachEvent('on' + evt, func);
		}
	}

	function unbind(el, evt, func, bool) {
		if (el.removeEventListener) {
			el.removeEventListener(evt, func, bool || false);
		} else if (el.detachEvent) {
			el.detachEvent('on' + evt, func);
		}
	}

	app.ready = true;

	var events = {
		t: {},

		closePopup: function (e) {
			e.preventDefault ? e.preventDefault() : e.returnValue = false;
			popup.close();
		},

		divMove: function (e) {
			var scroll = getScrollXY();
			var popupContainer = document.getElementById(appName + '-container');
			popupContainer.style.top = (e.clientY - events.t.y + scroll.top) + 'px';
			popupContainer.style.left = (e.clientX - events.t.x + scroll.left) + 'px';
			popupContainer.style.marginTop = '0';
			popupContainer.style.marginLeft = '0';
		},

		mouseUp: function (e) {
			if (e.which != 1) return;
			e.preventDefault();
			e.stopPropagation();
			var popupFrame = document.getElementById(appName + '-frame');
			popupFrame.style.display = 'block';
			var popupContainer = document.getElementById(appName + '-container');
			popupContainer.style.opacity = '1';
			unbind(window, 'mousemove', events.divMove, true);
		},

		mouseDown: function (e) {
			if (e.which != 1) return;
			e.preventDefault();
			e.stopPropagation();
			events.t.x = e.layerX;
			events.t.y = e.layerY;
			var popupFrame = document.getElementById(appName + '-frame');
			popupFrame.style.display = 'none';
			var popupContainer = document.getElementById(appName + '-container');
			popupContainer.style.opacity = '0.5';
			popupContainer.style.position = 'absolute';
			bind(window, 'mousemove', events.divMove, true);
		}
	}

	var popup = {
		create: function () {
			var popupWindow = document.createElement('div');
			popupWindow.id = appName + "-container";
			popupWindow.className = appName + "-block";
			popupWindow.draggable = "true"

			var popupCloseBtn = document.createElement('div');
			popupCloseBtn.id = appName + "-cancel";
			bind(popupCloseBtn, 'click', function (e) {
				e = e || window.event;
				e.preventDefault ? e.preventDefault() : e.returnValue = false;
				popup.close();
			});
			popupWindow.appendChild(popupCloseBtn);

			var popupWindowHeader = document.createElement('div');
			popupWindowHeader.id = appName + "-header";
			popupWindow.appendChild(popupWindowHeader);

			var popupFrame = document.createElement('iframe');
			popupFrame.id = appName + "-frame";
			popupFrame.src = "";
			popupFrame.style.cssText = 'padding:0; margin:0; border:0;';
			popupFrame.scrolling = 'no';
			popupFrame.frameBorder = 0;
			popupWindow.appendChild(popupFrame);

			document.body.appendChild(popupWindow);

			bind(popupWindow, 'close', function () {
				popup.close();
			});
		},

		close: function () {
			var popupWindow = document.getElementById(appName + '-container');
			popupWindow.style.display = 'none';
		},

		show: function () {
			var popupWindow = document.getElementById(appName + '-container');
			popupWindow.style.display = 'block';
		},

		fixTop: function () {
			var popupWindow = document.getElementById(appName + '-container');
			if (popupWindow.style.position != 'absolute') {
				popupWindow.style.marginTop = (popupWindow.offsetHeight * -1 / 2) + 'px';
			}
		},

		load: function (url, onload) {
			var frame = document.getElementById(appName + '-frame');
			bind(frame, 'load', onload);
			frame.src = url;
		}
	}

	var css = document.createElement('LINK');
	css.rel = 'stylesheet';
	css.href = 'http://applet.landgraf-paul.com/css/bootstrap.css';
	css.type = 'text/css';
	document.getElementsByTagName('head')[0].appendChild(css);

	popup.create();

	bind(window, "message", function (e) {
		if (e.origin !== 'http://applet.landgraf-paul.com') {
			return;
		}

		var kv = e.data.split('::');

		switch (kv[0]) {
			case "close":
				return popup.close();
				break;
			case "qty":
				app.parseSite().done(function (params) {
					params.id = 0;
					params.qty = kv[1];

					app.parsePrice(params.qty, params.in_stock).done(function (ext) {
						params.price = ext.price;
						params.currency = ext.currency;
						popup.load('http://applet.landgraf-paul.com/form.php?' + stringfy(params), function () {
							popup.show();
						});
					});
				});
				break;
			case "height":
				var popupFrame = document.getElementById(appName + '-frame');
				var popupWindow = document.getElementById(appName + '-container');

				if (kv[1]) {
					popupFrame.style.height = (parseFloat(kv[1]) + 31) + 'px';
					popupWindow.style.height = (parseFloat(kv[1]) + 31) + 'px';
					setTimeout(function () {
						popup.fixTop();
					}, 0);
				}
				break;
			case "title":
				var popupWindow = document.getElementById(appName + '-header');
				popupWindow.innerHTML = '<span>' + kv[1] + '</span>';
				break;
		}
	});
	bind(document.getElementById(appName + '-header'), "mousedown", events.mouseDown);
	bind(window, "mouseup", events.mouseUp);
	//---------------------------------------------------------

	app.run = function () {
		popup.close();

		try {
			getScript('http://applet.landgraf-paul.com/js/parser.js', function () {
				app.parseSite().done(function (params) {
					params.qty = 1;
					app.parsePrice(params.qty, params.in_stock).done(function (ext) {
						params.price = ext.price;
						params.currency = ext.currency;
						popup.load('http://applet.landgraf-paul.com/form.php?' + stringfy(params), function () {
							popup.show();
						});
					});
				});
			});
		}
		catch (e) {
			console.log(e);
			return alert(e.message);
		}
	}

	if (document.readyState === "complete") {
		app.run();
	} else {
		bind(window, 'load', app.run);
	}
})('appletApp');