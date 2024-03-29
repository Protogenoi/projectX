window.FlashCanvasOptions = window.FlashCanvasOptions || {};
webshims.$.extend(FlashCanvasOptions, {
	swfPath: webshims.cfg.basePath + 'FlashCanvasPro/'
});

/*
 * FlashCanvas Pro
 *
 * Copyright (c) 2009      Tim Cameron Ryan
 * Copyright (c) 2009-2013 Shinya Muramatsu
 */
window.ActiveXObject && !window.CanvasRenderingContext2D && function (l, k, q) {
	function O(a) {
		this.code = a;
		this.message = ga[a]
	}

	function w(a, b, c) {
		if (!c) for (var c = [], d = 0, e = a * b * 4; d < e; ++d) c[d] = 0;
		this.width = a;
		this.height = b;
		this.data = c
	}

	function ha(a) {
		this.width = a
	}

	function x(a) {
		this.id = a.F++
	}

	function o(a) {
		this.J = a;
		this.id = a.F++
	}

	function A(a, b) {
		this.canvas = a;
		this.z = b;
		this.e = b.id.slice(8);
		this.G();
		this.F = 0;
		this.j = this.D = "";
		this.d = 0
	}

	function B() {
		if (k.readyState === "complete") {
			k.detachEvent(P, B);
			for (var a = k.getElementsByTagName(r),
					 b = 0, c = a.length; b < c; ++b) C.initElement(a[b])
		}
	}

	function Q() {
		var a = event.srcElement, b = a.parentNode;
		a.blur();
		b.focus()
	}

	function D() {
		event.button & 2 && event.srcElement.parentNode.setCapture()
	}

	function E() {
		event.button & 2 && event.srcElement.parentNode.releaseCapture()
	}

	function R() {
		var a = event.propertyName;
		if (a === "width" || a === "height") {
			var b = event.srcElement, c = b[a], d = parseInt(c, 10);
			if (isNaN(d) || d < 0) d = a === "width" ? 300 : 150;
			c === d ? (b.style[a] = d + "px", b.getContext("2d").K(b.width, b.height)) : b[a] = d
		}
	}

	function S() {
		l.detachEvent(T,
			S);
		for (var a in m) {
			var b = m[a], c = b.firstChild, d;
			for (d in c) typeof c[d] === "function" && (c[d] = g);
			for (d in b) typeof b[d] === "function" && (b[d] = g);
			c.detachEvent(U, Q);
			c.detachEvent(F, D);
			b.detachEvent(G, E);
			b.detachEvent(V, R)
		}
		l[W] = g;
		l[X] = g;
		l[Y] = g;
		l[H] = g;
		l[Z] = g
	}

	function ia(a) {
		return a.toLowerCase()
	}

	function i(a) {
		throw new O(a);
	}

	function $(a) {
		var b = parseInt(a.width, 10), c = parseInt(a.height, 10);
		if (isNaN(b) || b < 0) b = 300;
		if (isNaN(c) || c < 0) c = 150;
		a.width = b;
		a.height = c
	}

	function I(a, b) {
		for (var c in m) {
			var d = m[c].getContext("2d");
			d.g.push(d.a.length + 2);
			d.a.push(y, a, b)
		}
	}

	var g = null, r = "canvas", W = "CanvasRenderingContext2D", X = "CanvasGradient", Y = "CanvasPattern",
		H = "FlashCanvas", Z = "G_vmlCanvasManager", U = "onfocus", F = "onmousedown", G = "onmouseup",
		V = "onpropertychange", P = "onreadystatechange", T = "onunload", n;
	try {
		n = (new ActiveXObject("ShockwaveFlash.ShockwaveFlash")).GetVariable("$version").match(/[\d,]+/)[0].replace(/,/g, ".")
	} catch (ka) {
		n = 0
	}
	var j = l[H + "Options"] || {}, J = function () {
		var a = k.getElementsByTagName("script"), a = a[a.length - 1];
		return k.documentMode >=
		8 ? a.src : a.getAttribute("src", 4)
	}().replace(/[^\/]+$/, ""), t = j.swfPath || J;
	t += parseInt(n) > 9 ? "flash10canvas.swf" : "flash9canvas.swf";
	var y = "4", s = {}, u = {}, aa = {}, K = {}, p = {}, ba = {}, v = {}, m = {}, z = {},
		J = "autoinit" in j ? j.autoinit : 1, L = "turbo" in j ? j.turbo : 1, M = j.delay || 0,
		ca = j.disableContextMenu || 0, da = j.imageCacheSize || 100, N = j.usePolicyFile || 0,
		ea = j.proxy || "proxy.php", fa = j.save || "save.php";
	n === "10.1.53.64" && (L = 0, M = 30);
	A.prototype = {
		save: function () {
			this.h(15);
			this.I.push([this.m, this.n, this.w, this.l, this.q, this.o, this.p,
				this.r, this.u, this.v, this.s, this.t, this.j, this.A, this.B]);
			this.a.push("B")
		}, restore: function () {
			var a = this.I;
			if (a.length) a = a.pop(), this.globalAlpha = a[0], this.globalCompositeOperation = a[1], this.strokeStyle = a[2], this.fillStyle = a[3], this.lineWidth = a[4], this.lineCap = a[5], this.lineJoin = a[6], this.miterLimit = a[7], this.shadowOffsetX = a[8], this.shadowOffsetY = a[9], this.shadowBlur = a[10], this.shadowColor = a[11], this.font = a[12], this.textAlign = a[13], this.textBaseline = a[14];
			this.a.push("C")
		}, scale: function (a, b) {
			this.a.push("D",
				a, b)
		}, rotate: function (a) {
			this.a.push("E", a)
		}, translate: function (a, b) {
			this.a.push("F", a, b)
		}, transform: function (a, b, c, d, e, f) {
			this.a.push("G", a, b, c, d, e, f)
		}, setTransform: function (a, b, c, d, e, f) {
			this.a.push("H", a, b, c, d, e, f)
		}, createLinearGradient: function (a, b, c, d) {
			(!isFinite(a) || !isFinite(b) || !isFinite(c) || !isFinite(d)) && i(9);
			this.a.push("M", a, b, c, d);
			return new o(this)
		}, createRadialGradient: function (a, b, c, d, e, f) {
			(!isFinite(a) || !isFinite(b) || !isFinite(c) || !isFinite(d) || !isFinite(e) || !isFinite(f)) && i(9);
			(c <
				0 || f < 0) && i(1);
			this.a.push("N", a, b, c, d, e, f);
			return new o(this)
		}, createPattern: function (a, b) {
			a || i(17);
			var c = a.tagName, d, e, f, h = this.e;
			if (c) if (c = c.toLowerCase(), c === "img") d = a.getAttribute("src", 2); else if (c === r) e = this.C(a), f = a !== this.canvas; else if (c === "video") return; else i(17); else a.src ? d = a.src : i(17);
			b === "repeat" || b === "no-repeat" || b === "repeat-x" || b === "repeat-y" || b === "" || b === g || i(12);
			e || (e = u[h][d], (f = e === q) && (e = this.k(d)));
			this.a.push("O", e, b);
			f && s[h] && (this.f(), ++p[h]);
			return new x(this)
		}, clearRect: function (a,
								b, c, d) {
			this.a.push("X", a, b, c, d);
			this.b || this.c();
			this.d = 0
		}, fillRect: function (a, b, c, d) {
			this.h(1);
			this.a.push("Y", a, b, c, d);
			this.b || this.c();
			this.d = 0
		}, strokeRect: function (a, b, c, d) {
			this.h(6);
			this.a.push("Z", a, b, c, d);
			this.b || this.c();
			this.d = 0
		}, beginPath: function () {
			this.a.push("a")
		}, closePath: function () {
			this.a.push("b")
		}, moveTo: function (a, b) {
			this.a.push("c", a, b)
		}, lineTo: function (a, b) {
			this.a.push("d", a, b)
		}, quadraticCurveTo: function (a, b, c, d) {
			this.a.push("e", a, b, c, d)
		}, bezierCurveTo: function (a, b, c, d, e, f) {
			this.a.push("f",
				a, b, c, d, e, f)
		}, arcTo: function (a, b, c, d, e) {
			e < 0 && isFinite(e) && i(1);
			this.a.push("g", a, b, c, d, e)
		}, rect: function (a, b, c, d) {
			this.a.push("h", a, b, c, d)
		}, arc: function (a, b, c, d, e, f) {
			c < 0 && isFinite(c) && i(1);
			this.a.push("i", a, b, c, d, e, f ? 1 : 0)
		}, fill: function () {
			this.h(1);
			this.a.push("j");
			this.b || this.c();
			this.d = 0
		}, stroke: function () {
			this.h(6);
			this.a.push("k");
			this.b || this.c();
			this.d = 0
		}, clip: function () {
			this.a.push("l")
		}, isPointInPath: function (a, b) {
			this.a.push("m", a, b);
			return this.f() === "true"
		}, fillText: function (a, b, c, d) {
			this.h(9);
			this.g.push(this.a.length + 1);
			this.a.push("r", a, b, c, d === q ? Infinity : d);
			this.b || this.c();
			this.d = 0
		}, strokeText: function (a, b, c, d) {
			this.h(10);
			this.g.push(this.a.length + 1);
			this.a.push("s", a, b, c, d === q ? Infinity : d);
			this.b || this.c();
			this.d = 0
		}, measureText: function (a) {
			var b = z[this.e];
			try {
				b.style.font = this.font
			} catch (c) {
			}
			b.innerText = ("" + a).replace(/[ \n\f\r]/g, "\t");
			return new ha(b.offsetWidth)
		}, drawImage: function (a, b, c, d, e, f, h, ja, l) {
			a || i(17);
			var g = a.tagName, k, j, m, n = arguments.length, o = this.e;
			if (g) if (g = g.toLowerCase(),
			g === "img") k = a.getAttribute("src", 2); else if (g === r) j = this.C(a), m = a !== this.canvas; else if (g === "video") return; else i(17); else a.src ? k = a.src : i(17);
			j || (j = u[o][k], (m = j === q) && (j = this.k(k)));
			this.h(0);
			if (n === 3) this.a.push("u", n, j, b, c); else if (n === 5) this.a.push("u", n, j, b, c, d, e); else if (n === 9) (d === 0 || e === 0) && i(1), this.a.push("u", n, j, b, c, d, e, f, h, ja, l); else return;
			m && s[o] ? (this.f(), ++p[o]) : this.b || this.c();
			this.d = 0
		}, createImageData: function (a, b) {
			var c = Math.ceil;
			arguments.length === 2 ? ((!isFinite(a) || !isFinite(b)) &&
			i(9), (a === 0 || b === 0) && i(1)) : (a instanceof w || i(9), b = a.height, a = a.width);
			a = c(a < 0 ? -a : a);
			b = c(b < 0 ? -b : b);
			return new w(a, b)
		}, getImageData: function (a, b, c, d) {
			(!isFinite(a) || !isFinite(b) || !isFinite(c) || !isFinite(d)) && i(9);
			(c === 0 || d === 0) && i(1);
			this.a.push("w", a, b, c, d);
			a = this.f();
			c = typeof JSON === "object" ? JSON.parse(a) : k.documentMode ? eval(a) : a.slice(1, -1).split(",");
			a = c.shift();
			b = c.shift();
			return new w(a, b, c)
		}, putImageData: function (a, b, c, d, e, f, h) {
			a instanceof w || i(17);
			(!isFinite(b) || !isFinite(c)) && i(9);
			var g =
				arguments.length, j = a.width, k = a.height, l = a.data;
			g === 3 ? this.a.push("x", g, j, k, l.toString(), b, c) : g === 7 && ((!isFinite(d) || !isFinite(e) || !isFinite(f) || !isFinite(h)) && i(9), this.a.push("x", g, j, k, l.toString(), b, c, d, e, f, h));
			this.b || this.c();
			this.d = 0
		}, loadFont: function (a, b, c) {
			var d = this.e;
			if (b || c) v[d][a] = [a, b, c];
			this.g.push(this.a.length + 1);
			this.a.push("6", a);
			s[d] ? (this.f(), ++p[d]) : this.b || this.c()
		}, loadImage: function (a, b, c) {
			var d = a.tagName, e, f = this.e;
			if (d) d.toLowerCase() === "img" && (e = a.getAttribute("src", 2));
			else if (a.src) e = a.src;
			if (e && u[f][e] === q) {
				d = this.k(e);
				if (b || c) v[f][d] = [a, b, c];
				this.a.push("u", 1, d);
				s[f] && (this.f(), ++p[f])
			}
		}, G: function () {
			this.globalAlpha = this.m = 1;
			this.globalCompositeOperation = this.n = "source-over";
			this.fillStyle = this.l = this.strokeStyle = this.w = "#000000";
			this.lineWidth = this.q = 1;
			this.lineCap = this.o = "butt";
			this.lineJoin = this.p = "miter";
			this.miterLimit = this.r = 10;
			this.shadowBlur = this.s = this.shadowOffsetY = this.v = this.shadowOffsetX = this.u = 0;
			this.shadowColor = this.t = "rgba(0, 0, 0, 0.0)";
			this.font =
				this.j = "10px sans-serif";
			this.textAlign = this.A = "start";
			this.textBaseline = this.B = "alphabetic";
			this.a = [];
			this.I = [];
			this.i = [];
			this.g = [];
			this.b = g;
			this.H = 1
		}, h: function (a) {
			var b = this.a, c;
			if (this.m !== this.globalAlpha) b.push("I", this.m = this.globalAlpha);
			if (this.n !== this.globalCompositeOperation) b.push("J", this.n = this.globalCompositeOperation);
			if (this.u !== this.shadowOffsetX) b.push("T", this.u = this.shadowOffsetX);
			if (this.v !== this.shadowOffsetY) b.push("U", this.v = this.shadowOffsetY);
			if (this.s !== this.shadowBlur) b.push("V",
				this.s = this.shadowBlur);
			if (this.t !== this.shadowColor) c = this.t = this.shadowColor, ("" + c).indexOf("%") > 0 && this.i.push(b.length + 1), b.push("W", c);
			if (a & 1 && this.l !== this.fillStyle) c = this.l = this.fillStyle, typeof c === "string" ? (c.indexOf("%") > 0 && this.i.push(b.length + 1), b.push("L", c)) : (c instanceof o || c instanceof x) && b.push("L", c.id);
			if (a & 2 && this.w !== this.strokeStyle) c = this.w = this.strokeStyle, typeof c === "string" ? (c.indexOf("%") > 0 && this.i.push(b.length + 1), b.push("K", c)) : (c instanceof o || c instanceof x) && b.push("K",
				c.id);
			if (a & 4) {
				if (this.q !== this.lineWidth) b.push("P", this.q = this.lineWidth);
				if (this.o !== this.lineCap) b.push("Q", this.o = this.lineCap);
				if (this.p !== this.lineJoin) b.push("R", this.p = this.lineJoin);
				if (this.r !== this.miterLimit) b.push("S", this.r = this.miterLimit)
			}
			if (a & 8) {
				if (this.j !== this.font) a = z[this.e].offsetHeight, this.g.push(b.length + 2), b.push("o", a, this.j = this.font);
				if (this.A !== this.textAlign) b.push("p", this.A = this.textAlign);
				if (this.B !== this.textBaseline) b.push("q", this.B = this.textBaseline);
				if (this.D !==
					this.canvas.currentStyle.direction) b.push("1", this.D = this.canvas.currentStyle.direction)
			}
		}, c: function () {
			var a = this;
			a.b = setTimeout(function () {
				p[a.e] ? a.c() : (a.b = g, a.f(L))
			}, M)
		}, L: function () {
			clearTimeout(this.b);
			this.b = g
		}, f: function (a) {
			var b, c, d, e = this.i, f = this.g, h = this.a, g = this.z;
			if (h.length) {
				this.b && this.L();
				if (a) {
					for (b = 0, c = e.length; b < c; ++b) d = e[b], h[d] = encodeURI(h[d]);
					for (b = 0, c = f.length; b < c; ++b) d = f[b], h[d] = encodeURIComponent(h[d])
				} else for (b = 0, c = f.length; b < c; ++b) d = f[b], h[d] = ("" + h[d]).replace(/&/g, "&amp;").replace(/</g,
					"&lt;");
				b = h.join("\u0001");
				this.a = [];
				this.i = [];
				this.g = [];
				if (a) g.flashvars = "c=" + b, g.width = g.clientWidth + this.H, this.H ^= -2; else return g.CallFunction('<invoke name="executeCommand" returntype="javascript"><arguments><string>' + b + "</string></arguments></invoke>")
			}
		}, K: function (a, b) {
			this.f();
			this.G();
			if (a > 0) this.z.width = a;
			if (b > 0) this.z.height = b;
			this.a.push("2", a, b);
			this.b || this.c();
			this.d = 0
		}, C: function (a) {
			var b = a.getContext("2d").e, c = r + ":" + b;
			(a.width === 0 || a.height === 0) && i(11);
			if (b !== this.e && (a = m[b].getContext("2d"),
				!a.d)) b = ++ba[b], c += ":" + b, a.a.push("3", b), a.b || a.c(), a.d = 1;
			return c
		}, k: function (a) {
			var b = this.e, c = u[b], d = aa[b], e = c[a] = K[b]++;
			e >= da - 1 && (K[b] = 0);
			e in d && delete c[d[e]];
			this.g.push(this.a.length + 2);
			this.a.push("5", e, a);
			d[e] = a;
			return e
		}
	};
	o.prototype = {
		addColorStop: function (a, b) {
			(isNaN(a) || a < 0 || a > 1) && i(1);
			var c = this.J, d = this.id;
			("" + b).indexOf("%") > 0 && c.i.push(c.a.length + 3);
			c.a.push("y", d, a, b)
		}
	};
	O.prototype = Error();
	var ga = {
		1: "INDEX_SIZE_ERR", 9: "NOT_SUPPORTED_ERR", 11: "INVALID_STATE_ERR", 12: "SYNTAX_ERR", 17: "TYPE_MISMATCH_ERR",
		18: "SECURITY_ERR"
	}, C = {
		initElement: function (a) {
			if (a.getContext) return a;
			var b = Math.random().toString(36).slice(2) || "0", c = "external" + b;
			s[b] = 0;
			u[b] = {};
			aa[b] = [];
			K[b] = 0;
			p[b] = 1;
			ba[b] = 0;
			v[b] = [];
			$(a);
			a.innerHTML = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="' + location.protocol + '//fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="100%" height="100%" id="' + c + '"><param name="allowScriptAccess" value="always"><param name="flashvars" value="id=' + c +
				'"><param name="wmode" value="transparent"></object><span style="margin:0;padding:0;border:0;display:inline-block;position:static;height:1em;overflow:visible;white-space:nowrap"></span>';
			m[b] = a;
			var d = a.firstChild;
			z[b] = a.lastChild;
			var e = k.body.contains;
			if (e(a)) d.movie = t; else var f = setInterval(function () {
				if (e(a)) clearInterval(f), d.movie = t
			}, 0);
			if (k.compatMode === "BackCompat" || !l.XMLHttpRequest) z[b].style.overflow = "hidden";
			var h = new A(a, d);
			a.getContext = function (a) {
				return a === "2d" ? h : g
			};
			a.toDataURL = function (b,
									c) {
				if (a.width === 0 || a.height === 0) return "data:,";
				("" + b).replace(/[A-Z]+/g, ia) === "image/jpeg" ? h.a.push("A", b, typeof c === "number" ? c : "") : h.a.push("A", b);
				return h.f().slice(1, -1)
			};
			d.attachEvent(U, Q);
			ca && (d.attachEvent(F, D), a.attachEvent(G, E));
			N && h.a.push(y, "usePolicyFile", N);
			b = h.a.length;
			h.g.push(b + 2, b + 5);
			h.a.push(y, "proxy", ea, y, "save", fa);
			return a
		}, saveImage: function (a, b) {
			a.firstChild.saveImage(b)
		}, setOptions: function (a) {
			for (var b in a) {
				var c = a[b];
				switch (b) {
					case "turbo":
						L = c;
						break;
					case "delay":
						M = c;
						break;
					case "disableContextMenu":
						ca = c;
						var d = void 0;
						for (d in m) {
							var e = m[d], f = c ? "attachEvent" : "detachEvent";
							e.firstChild[f](F, D);
							e[f](G, E)
						}
						break;
					case "imageCacheSize":
						da = c;
						break;
					case "usePolicyFile":
						I(b, N = c ? 1 : 0);
						break;
					case "proxy":
						I(b, ea = c);
						break;
					case "save":
						I(b, fa = c)
				}
			}
		}, trigger: function (a, b) {
			m[a].fireEvent("on" + b)
		}, unlock: function (a, b, c) {
			var d, e, f;
			p[a] && --p[a];
			if (b === q) {
				d = m[a];
				b = d.firstChild;
				$(d);
				e = d.width;
				c = d.height;
				d.style.width = e + "px";
				d.style.height = c + "px";
				if (e > 0) b.width = e;
				if (c > 0) b.height = c;
				b.resize(e,
					c);
				d.attachEvent(V, R);
				s[a] = 1;
				typeof d.onload === "function" && setTimeout(function () {
					d.onload()
				}, 0)
			} else if (f = v[a][b]) e = f[0], c = f[1 + c], delete v[a][b], typeof c === "function" && c.call(e)
		}
	};
	k.createElement(r);
	k.createStyleSheet().cssText = r + "{display:inline-block;overflow:hidden;width:300px;height:150px}";
	J && (k.readyState === "complete" ? B() : k.attachEvent(P, B));
	l.attachEvent(T, S);
	t.indexOf(location.protocol + "//" + location.host + "/") === 0 && (n = new ActiveXObject("Microsoft.XMLHTTP"), n.open("GET", t, !1), n.send(g));
	l[W] =
		A;
	l[X] = o;
	l[Y] = x;
	l[H] = C;
	l[Z] = {
		init: function () {
		}, init_: function () {
		}, initElement: C.initElement
	}
}(window, document);


(function (document) {
	webshims.addReady(function (context, elem) {
		if (context == document) {
			if (window.G_vmlCanvasManager && G_vmlCanvasManager.init_) {
				G_vmlCanvasManager.init_(document);
			}
		}
		webshims.$('canvas', context).add(elem.filter('canvas')).each(function () {
			var hasContext = this.getContext;
			if (!hasContext && window.G_vmlCanvasManager) {
				G_vmlCanvasManager.initElement(this);
			}
		});
	});
	webshims.isReady('canvas', true);
})(document);
