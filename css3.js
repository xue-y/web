/*
获取css 样式
e --- 元素
t --- 属性
a --- 透明度
 */
function css(e, t, a) {
	if (2 == arguments.length) {
		if ("scale" == t || "rotate" == t || "rotateX" == t || "rotateY" == t || "rotateZ" == t || "scaleX" == t || "scaleY" == t || "translateY" == t || "translateX" == t || "translateZ" == t) switch (e.$Transform || (e.$Transform = {}), t) {
		case "scale":
		case "scaleX":
		case "scaleY":
			return "number" == typeof e.$Transform[t] ? e.$Transform[t] : 100;
		case "translateY":
		case "translateX":
		case "translateZ":
			return e.$Transform[t] ? e.$Transform[t] : 0;
		default:
			return e.$Transform[t] ? e.$Transform[t] : 0
		}
		var n = e.currentStyle ? e.currentStyle[t] : document.defaultView.getComputedStyle(e, !1)[t];
		return "opacity" == t ? Math.round(100 * parseFloat(n)) : parseInt(n)
	}
	if (3 == arguments.length) switch (t) {
	case "scale":
	case "scaleX":
	case "scaleY":
	case "rotate":
	case "rotateX":
	case "rotateY":
	case "rotateZ":
	case "translateY":
	case "translateX":
	case "translateZ":
		setCss3(e, t, a);
		break;
	case "width":
	case "height":
	case "paddingLeft":
	case "paddingTop":
	case "paddingRight":
	case "paddingBottom":
		a = Math.max(a, 0);
	case "left":
	case "top":
	case "right":
	case "bottom":
	case "marginLeft":
	case "marginTop":
	case "marginRight":
	case "marginBottom":
		e.style[t] = "string" == typeof a ? a : a + "px";
		break;
	case "opacity":
		e.style.filter = "alpha(opacity:" + a + ")", e.style.opacity = a / 100;
		break;
	default:
		e.style[t] = a
	}
	return function(t, a) {
		css(e, t, a)
	}
}

/*
设置 css3 样式
e --- 元素
t --- 属性
a --- 值
 */
function setCss3(e, t, a) {
	var n = "",
		r = "",
		s = ["Webkit", "Moz", "O", "ms", ""];
	e.$Transform || (e.$Transform = {}), e.$Transform[t] = parseInt(a);
	for (n in e.$Transform) switch (n) {
	case "scale":
	case "scaleX":
	case "scaleY":
		r += n + "(" + e.$Transform[n] / 100 + ") ";
		break;
	case "rotate":
	case "rotateX":
	case "rotateY":
	case "rotateZ":
		r += n + "(" + e.$Transform[n] + "deg) ";
		break;
	case "translateY":
	case "translateX":
	case "translateZ":
		r += n + "(" + e.$Transform[n] + "px) "
	}
	for (var c = 0; c < s.length; c++) e.style[s[c] + "Transform"] = r
}