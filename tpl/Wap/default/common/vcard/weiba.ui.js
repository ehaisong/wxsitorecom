var dataBasePath = "";
var effectPlugBasePath = "/assets/public/js/effect";
(function(a, b) {
	a.WBPage = {};
	b(function() {
		b("body").on("touchend", ".weiba-button-share", function() {
			var c = WBPage.MaskLayer.show("black");
			var e = WBPage.MaskLayer.getZIndex();
			var d = '<div class="weiba-layer-sharehelper" style="z-index: ' + (e + 1) + '"></div>';
			c.after(d);
			b(".weiba-layer-sharehelper").on("touchend", function() {
				WBPage.MaskLayer.close();
				b(this).remove()
			})
		})
	});
	(function() {
		var c = document.getElementsByTagName("script"),
			e = c.length,
			f = c[e - 1].src,
			g = f.indexOf("/"),
			d = f.substr(0, g) + "/";
		if (!a.importScriptList) {
			a.importScriptList = {}
		}
		a.importScript = function(h) {
			if (!h) {
				return
			}
			if (h.indexOf("http://") == -1 && h.indexOf("https://") == -1) {
				if (h.substr(0, 1) == "/") {
					h = h.substr(1)
				}
				h = d + h
			}
			if (h in importScriptList) {
				return
			}
			importScriptList[h] = true;
			document.write('<script src="' + h + '" type="text/javascript"><\/script>')
		}
	})();
	importScript("/assets/public/js/jDialog/jquery.drag.js");
	importScript("/assets/public/js/jDialog/jquery.mask.js");
	importScript("/assets/public/js/jDialog/jquery.dialog.js");
	b(a).bind("rendercomplete", function() {
		b(".weiba-navbar-item.home").attr("href", "/account/").removeClass("home").addClass("account");
		var e = '<a class="weiba-list-item tpl-catelist-item" href="/">';
		e += '<div class="weiba-list-item-line">';
		e += '<div class="weiba-list-item-title tpl-cate-title">网站首页</div>';
		e += "</div>";
		e += '<div class="weiba-list-item-icon icon-arrow-r"></div>';
		e += "</a>";
		b(e).prependTo(".weiba-quickpanel .weiba-list.tpl-quickpanel");
		var f = a.navigator.userAgent.toLowerCase();
		if (f.indexOf("android") > -1) {
			var d = a.location.href;
			var h = a.sessionStorage.getItem("historyUrl");
			if (!h) {
				a.sessionStorage.setItem("historyUrl", "[]")
			}
			var g = h ? b.parseJSON(h) : [];
			var c = g.length;
			if (c > 0) {
				if (g[c - 1] != d) {
					g.push(d)
				}
			} else {
				g.push(d)
			}
			a.sessionStorage.setItem("historyUrl", JSON.stringify(g))
		}
		b.getUserInfo()
	});
	b.extend(a.WBPage, {
		hasRender: function() {
			return (b("html").hasClass("RENDERCOMPLETE"))
		},
		goBack: function() {
			var e = a.navigator.userAgent.toLowerCase();
			if (e.indexOf("android") > -1) {
				var f = b.parseJSON(a.sessionStorage.getItem("historyUrl")),
					c = f.length,
					d = a.location.href;
				if (c > 1) {
					if (d != f[c - 2]) {
						f.pop();
						a.sessionStorage.setItem("historyUrl", JSON.stringify(f));
						a.location.href = f[c - 2]
					}
				} else {
					a.location.href = (a.WBPage.Info.home)
				}
			} else {
				if (a.history.length <= 1) {
					a.location.href = (a.WBPage.Info.home)
				} else {
					a.history.back()
				}
			}
		},
		getWBData: function(c) {
			return b("body").getWBData(c)
		},
		show: function() {
			b(".weiba-page").show()
		},
		hide: function() {
			b(".weiba-page").hide()
		},
		info_init: function(d) {
			var c = -1;
			if (d && d.status !== undefined) {
				c = d.status
			}
			b.extend({
				home: "",
				name: "微信网站"
			}, d);
			if (c == 0) {
				a.WBPage.Info = {
					home: d.url,
					name: d.company
				}
			} else {
				var e = "";
				switch (c) {
				case 1:
					e = "微网站已经被禁用，请联系代理商。";
					break;
				case 2:
					e = "微网站已经被删除，请联系代理商。";
					break;
				case 3:
					e = "微网站已经被过期，请联系代理商。";
					break;
				default:
					e = "微网站状态不正常，请联系代理商。"
				}
				alert(e);
				document.write(e)
			}
			return c
		},
		widget_init: function(c) {
			switch (c) {
			case "banner":
				b(".weiba-banner").wb_ui_banner();
				break;
			case "navbar":
				b(".weiba-navbar").wb_ui_navbar();
				break;
			case "quickpanel":
				b(".weiba-quickpanel").wb_ui_quickpanel();
				break;
			case "easycall":
				b(".weiba-easycall").wb_ui_easycall();
				break;
			case "listscroll":
				b(".weiba-listscroll").wb_ui_list();
				break;
			case "select":
				b(".weiba-select").wb_ui_select();
				break;
			case "datetime":
				b(".weiba-datetime").wb_ui_datetime();
				break;
			case "date":
				b(".weiba-date").wb_ui_date();
				break;
			case "time":
				b(".weiba-time").wb_ui_time();
				break
			}
		},
		tpl_render: function(c, d) {
			b.each(d, function(f, e) {
				if (e) {
					var g = b(f);
					if (g.length > 0) {
						b(f).render(c, e)
					} else {
						console.log("no target:", f)
					}
				}
			})
		},
		PATH_TYPE_PROCESSOR: "/assets/public/js/type_processor/",
		PATH_INVITATION: dataBasePath + "/data/invitation",
		PATH_AUTOCARD: dataBasePath + "/data/autocard/",
		PATH_GAME_SUBMIT: dataBasePath + "/game/",
		PATH_GAME: dataBasePath + "/data/game/",
		PATH_RESERVE: dataBasePath + "/data/reserve/",
		PATH_DATA_INFO: dataBasePath + "/data/info/",
		PATH_DATA_BANNER: dataBasePath + "/data/cate_banner/",
		PATH_DATA_DETAIL_LIST: dataBasePath + "/data/detail_list/",
		PATH_DATA_FOOTER: dataBasePath + "/data/footer/",
		PATH_DATA_EASYCALL: dataBasePath + "/data/assist/",
		PATH_DATA_CATELIST: dataBasePath + "/data/cate_list/",
		PATH_DATA_DETAIL: dataBasePath + "/data/detail",
		PATH_DATA_LINKUS: dataBasePath + "/data/linkus",
		PATH_SMS_SENDCODE: dataBasePath + "/data/sms/send_verfy_code",
		PATH_SMS_SENDVOICECODE: dataBasePath + "/data/sms/send_voice_code",
		PATH_SMS_CHECKCODE: dataBasePath + "/data/sms/check_verfy_code",
		PATH_DATA_CHANGE_PWD: dataBasePath + "/data/ac_reset_password",
		PATH_DATA_REG: dataBasePath + "/data/ac_add",
		PATH_DATA_ACCOUNT: dataBasePath + "/data/ac_search",
		PATH_DATA_ACCOUNT_BINDWEIXIN: dataBasePath + "/data/ac_bind",
		PATH_DATA_ACCOUNT_CONSUME: dataBasePath + "/data/mc_record/consume",
		PATH_DATA_ACCOUNT_CREDIT: dataBasePath + "/data/mc_record/credit",
		PATH_DATA_VIP_ADD: dataBasePath + "/data/mc_user_card/add",
		PATH_DATA_VIP_INFO: dataBasePath + "/data/mc_user_card",
		PATH_DATA_VIP_TICKET: dataBasePath + "/data/mc_user_ticket",
		PATH_DATA_MC_INFO: dataBasePath + "/data/mc_info",
		PATH_DATA_VIP_PAY: dataBasePath + "/data/ac_sequestrate",
		PATH_DATA_VIP_TICKET_VERIFY: dataBasePath + "/data/mc_user_ticket/check_my_ticket",
		PATH_DATA_VIP_EXCHANGE: dataBasePath + "/data/member_ticket/to_exchange_ticket",
		PATH_DATA_VIP_USETICKET: dataBasePath + "/data/member_ticket/use_ticket",
		PATH_DATA_MC_TICKET: dataBasePath + "/data/mc_ticket",
		PATH_DATA_ACCOUNT_SERVICE: dataBasePath + "/data/ac_project",
		PATH_VOTE: dataBasePath + "/data/vote/",
		PATH_EMI: dataBasePath + "/data/emigrated/",
		PATH_GROUPON: dataBasePath + "/data/groupon/",
		PATH_CONTACT: dataBasePath + "/data/contact/",
		PATH_DATA_ADDRESS: dataBasePath + "/data/address/",
		PATH_MALL: dataBasePath + "/data/mall/",
		PATH_EFFECT: dataBasePath + "/data/effect",
		PATH_USERCENTER: dataBasePath + "/data/usercenter/",
		PATH_EFFECT_PLUG_FILE: {
			Atmo: effectPlugBasePath + "/weather.js",
			Music: effectPlugBasePath + "/music.js",
			Door: effectPlugBasePath + "/door.js"
		},
		PATH_EFFECT_IMG: "/assets/public/images/",
		PATH_LIVE: dataBasePath + "/data/live/",
		PATH_DIFF_UID: dataBasePath + "/data/ac_verify/",
		PATH_NEW_ACCOUNT: dataBasePath + "/data/ac_new_account/",
		PATH_VIP_MYCARD: dataBasePath + "/data/mcard/",
		PATH_BUSINESS_CARD: dataBasePath + "/data/business_card/",
		PATH_ACCOUNT_PAY: dataBasePath + "/data/account/",
		PATH_PAY_SITE: dataBasePath + "/data/site/"
	});
	b.fn.getWBData = function(d) {
		var c = "weiba-" + d;
		return this.attr(c)
	}
})(window, jQuery);
(function(c, d, f) {
	var a, e = 9999;
	d.getUserInfo = function() {
		var h = WBPage.PageData;
		if (h) {
			var i = WBPage.PageData.user;
			if (i) {
				var l = i.name;
				var k = i.mobile;
				d(".weiba-user-name").val(l);
				d(".weiba-user-mobile").val(k)
			} else {
				var g = WBPage.PATH_ACCOUNT_PAY;
				var j = {
					uid: c.localStorage.getItem("MYUID")
				};
				d.getJSON(g, j, function(m) {
					if (m.ret == 0) {
						var o = m.data["name"];
						var n = m.data["mobile"];
						d(".weiba-user-name").val(o);
						d(".weiba-user-mobile").val(n)
					}
				})
			}
		}
	};
	d.fn.showDialog = function(g) {
		a = b().fadeIn();
		return this.each(function() {
			var h = d(this).css({
				"z-index": e++
			}).css({
				bottom: -d(this).height()
			}).show().animate({
				bottom: 0
			}, function() {
				a.one("click", function() {
					h.closeDialog()
				})
			}).data("isOpened", true)
		})
	};
	d.fn.closeDialog = function() {
		return this.each(function() {
			var g = d(this);
			if (g.data("isOpened")) {
				g.animate({
					bottom: -g.height()
				}, function() {
					if (a) {
						a.fadeOut(function() {
							a.remove();
							a = null
						})
					}
					g.hide()
				}).data("isOpened", false)
			}
		})
	};
	d.fn.showHelper = function(g) {
		a = b().fadeIn();
		return this.each(function() {
			var h = d(this).css({
				"z-index": e++
			}).fadeIn(function() {
				a.one("click", function() {
					h.closeHelper()
				})
			}).data("isOpened", true)
		})
	};
	d.fn.closeHelper = function() {
		return this.each(function() {
			var g = d(this);
			if (g.data("isOpened")) {
				g.hide(function() {
					if (a) {
						a.fadeOut(function() {
							a.remove();
							a = null
						})
					}
				}).data("isOpened", false)
			}
		})
	};

	function b() {
		if (!a) {
			a = d('<div class="weiba-masklayer black"></div>').css({
				"z-index": (e - 1)
			}).appendTo("body")
		}
		return a
	}
	d.popTip = function(o, h, m, l) {
		var i = '<div class="Tip ' + (l ? l : "") + '"><div class="Text">' + o + '</div><div class="ICON"></div></div>';
		var j = d(i).appendTo("body");
		var k = d(".ICON", j);
		var n, g;
		if (h) {
			switch (m) {
			case "TopRight":
				n = h.offset().top - j.height() - k.height();
				g = h.offset().left + h.width() - j.width();
				break;
			case "BottomRight":
				n = h.offset().top + h.height() + k.height();
				g = h.offset().left + h.width() - j.width();
				break;
			case "BottomLeft":
				n = h.offset().top + h.height() + k.height();
				g = h.offset().left;
				break;
			case "TopLeft":
				n = h.offset().top - j.height() - k.height();
				g = h.offset().left;
				break;
			case "Center":
			default:
				m = "Center";
				n = h.offset().top + (h.height() - j.height()) / 2;
				g = h.offset().left + (h.width() - j.width()) / 2;
				k.hide();
				break
			}
		} else {
			k.hide();
			n = "50%";
			g = "50%";
			j.css({
				position: "fixed",
				"margin-left": -j.width() / 2 + "px",
				"margin-right": -j.height() / 2 + "px"
			})
		}
		j.addClass(m).css({
			top: n,
			left: g
		}).fadeIn(function() {
			c.setTimeout(function() {
				j.fadeOut(function() {
					j.remove();
					j = null
				})
			}, 800)
		})
	};
	d.popWaningTip = function(h, g, i) {
		d.popTip(h, g, i, "Warning")
	};
	$Loadings = {};
	d.showLoading = function(g) {
		if (!g) {
			g = d("body")
		}
		var h = "LOADING" + Math.round(Math.random() * 8000000 + 1000000);
		$Loadings[h] = d('<div class="System-Loading"><div class="BG"></div><div class="ICON"></div></div>').show().appendTo(g);
		return h
	};
	d.hideLoading = function(g) {
		if ($Loadings[g]) {
			$Loadings[g].hide();
			$Loadings[g].remove();
			$Loadings[g] = null;
			delete $Loadings[g]
		}
	};
	d.formatDate = function(i, g) {
		var l = {
			"M+": i.getMonth() + 1,
			"d+": i.getDate(),
			"h+": i.getHours() % 12 == 0 ? 12 : i.getHours() % 12,
			"H+": i.getHours(),
			"m+": i.getMinutes(),
			"s+": i.getSeconds(),
			"q+": Math.floor((i.getMonth() + 3) / 3),
			S: i.getMilliseconds()
		};
		var j = {
			"0": "/u65e5",
			"1": "/u4e00",
			"2": "/u4e8c",
			"3": "/u4e09",
			"4": "/u56db",
			"5": "/u4e94",
			"6": "/u516d"
		};
		if (/(y+)/.test(g)) {
			g = g.replace(RegExp.$1, (i.getFullYear() + "").substr(4 - RegExp.$1.length))
		}
		if (/(E+)/.test(g)) {
			g = g.replace(RegExp.$1, ((RegExp.$1.length > 1) ? (RegExp.$1.length > 2 ? "/u661f/u671f" : "/u5468") : "") + j[i.getDay() + ""])
		}
		for (var h in l) {
			if (new RegExp("(" + h + ")").test(g)) {
				g = g.replace(RegExp.$1, (RegExp.$1.length == 1) ? (l[h]) : (("00" + l[h]).substr(("" + l[h]).length)))
			}
		}
		return g
	};
	d.ClosePopUp = function() {
		if (d("#popUp").size()) {
			d("#popUp").css({
				webkitTransform: "translateX(" + parseInt(d(c).width() + 2) + "px)"
			});
			setTimeout(function() {
				d("#popUpCss").size() ? d("#popUpCss").remove() : "";
				d("#popUp").size() ? d("#popUp").remove() : ""
			}, 400)
		}
	};
	d.OpenPopTips = function(g, h) {
		if (d("#popUp").size()) {
			d("#popUp .error").html(g).show();
			setTimeout(function() {
				d("#popUp .error").hide().html("")
			}, h)
		}
	};
	d.OpenPopUp = function(h, i, g, j) {
		d("#popUpCss").size() ? d("#popUpCss").remove() : "";
		d("#popUp").size() ? d("#popUp").remove() : "";
		var i = '<style id="popUpCss">#popUp{position:fixed; left:0; top:0; z-index:999999; width:100%; height:100%; background:#eaebee; box-shadow:0px -1px 2px #333; -webkit-transform: translateX(' + parseInt(d(c).width() + 2) + "px);-webkit-transition: -webkit-transform 400ms ease;}#popUp .popTit{height:45px; line-height:45px; overflow:hidden; background:" + g + "; color:#fff; text-align:center; font-weight:bold; font-size:20px; position:relative;}#popUp .popTit i{position:absolute; top:8px; width:29px; height:29px; overflow:hidden;}#popUp .popTit i.back{left:10px; background:url(" + WBPage.PATH_EFFECT_IMG + "back.png) no-repeat; background-size:29px 29px;}#popUp .popTit i.check{right:10px; background:url(" + WBPage.PATH_EFFECT_IMG + 'check.png) no-repeat; background-size:29px 29px;}#popUp .popCon{border:1px solid #dbdbdb; background:#fff; margin:10px; overflow:hidden;}#popUp input{border-radius:0;}#popUp input[type=text],#popUp input[type=password]{display:block;width:88%;border:none;border-bottom:1px solid #ccc;height:15px;line-height: 15px;overflow: hidden;padding:12px 10% 12px 2%;font-size:15px;margin:0;}#popUp .error{margin:0; background:#8c040e; color:#fff; opacity:0.7; width:90%; padding:5px 5%; position:absolute; top:45px; left:0; display:none;}</style><div id="popUp"><div class="popTit"><i class="back"></i><span>' + h + '</span><i class="check"></i></div><div class="popCon">' + i + '</div><p class="error"></p></div>';
		d("body").append(i);
		setTimeout(function() {
			d("#popUp").css({
				webkitTransform: "translateX(0px)"
			})
		}, 0);
		setTimeout(function() {
			d("#popUp .popTit i.back").on("touchend", function() {
				setTimeout(function() {
					d.ClosePopUp()
				}, 400)
			});
			if (typeof j === "function") {
				j()
			}
		}, 400)
	}
})(window, jQuery, undefined);
(function(d, e) {
	var b, a = {};
	d.WBPage.Loader = {
		append: function() {
			if (!b) {
				var f = WBPage.MaskLayer.getZIndex() + 1;
				if (f < 999) {
					f = 999999
				}
				b = e('<div class="weiba-loader" style="z-index: ' + f + '"></div>').appendTo("body")
			}
			b.show();
			return c()
		},
		remove: function(f) {
			if (a.hasOwnProperty(f)) {
				delete a[f]
			}
			if (WBPage.Loader.getAllIds().length == 0) {
				b.css("display", "none")
			}
		},
		removeAll: function() {
			a = {};
			b.hide()
		},
		getAllIds: function() {
			var f = [];
			e.each(a, function(g, h) {
				f.push(g)
			});
			return f
		}
	};

	function c() {
		var f = "weiba_loaders_" + Math.round(Math.random() * 8000000 + 1000000);
		if (!a.hasOwnProperty(f)) {
			a[f] = true;
			return f
		} else {
			return c()
		}
	}
})(window, jQuery);
(function(a, b) {
	var c;
	a.WBPage.MaskLayer = {
		show: function(d) {
			if (!c) {
				c = b('<div class="weiba-masklayer"></div>').addClass(d ? d : "")
			}
			return c.hide().appendTo("body").fadeIn()
		},
		close: function() {
			if (c) {
				c.fadeOut(function() {
					c.off();
					c.unbind();
					c.remove();
					c = null
				})
			}
		},
		getZIndex: function() {
			if (c) {
				return c.css("z-index")
			} else {
				return 0
			}
		}
	}
})(window, jQuery);
(function(b, c) {
	c.fn.wb_ui_navbar = function() {
		var d = this.each(function() {
			c(this).on("tap", ".weiba-navbar-item", function(f) {
				if (c(this).hasClass("quick")) {
					if (WBPage.QuickPanel) {
						if (!WBPage.QuickPanel.isOpened) {
							WBPage.QuickPanel.open()
						} else {
							WBPage.QuickPanel.close()
						}
					}
				} else {
					if (c(this).hasClass("easycall")) {
						if (c(this).hasClass("easycall-one")) {
							return
						} else {
							if (WBPage.EasyCall) {
								if (!WBPage.EasyCall.isOpened) {
									WBPage.EasyCall.open()
								} else {
									WBPage.EasyCall.close()
								}
							}
						}
					} else {
						if (c(this).hasClass("home")) {
							return
						} else {
							if (c(this).hasClass("account")) {
								return
							} else {
								if (c(this).hasClass("back")) {
									WBPage.goBack()
								}
							}
						}
					}
				}
				f.preventDefault();
				return false
			})
		});
		b.WBPage.NavBar = {
			Dom: d
		};
		return d
	};

	function a(d) {
		console.log("close");
		c("body").removeClass("weiba-quickpanel-animate-push");
		c(".weiba-quickpanel").hide();
		c(".weiba-page").unbind("tap.quickpanel", a);
		d.preventDefault();
		return false
	}
})(window, jQuery);
(function(a, b) {
	b.fn.wb_ui_quickpanel = function(f) {
		var c = "webkitTransitionEnd oTransitionEnd otransitionend transitionend msTransitionEnd";
		var e = this;
		var d;
		var g = b(".weiba-navbar");
		if (e.length > 0) {
			d = b('<div class="weiba-quickpanel-box"><div class="weiba-quickpanel-toolbar"><div class="weiba-quickpanel-toolbar-title">快捷导航</div><div class="weiba-quickpanel-toolbar-close icon-delete"></div></div></div>').append(e.show()).appendTo("body").on("tap", ".weiba-quickpanel-toolbar", function() {
				a.WBPage.QuickPanel.close()
			});
			b("body").on("swipeleft", ".weiba-quickpanel-box", function() {
				a.WBPage.QuickPanel.close()
			})
		}
		a.WBPage.QuickPanel = {
			isOpened: false,
			open: function() {
				var h = a.WBPage.MaskLayer.show("black");
				h.on("tap", function() {
					a.WBPage.QuickPanel.close()
				});
				d.css({
					"z-index": a.WBPage.MaskLayer.getZIndex() + 1,
					width: e.width(),
					top: -g.height() + "px",
					right: -e.width() + "px"
				}).show().animate({
					right: 0
				}, function() {
					a.WBPage.QuickPanel.isOpened = true
				})
			},
			close: function() {
				d.css({
					top: -g.height() + "px",
					right: 0
				}).show().animate({
					right: -e.width() + "px"
				}, function() {
					a.WBPage.QuickPanel.isOpened = false;
					d.hide();
					a.WBPage.MaskLayer.close()
				})
			}
		};
		return e
	}
})(window, jQuery);
(function(a, b) {
	b.fn.wb_ui_easycall = function() {
		var d = b(".weiba-easycall-but").on("tap", function() {
			if (b(".weiba-easycall-show").hasClass("open")) {
				c()
			} else {
				a.WBPage.MaskLayer.show("black").on("tap", function(f) {
					c()
				});
				b(".weiba-easycall-show").show().addClass("open").parent().css({
					"z-index": a.WBPage.MaskLayer.getZIndex() + 1
				});
				b(".weiba-easycall-but").addClass("open");
				b(".weiba-easycall-item").removeClass("margin-bottom33").addClass("margin-bottom20")
			}
		});

		function c() {
			b(".weiba-easycall-item").addClass("margin-bottom33").removeClass("margin-bottom20");
			b(".weiba-easycall-show").hide(100).removeClass("open");
			a.WBPage.MaskLayer.close();
			b(".weiba-easycall-but").removeClass("open")
		}
	}
})(window, jQuery);
(function(a, b) {
	b.fn.wb_ui_banner = function() {
		return this.each(function() {
			var h = b(this),
				g = b(".weiba-banner-box", h).children(".weiba-banner-item").length;
			if (g > 1) {
				var f = '<div class="weiba-banner-toolbar">';
				for (var e = 0; e < g; e++) {
					f += '<span class="weiba-banner-toolbar-item l' + e + '"></span>'
				}
				f += "</div>";
				var d = b(f).appendTo(h);
				c(0);
				b(a).bind("rendercomplete", function() {
					h.swipe({
						startSlide: 0,
						speed: 400,
						auto: 1500,
						continuous: true,
						disableScroll: false,
						stopPropagation: false,
						callback: function(i, j) {
							c(i)
						},
						transitionEnd: function(i, j) {}
					})
				})
			} else {
				if (g <= 0) {
					h.remove()
				}
			}
			function c(i) {
				b(d.children().removeClass("selected")[i]).addClass("selected")
			}
		})
	}
})(window, jQuery);
(function(b, c) {
	function a(f) {
		f = c.trim(f);
		var e = new Date().getTime();
		if (f == "now") {
			return new Date()
		} else {
			if (f.indexOf("now+y") > -1) {
				var d = f.replace("now+y", "") * 1 * 365 * 24 * 3600 * 1000;
				return new Date(e + d)
			} else {
				if (f.indexOf("now+d") > -1) {
					var d = f.replace("now+d", "") * 1 * 24 * 3600 * 1000;
					return new Date(e + d)
				} else {
					if (f.indexOf("now+M") > -1) {
						var d = f.replace("now+M", "") * 1 * 30 * 24 * 3600 * 1000;
						return new Date(e + d)
					} else {
						if (f.indexOf("now+m") > -1) {
							var d = f.replace("now+m", "") * 1 * 60 * 1000;
							return new Date(e + d)
						} else {
							if (f.indexOf("now+h") > -1) {
								var d = f.replace("now+h", "") * 1 * 3600 * 1000;
								return new Date(e + d)
							} else {
								if (f.indexOf("now-y") > -1) {
									var d = f.replace("now-y", "") * 1 * 365 * 24 * 3600 * 1000;
									return new Date(e - d)
								} else {
									if (f.indexOf("now-d") > -1) {
										var d = f.replace("now-d", "") * 1 * 24 * 3600 * 1000;
										return new Date(e - d)
									} else {
										if (f.indexOf("now-M") > -1) {
											var d = f.replace("now-M", "") * 1 * 30 * 24 * 3600 * 1000;
											return new Date(e - d)
										} else {
											if (f.indexOf("now-m") > -1) {
												var d = f.replace("now-m", "") * 1 * 60 * 1000;
												return new Date(e - d)
											} else {
												if (f.indexOf("now-h") > -1) {
													var d = f.replace("now-h", "") * 1 * 3600 * 1000;
													return new Date(e - d)
												} else {
													return c.translateDatetime(f)
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
	c.myGetdate = function(d) {
		return a(d)
	};
	c.fn.wb_ui_date = function() {
		return this.each(function() {
			var g = c(this);
			var d = {
				preset: "date"
			},
				f = g.attr("weiba-datetime-min"),
				h = g.attr("weiba-datetime-max"),
				e = g.attr("weiba-datetime-step");
			if (f) {
				d.minDate = a(f)
			}
			if (h) {
				d.maxDate = a(h)
			}
			if (e) {
				d.stepMinute = e * 1
			}
			c(this).scroller(d)
		})
	};
	c.fn.wb_ui_datetime = function() {
		return this.each(function() {
			var g = c(this);
			var d = {
				preset: "datetime"
			},
				f = g.attr("weiba-datetime-min"),
				h = g.attr("weiba-datetime-max"),
				e = g.attr("weiba-datetime-step");
			if (f) {
				d.minDate = a(f)
			}
			if (h) {
				d.maxDate = a(h)
			}
			if (e) {
				d.stepMinute = e * 1
			}
			c(this).scroller(d)
		})
	}
})(window, jQuery);
(function(a, b) {
	b.fn.wb_ui_time = function() {
		return this.each(function() {
			b(this).scroller({
				preset: "time"
			})
		})
	}
})(window, jQuery);
(function(a, b) {
	b.fn.wb_ui_select = function() {
		return this.each(function() {
			b(this).scroller({
				preset: "select"
			})
		})
	}
})(window, jQuery);
(function(a, b) {
	b.fn.wb_ui_list = function() {
		return this.each(function() {
			b(this).scroller({
				preset: "list"
			})
		})
	}
})(window, jQuery);
(function(a, b) {
	function c(n, h) {
		var i = function() {};
		var v = function(D) {
				setTimeout(D || i, 0)
			};
		var C = {
			addEventListener: !! a.addEventListener,
			touch: ("ontouchstart" in a) || a.DocumentTouch && document instanceof DocumentTouch,
			transitions: (function(D) {
				var F = ["transformProperty", "WebkitTransform", "MozTransform", "OTransform", "msTransform"];
				for (var E in F) {
					if (D.style[F[E]] !== undefined) {
						return true
					}
				}
				return false
			})(document.createElement("swipe"))
		};
		if (!n) {
			return
		}
		var f = n.children[0];
		var t, g, s;
		h = h || {};
		var l = parseInt(h.startSlide, 10) || 0;
		var w = h.speed || 300;

		function o() {
			t = f.children;
			g = new Array(t.length);
			s = n.getBoundingClientRect().width || n.offsetWidth;
			f.style.width = (t.length * s) + "px";
			var E = t.length;
			while (E--) {
				var D = t[E];
				D.style.width = s + "px";
				D.setAttribute("data-index", E);
				if (C.transitions) {
					D.style.left = (E * -s) + "px";
					r(E, l > E ? -s : (l < E ? s : 0), 0)
				}
			}
			if (!C.transitions) {
				f.style.left = (l * -s) + "px"
			}
			n.style.visibility = "visible"
		}
		function p() {
			if (l) {
				d(l - 1)
			} else {
				if (h.continuous) {
					d(t.length - 1)
				}
			}
		}
		function q() {
			if (l < t.length - 1) {
				d(l + 1)
			} else {
				if (h.continuous) {
					d(0)
				}
			}
		}
		function d(G, D) {
			if (l == G) {
				return
			}
			if (C.transitions) {
				var F = Math.abs(l - G) - 1;
				var E = Math.abs(l - G) / (l - G);
				while (F--) {
					r((G > l ? G : l) - F - 1, s * E, 0)
				}
				r(l, s * E, D || w);
				r(G, 0, D || w)
			} else {
				k(l * -s, G * -s, D || w)
			}
			l = G;
			v(h.callback && h.callback(l, t[l]))
		}
		function r(D, F, E) {
			m(D, F, E);
			g[D] = F
		}
		function m(E, H, G) {
			var D = t[E];
			var F = D && D.style;
			if (!F) {
				return
			}
			F.webkitTransitionDuration = F.MozTransitionDuration = F.msTransitionDuration = F.OTransitionDuration = F.transitionDuration = G + "ms";
			F.webkitTransform = "translate(" + H + "px,0)translateZ(0)";
			F.msTransform = F.MozTransform = F.OTransform = "translateX(" + H + "px)"
		}
		function k(H, G, D) {
			if (!D) {
				f.style.left = G + "px";
				return
			}
			var F = +new Date;
			var E = setInterval(function() {
				var I = +new Date - F;
				if (I > D) {
					f.style.left = G + "px";
					if (B) {
						y()
					}
					h.transitionEnd && h.transitionEnd.call(event, l, t[l]);
					clearInterval(E);
					return
				}
				f.style.left = (((G - H) * (Math.floor((I / D) * 100) / 100)) + H) + "px"
			}, 4)
		}
		var B = h.auto || 0;
		var x;

		function y() {
			x = setTimeout(q, B)
		}
		function u() {
			B = 0;
			clearTimeout(x)
		}
		var j = {};
		var z = {};
		var A;
		var e = {
			handleEvent: function(D) {
				switch (D.type) {
				case "touchstart":
					this.start(D);
					break;
				case "touchmove":
					this.move(D);
					break;
				case "touchend":
					v(this.end(D));
					break;
				case "webkitTransitionEnd":
				case "msTransitionEnd":
				case "oTransitionEnd":
				case "otransitionend":
				case "transitionend":
					v(this.transitionEnd(D));
					break;
				case "resize":
					v(o.call());
					break
				}
				if (h.stopPropagation) {
					D.stopPropagation()
				}
			},
			start: function(D) {
				var E = D.touches[0];
				j = {
					x: E.pageX,
					y: E.pageY,
					time: +new Date
				};
				A = undefined;
				z = {};
				f.addEventListener("touchmove", this, false);
				f.addEventListener("touchend", this, false)
			},
			move: function(D) {
				if (D.touches.length > 1 || D.scale && D.scale !== 1) {
					return
				}
				if (h.disableScroll) {
					D.preventDefault()
				}
				var E = D.touches[0];
				z = {
					x: E.pageX - j.x,
					y: E.pageY - j.y
				};
				if (typeof A == "undefined") {
					A = !! (A || Math.abs(z.x) < Math.abs(z.y))
				}
				if (!A) {
					D.preventDefault();
					u();
					z.x = z.x / ((!l && z.x > 0 || l == t.length - 1 && z.x < 0) ? (Math.abs(z.x) / s + 1) : 1);
					m(l - 1, z.x + g[l - 1], 0);
					m(l, z.x + g[l], 0);
					m(l + 1, z.x + g[l + 1], 0)
				}
			},
			end: function(F) {
				var H = +new Date - j.time;
				var E = Number(H) < 250 && Math.abs(z.x) > 20 || Math.abs(z.x) > s / 2;
				var D = !l && z.x > 0 || l == t.length - 1 && z.x < 0;
				var G = z.x < 0;
				if (!A) {
					if (E && !D) {
						if (G) {
							r(l - 1, -s, 0);
							r(l, g[l] - s, w);
							r(l + 1, g[l + 1] - s, w);
							l += 1
						} else {
							r(l + 1, s, 0);
							r(l, g[l] + s, w);
							r(l - 1, g[l - 1] + s, w);
							l += -1
						}
						h.callback && h.callback(l, t[l])
					} else {
						r(l - 1, -s, w);
						r(l, 0, w);
						r(l + 1, s, w)
					}
				}
				f.removeEventListener("touchmove", e, false);
				f.removeEventListener("touchend", e, false)
			},
			transitionEnd: function(D) {
				if (parseInt(D.target.getAttribute("data-index"), 10) == l) {
					if (B) {
						y()
					}
					h.transitionEnd && h.transitionEnd.call(D, l, t[l])
				}
			}
		};
		o();
		if (B) {
			y()
		}
		if (C.addEventListener) {
			if (C.touch) {
				f.addEventListener("touchstart", e, false)
			}
			if (C.transitions) {
				f.addEventListener("webkitTransitionEnd", e, false);
				f.addEventListener("msTransitionEnd", e, false);
				f.addEventListener("oTransitionEnd", e, false);
				f.addEventListener("otransitionend", e, false);
				f.addEventListener("transitionend", e, false)
			}
			a.addEventListener("resize", e, false)
		} else {
			a.onresize = function() {
				o()
			}
		}
		return {
			setup: function() {
				o()
			},
			slide: function(E, D) {
				d(E, D)
			},
			prev: function() {
				u();
				p()
			},
			next: function() {
				u();
				q()
			},
			getPos: function() {
				return l
			},
			kill: function() {
				u();
				f.style.width = "auto";
				f.style.left = 0;
				var E = t.length;
				while (E--) {
					var D = t[E];
					D.style.width = "100%";
					D.style.left = 0;
					if (C.transitions) {
						m(E, 0, 0)
					}
				}
				if (C.addEventListener) {
					f.removeEventListener("touchstart", e, false);
					f.removeEventListener("webkitTransitionEnd", e, false);
					f.removeEventListener("msTransitionEnd", e, false);
					f.removeEventListener("oTransitionEnd", e, false);
					f.removeEventListener("otransitionend", e, false);
					f.removeEventListener("transitionend", e, false);
					a.removeEventListener("resize", e, false)
				} else {
					a.onresize = null
				}
			}
		}
	}
	b.fn.swipe = function(d) {
		return this.each(function() {
			b(this).data("swipe", new c(b(this)[0], d))
		})
	}
})(window, jQuery);
(function(h, e) {
	function x(aF, aK) {
		var aG, aO, aA, N, aJ, K, aH, U, aa, G, ay, V, ar, Q, aw, at, I, am, M, ad, aD, aN, ai, D, au, ao, E, ax, az = this,
			W = e.mobiscroll,
			aM = aF,
			aL = e(aM),
			aB = w({}, j),
			aq = {},
			al = {},
			ap = {},
			T = {},
			Y = [],
			F = aL.is("input"),
			aj = false,
			Z = function(aQ) {
				if (a(aQ) && !n && !ab(this) && !aw) {
					aQ.preventDefault();
					n = true;
					at = aB.mode != "clickpick";
					D = e(".dw-ul", this);
					J(D);
					I = al[au] !== undefined;
					aD = I ? ah(D) : ap[au];
					am = b(aQ, "Y");
					M = new Date();
					ad = am;
					aC(D, au, aD, 0.001);
					if (at) {
						D.closest(".dwwl").addClass("dwa")
					}
					e(document).on(y, ac).on(m, aE)
				}
			},
			ac = function(aQ) {
				if (at) {
					aQ.preventDefault();
					aQ.stopPropagation();
					ad = b(aQ, "Y");
					aC(D, au, p(aD + (am - ad) / aO, aN - 1, ai + 1))
				}
				I = true
			},
			aE = function(aW) {
				var aT = new Date() - M,
					aS = p(aD + (am - ad) / aO, aN - 1, ai + 1),
					aU, aX, aR, aV = D.offset().top;
				if (aT < 300) {
					aU = (ad - am) / aT;
					aX = (aU * aU) / aB.speedUnit;
					if (ad - am < 0) {
						aX = -aX
					}
				} else {
					aX = ad - am
				}
				aR = Math.round(aD - aX / aO);
				if (!aX && !I) {
					var aY = Math.floor((ad - aV) / aO),
						aZ = e(".dw-li", D).eq(aY),
						aQ = at;
					if (aP("onValueTap", [aZ]) !== false) {
						aR = aY
					} else {
						aQ = true
					}
					if (aQ) {
						aZ.addClass("dw-hl");
						setTimeout(function() {
							aZ.removeClass("dw-hl")
						}, 200)
					}
				}
				if (at) {
					O(D, aR, 0, true, Math.round(aS))
				}
				n = false;
				D = null;
				e(document).off(y, ac).off(m, aE)
			},
			ag = function(aR) {
				var aQ = e(this);
				e(document).on(m, ak);
				if (!aQ.hasClass("dwb-d")) {
					aQ.addClass("dwb-a")
				}
				setTimeout(function() {
					aQ.blur()
				}, 10);
				if (aQ.hasClass("dwwb")) {
					if (a(aR)) {
						av(aR, aQ.closest(".dwwl"), aQ.hasClass("dwwbp") ? L : R)
					}
				}
			},
			ak = function(aQ) {
				if (aw) {
					clearInterval(ao);
					aw = false
				}
				e(document).off(m, ak);
				e(".dwb-a", N).removeClass("dwb-a")
			},
			S = function(aQ) {
				if (aQ.keyCode == 38) {
					av(aQ, e(this), R)
				} else {
					if (aQ.keyCode == 40) {
						av(aQ, e(this), L)
					}
				}
			},
			H = function(aQ) {
				if (aw) {
					clearInterval(ao);
					aw = false
				}
			},
			X = function(aR) {
				if (!ab(this)) {
					aR.preventDefault();
					aR = aR.originalEvent || aR;
					var aS = aR.wheelDelta ? (aR.wheelDelta / 120) : (aR.detail ? (-aR.detail / 3) : 0),
						aQ = e(".dw-ul", this);
					J(aQ);
					O(aQ, Math.round(ap[au] - aS), aS < 0 ? 1 : 2)
				}
			};

		function av(aT, aQ, aS) {
			aT.stopPropagation();
			aT.preventDefault();
			if (!aw && !ab(aQ) && !aQ.hasClass("dwa")) {
				aw = true;
				var aR = aQ.find(".dw-ul");
				J(aR);
				clearInterval(ao);
				ao = setInterval(function() {
					aS(aR)
				}, aB.delay);
				aS(aR)
			}
		}
		function ab(aQ) {
			if (e.isArray(aB.readonly)) {
				var aR = e(".dwwl", N).index(aQ);
				return aB.readonly[aR]
			}
			return aB.readonly
		}
		function ae(aU) {
			var aT = '<div class="dw-bf">',
				aW = Y[aU],
				aR = aW.values ? aW : g(aW),
				aQ = 1,
				aX = aR.labels || [],
				aS = aR.values,
				aV = aR.keys || aS;
			e.each(aS, function(aZ, aY) {
				if (aQ % 20 == 0) {
					aT += '</div><div class="dw-bf">'
				}
				aT += '<div role="option" aria-selected="false" class="dw-li dw-v" data-val="' + aV[aZ] + '"' + (aX[aZ] ? ' aria-label="' + aX[aZ] + '"' : "") + ' style="height:' + aO + "px;line-height:" + aO + 'px;"><div class="dw-i">' + aY + "</div></div>";
				aQ++
			});
			aT += "</div>";
			return aT
		}
		function J(aQ) {
			aN = e(".dw-li", aQ).index(e(".dw-v", aQ).eq(0));
			ai = e(".dw-li", aQ).index(e(".dw-v", aQ).eq(-1));
			au = e(".dw-ul", N).index(aQ)
		}
		function C(aQ) {
			var aR = aB.headerText;
			return aR ? (typeof aR === "function" ? aR.call(aM, aQ) : aR.replace(/\{value\}/i, aQ)) : ""
		}
		function af() {
			az.temp = ((F && az.val !== null && az.val != aL.val()) || az.values === null) ? aB.parseValue(aL.val() || "", az) : az.values.slice(0);
			an()
		}
		function ah(aS) {
			var aT = h.getComputedStyle ? getComputedStyle(aS[0]) : aS[0].style,
				aQ, aR;
			if (l) {
				e.each(["t", "webkitT", "MozT", "OT", "msT"], function(aV, aU) {
					if (aT[aU + "ransform"] !== undefined) {
						aQ = aT[aU + "ransform"];
						return false
					}
				});
				aQ = aQ.split(")")[0].split(", ");
				aR = aQ[13] || aQ[5]
			} else {
				aR = aT.top.replace("px", "")
			}
			return Math.round(aG - (aR / aO))
		}
		function P(aR, aQ) {
			clearTimeout(al[aQ]);
			delete al[aQ];
			aR.closest(".dwwl").removeClass("dwa")
		}
		function aC(aT, aQ, aX, aW, aV) {
			var aS = (aG - aX) * aO,
				aU = aT[0].style,
				aR;
			if (aS == T[aQ] && al[aQ]) {
				return
			}
			if (aW && aS != T[aQ]) {
				aP("onAnimStart", [N, aQ, aW])
			}
			T[aQ] = aS;
			aU[o + "Transition"] = "all " + (aW ? aW.toFixed(3) : 0) + "s ease-out";
			if (l) {
				aU[o + "Transform"] = "translate3d(0," + aS + "px,0)"
			} else {
				aU.top = aS + "px"
			}
			if (al[aQ]) {
				P(aT, aQ)
			}
			if (aW && aV) {
				aT.closest(".dwwl").addClass("dwa");
				al[aQ] = setTimeout(function() {
					P(aT, aQ)
				}, aW * 1000)
			}
			ap[aQ] = aX
		}
		function aI(aU, aR, aS, aQ, aT) {
			if (aP("validate", [N, aR, aU]) !== false) {
				e(".dw-ul", N).each(function(aY) {
					var a4 = e(this),
						a3 = e('.dw-li[data-val="' + az.temp[aY] + '"]', a4),
						a5 = e(".dw-li", a4),
						a2 = a5.index(a3),
						aV = a5.length,
						aZ = aY == aR || aR === undefined;
					if (!a3.hasClass("dw-v")) {
						var a1 = a3,
							a0 = a3,
							aX = 0,
							aW = 0;
						while (a2 - aX >= 0 && !a1.hasClass("dw-v")) {
							aX++;
							a1 = a5.eq(a2 - aX)
						}
						while (a2 + aW < aV && !a0.hasClass("dw-v")) {
							aW++;
							a0 = a5.eq(a2 + aW)
						}
						if (((aW < aX && aW && aQ !== 2) || !aX || (a2 - aX < 0) || aQ == 1) && a0.hasClass("dw-v")) {
							a3 = a0;
							a2 = a2 + aW
						} else {
							a3 = a1;
							a2 = a2 - aX
						}
					}
					if (!(a3.hasClass("dw-sel")) || aZ) {
						az.temp[aY] = a3.attr("data-val");
						e(".dw-sel", a4).removeClass("dw-sel");
						if (!aB.multiple) {
							e(".dw-sel", a4).removeAttr("aria-selected");
							a3.attr("aria-selected", "true")
						}
						a3.addClass("dw-sel");
						aC(a4, aY, a2, aZ ? aU : 0.1, aZ ? aT : false)
					}
				});
				aA = aB.formatResult(az.temp);
				if (aB.display == "inline") {
					an(aS, 0, true)
				} else {
					e(".dwv", N).html(C(aA))
				}
				if (aS) {
					aP("onChange", [aA])
				}
			}
		}
		function aP(aS, aR) {
			var aQ;
			aR.push(az);
			e.each([ar.defaults, aq, aK], function(aU, aT) {
				if (aT[aS]) {
					aQ = aT[aS].apply(aM, aR)
				}
			});
			return aQ
		}
		function O(aY, aR, aT, aU, aV) {
			aR = p(aR, aN, ai);
			var aX = e(".dw-li", aY).eq(aR),
				aQ = aV === undefined ? aR : aV,
				aW = au,
				aS = aU ? (aR == aQ ? 0.1 : Math.abs((aR - aQ) * aB.timeUnit)) : 0;
			az.temp[aW] = aX.attr("data-val");
			aC(aY, aW, aR, aS, aV);
			setTimeout(function() {
				aI(aS, aW, true, aT, aV !== undefined)
			}, 10)
		}
		function L(aQ) {
			var aR = ap[au] + 1;
			O(aQ, aR > ai ? aN : aR, 1, true)
		}
		function R(aQ) {
			var aR = ap[au] - 1;
			O(aQ, aR < aN ? ai : aR, 2, true)
		}
		function an(aT, aS, aR, aQ) {
			if (aj && !aR) {
				aI(aS)
			}
			aA = aB.formatResult(az.temp);
			if (!aQ) {
				az.values = az.temp.slice(0);
				az.val = aA
			}
			if (aT) {
				if (F) {
					aL.val(aA).trigger("change")
				}
			}
		}
		az.position = function(a8) {
			if (aB.display == "inline" || (aJ === e(h).width() && aH === e(h).height() && a8) || (aP("onPosition", [N]) === false)) {
				return
			}
			var aX, a3, aZ, aY, a6, a1, a0, a4, aR, a5, aQ, aS, a9 = 0,
				aU = 0,
				a2 = e(h).scrollTop(),
				aT = e(".dwwr", N),
				a7 = e(".dw", N),
				aW = {},
				aV = aB.anchor === undefined ? aL : aB.anchor;
			aJ = e(h).width();
			aH = e(h).height();
			K = h.innerHeight;
			K = K || aH;
			if (/modal|bubble/.test(aB.display)) {
				e(".dwc", N).each(function() {
					aX = e(this).outerWidth(true);
					a9 += aX;
					aU = (aX > aU) ? aX : aU
				});
				aX = a9 > aJ ? aU : a9;
				aT.width(aX).css("white-space", a9 > aJ ? "" : "nowrap")
			}
			U = a7.outerWidth();
			aa = a7.outerHeight(true);
			G = aa <= K && U <= aJ;
			if (aB.display == "modal") {
				a3 = (aJ - U) / 2;
				aZ = a2 + (K - aa) / 2
			} else {
				if (aB.display == "bubble") {
					aS = true;
					aR = e(".dw-arrw-i", N);
					a1 = aV.offset();
					a0 = a1.top;
					a4 = a1.left;
					aY = aV.outerWidth();
					a6 = aV.outerHeight();
					a3 = a4 - (a7.outerWidth(true) - aY) / 2;
					a3 = a3 > (aJ - U) ? (aJ - (U + 20)) : a3;
					a3 = a3 >= 0 ? a3 : 20;
					aZ = a0 - aa;
					if ((aZ < a2) || (a0 > a2 + K)) {
						a7.removeClass("dw-bubble-top").addClass("dw-bubble-bottom");
						aZ = a0 + a6
					} else {
						a7.removeClass("dw-bubble-bottom").addClass("dw-bubble-top")
					}
					a5 = aR.outerWidth();
					aQ = a4 + aY / 2 - (a3 + (U - a5) / 2);
					e(".dw-arr", N).css({
						left: p(aQ, 0, a5)
					})
				} else {
					aW.width = "100%";
					if (aB.display == "top") {
						aZ = a2
					} else {
						if (aB.display == "bottom") {
							aZ = a2 + K - aa
						}
					}
				}
			}
			aW.top = aZ < 0 ? 0 : aZ;
			aW.left = a3;
			a7.css(aW);
			e(".dw-persp", N).height(0).height(aZ + aa > e(document).height() ? aZ + aa : e(document).height());
			if (aS && ((aZ + aa > a2 + K) || (a0 > a2 + K))) {
				e(h).scrollTop(aZ + aa - K)
			}
		};
		az.enable = function() {
			aB.disabled = false;
			if (F) {
				aL.prop("disabled", false)
			}
		};
		az.disable = function() {
			aB.disabled = true;
			if (F) {
				aL.prop("disabled", true)
			}
		};
		az.setValue = function(aR, aT, aS, aQ) {
			az.temp = e.isArray(aR) ? aR.slice(0) : aB.parseValue.call(aM, aR + "", az);
			an(aT, aS, false, aQ)
		};
		az.getValue = function() {
			return az.values
		};
		az.getValues = function() {
			var aQ = [],
				aR;
			for (aR in az._selectedValues) {
				aQ.push(az._selectedValues[aR])
			}
			return aQ
		};
		az.changeWheel = function(aQ, aT) {
			if (N) {
				var aR = 0,
					aS = aQ.length;
				e.each(aB.wheels, function(aV, aU) {
					e.each(aU, function(aX, aW) {
						if (e.inArray(aR, aQ) > -1) {
							Y[aR] = aW;
							e(".dw-ul", N).eq(aR).html(ae(aR));
							aS--;
							if (!aS) {
								az.position();
								aI(aT, undefined, true);
								return false
							}
						}
						aR++
					});
					if (!aS) {
						return false
					}
				})
			}
		};
		az.isVisible = function() {
			return aj
		};
		az.tap = function(aT, aS) {
			var aR, aQ;
			if (aB.tap) {
				aT.on("touchstart.dw", function(aU) {
					aU.preventDefault();
					aR = b(aU, "X");
					aQ = b(aU, "Y")
				}).on("touchend.dw", function(aU) {
					if (Math.abs(b(aU, "X") - aR) < 20 && Math.abs(b(aU, "Y") - aQ) < 20) {
						aS.call(this, aU)
					}
					v = true;
					setTimeout(function() {
						v = false
					}, 300)
				})
			}
			aT.on("click.dw", function(aU) {
				if (!v) {
					aS.call(this, aU)
				}
			})
		};
		az.show = function(aR) {
			if (aB.disabled || aj) {
				return false
			}
			if (aB.display == "top") {
				ay = "slidedown"
			}
			if (aB.display == "bottom") {
				ay = "slideup"
			}
			af();
			aP("onBeforeShow", []);
			var aT, aQ = 0,
				aU = "";
			if (ay && !aR) {
				aU = "dw-" + ay + " dw-in"
			}
			var aS = '<div role="dialog" class="' + aB.theme + " dw-" + aB.display + (s ? " dw" + s : "") + '">' + (aB.display == "inline" ? '<div class="dw dwbg dwi"><div class="dwwr">' : '<div class="dw-persp"><div class="dwo"></div><div class="dw dwbg ' + aU + '"><div class="dw-arrw"><div class="dw-arrw-i"><div class="dw-arr"></div></div></div><div class="dwwr"><div aria-live="assertive" class="dwv' + (aB.headerText ? "" : " dw-hidden") + '"></div>') + '<div class="dwcc">';
			e.each(aB.wheels, function(aW, aV) {
				aS += '<div class="dwc' + (aB.mode != "scroller" ? " dwpm" : " dwsc") + (aB.showLabel ? "" : " dwhl") + '"><div class="dwwc dwrc"><table cellpadding="0" cellspacing="0"><tr>';
				e.each(aV, function(aY, aX) {
					Y[aQ] = aX;
					aT = aX.label !== undefined ? aX.label : aY;
					aS += '<td><div class="dwwl dwrc dwwl' + aQ + '">' + (aB.mode != "scroller" ? '<div class="dwb-e dwwb dwwbp" style="height:' + aO + "px;line-height:" + aO + 'px;"><span>+</span></div><div class="dwb-e dwwb dwwbm" style="height:' + aO + "px;line-height:" + aO + 'px;"><span>&ndash;</span></div>' : "") + '<div class="dwl">' + aT + '</div><div tabindex="0" aria-live="off" aria-label="' + aT + '" role="listbox" class="dwww"><div class="dww" style="height:' + (aB.rows * aO) + "px;min-width:" + aB.width + 'px;"><div class="dw-ul">';
					aS += ae(aQ);
					aS += '</div><div class="dwwol"></div></div><div class="dwwo"></div></div><div class="dwwol"></div></div></td>';
					aQ++
				});
				aS += "</tr></table></div></div>"
			});
			aS += "</div>" + (aB.display != "inline" ? '<div class="dwbc' + (aB.button3 ? " dwbc-p" : "") + '"><span class="dwbw dwb-s"><span class="dwb dwb-e" role="button" tabindex="0">' + aB.setText + "</span></span>" + (aB.button3 ? '<span class="dwbw dwb-n"><span class="dwb dwb-e" role="button" tabindex="0">' + aB.button3Text + "</span></span>" : "") + '<span class="dwbw dwb-c"><span class="dwb dwb-e" role="button" tabindex="0">' + aB.cancelText + "</span></span></div></div>" : "") + "</div></div></div>";
			N = e(aS);
			aI();
			aP("onMarkupReady", [N]);
			if (aB.display != "inline") {
				N.appendTo("body");
				if (ay && !aR) {
					N.addClass("dw-trans");
					setTimeout(function() {
						N.removeClass("dw-trans").find(".dw").removeClass(aU)
					}, 350)
				}
			} else {
				if (aL.is("div")) {
					aL.html(N)
				} else {
					N.insertAfter(aL)
				}
			}
			aP("onMarkupInserted", [N]);
			aj = true;
			ar.init(N, az);
			if (aB.display != "inline") {
				az.tap(e(".dwb-s span", N), function() {
					az.select()
				});
				az.tap(e(".dwb-c span", N), function() {
					az.cancel()
				});
				if (aB.button3) {
					az.tap(e(".dwb-n span", N), aB.button3)
				}
				e(h).on("keydown.dw", function(aV) {
					if (aV.keyCode == 13) {
						az.select()
					} else {
						if (aV.keyCode == 27) {
							az.cancel()
						}
					}
				});
				if (aB.scrollLock) {
					N.on("touchmove touchstart", function(aV) {
						if (G) {
							aV.preventDefault()
						}
					})
				}
				e("input,select,button").each(function() {
					if (!this.disabled) {
						if (e(this).attr("autocomplete")) {
							e(this).data("autocomplete", e(this).attr("autocomplete"))
						}
						e(this).addClass("dwtd").prop("disabled", true).attr("autocomplete", "off")
					}
				});
				az.position();
				e(h).on("orientationchange.dw resize.dw scroll.dw", function(aV) {
					clearTimeout(V);
					V = setTimeout(function() {
						var aW = aV.type == "scroll";
						if ((aW && G) || !aW) {
							az.position(!aW)
						}
					}, 100)
				});
				az.alert(aB.ariaDesc)
			}
			e(".dwwl", N).on("DOMMouseScroll mousewheel", X).on(r, Z).on("keydown", S).on("keyup", H);
			N.on(r, ".dwb-e", ag).on("keydown", ".dwb-e", function(aV) {
				if (aV.keyCode == 32) {
					aV.preventDefault();
					aV.stopPropagation();
					e(this).click()
				}
			});
			aP("onShow", [N, aA])
		};
		az.hide = function(aQ, aR) {
			if (!aj || aP("onClose", [aA, aR]) === false) {
				return false
			}
			e(".dwtd").each(function() {
				e(this).prop("disabled", false).removeClass("dwtd");
				if (e(this).data("autocomplete")) {
					e(this).attr("autocomplete", e(this).data("autocomplete"))
				} else {
					e(this).removeAttr("autocomplete")
				}
			});
			if (N) {
				if (aB.display != "inline" && ay && !aQ) {
					N.addClass("dw-trans").find(".dw").addClass("dw-" + ay + " dw-out");
					setTimeout(function() {
						N.remove();
						N = null
					}, 350)
				} else {
					N.remove();
					N = null
				}
				e(h).off(".dw")
			}
			T = {};
			aj = false;
			ax = true;
			aL.focus()
		};
		az.select = function() {
			if (az.hide(false, "set") !== false) {
				an(true, 0, true);
				aP("onSelect", [az.val])
			}
		};
		az.alert = function(aQ) {
			d.text(aQ);
			clearTimeout(q);
			q = setTimeout(function() {
				d.text("")
			}, 5000)
		};
		az.cancel = function() {
			if (az.hide(false, "cancel") !== false) {
				aP("onCancel", [az.val])
			}
		};
		az.init = function(aQ) {
			ar = w({
				defaults: {},
				init: i
			}, W.themes[aQ.theme || aB.theme]);
			Q = W.i18n[aQ.lang || aB.lang];
			w(aK, aQ);
			w(aB, ar.defaults, Q, aK);
			az.settings = aB;
			aL.off(".dw");
			var aR = W.presets[aB.preset];
			if (aR) {
				aq = aR.call(aM, az);
				w(aB, aq, aK)
			}
			aG = Math.floor(aB.rows / 2);
			aO = aB.height;
			ay = aB.animate;
			if (aj) {
				az.hide()
			}
			if (aB.display == "inline") {
				az.show()
			} else {
				af();
				if (F) {
					if (E === undefined) {
						E = aM.readOnly
					}
					aM.readOnly = true;
					if (aB.showOnFocus) {
						aL.on("focus.dw", function() {
							if (!ax) {
								az.show()
							}
							ax = false
						})
					}
				}
				if (aB.showOnTap) {
					az.tap(aL, function() {
						az.show()
					})
				}
			}
		};
		az.trigger = function(aQ, aR) {
			return aP(aQ, aR)
		};
		az.option = function(aQ, aR) {
			var aS = {};
			if (typeof aQ === "object") {
				aS = aQ
			} else {
				aS[aQ] = aR
			}
			az.init(aS)
		};
		az.destroy = function() {
			az.hide();
			aL.off(".dw");
			delete k[aM.id];
			if (F) {
				aM.readOnly = E
			}
		};
		az.getInst = function() {
			return az
		};
		az.values = null;
		az.val = null;
		az.temp = null;
		az._selectedValues = {};
		az.init(aK)
	}
	function B(D) {
		var C;
		for (C in D) {
			if (A[D[C]] !== undefined) {
				return true
			}
		}
		return false
	}
	function c() {
		var C = ["Webkit", "Moz", "O", "ms"],
			D;
		for (D in C) {
			if (B([C[D] + "Transform"])) {
				return "-" + C[D].toLowerCase()
			}
		}
		return ""
	}
	function a(C) {
		if (C.type === "touchstart") {
			f = true
		} else {
			if (f) {
				f = false;
				return false
			}
		}
		return true
	}
	function b(D, F) {
		var E = D.originalEvent,
			C = D.changedTouches;
		return C || (E && E.changedTouches) ? (E ? E.changedTouches[0]["page" + F] : C[0]["page" + F]) : D["page" + F]
	}
	function p(E, D, C) {
		return Math.max(D, Math.min(E, C))
	}
	function g(C) {
		var D = {
			values: [],
			keys: []
		};
		e.each(C, function(F, E) {
			D.keys.push(F);
			D.values.push(E)
		});
		return D
	}
	function t(F, E, D) {
		var C = F;
		if (typeof E === "object") {
			return F.each(function() {
				if (!this.id) {
					u += 1;
					this.id = "mobiscroll" + u
				}
				k[this.id] = new x(this, E)
			})
		}
		if (typeof E === "string") {
			F.each(function() {
				var G, H = k[this.id];
				if (H && H[E]) {
					G = H[E].apply(this, Array.prototype.slice.call(D, 1));
					if (G !== undefined) {
						C = G;
						return false
					}
				}
			})
		}
		return C
	}
	var n, v, f, q, d, z = new Date(),
		u = z.getTime(),
		k = {},
		i = function() {},
		A = document.createElement("modernizr").style,
		l = B(["perspectiveProperty", "WebkitPerspective", "MozPerspective", "OPerspective", "msPerspective"]),
		s = c(),
		o = s.replace(/^\-/, "").replace("moz", "Moz"),
		w = e.extend,
		r = "touchstart mousedown",
		y = "touchmove mousemove",
		m = "touchend mouseup",
		j = {
			width: 70,
			height: 40,
			rows: 3,
			delay: 300,
			disabled: false,
			readonly: false,
			showOnFocus: true,
			showOnTap: true,
			showLabel: true,
			wheels: [],
			theme: "ios",
			headerText: "{value}",
			display: "bottom",
			mode: "scroller",
			preset: "",
			lang: "zh",
			setText: "Set",
			cancelText: "Cancel",
			ariaDesc: "Select a value",
			scrollLock: true,
			tap: true,
			speedUnit: 0.0012,
			timeUnit: 0.1,
			formatResult: function(C) {
				return C.join(" ")
			},
			parseValue: function(G, F) {
				var H = G.split(" "),
					C = [],
					D = 0,
					E;
				e.each(F.settings.wheels, function(J, I) {
					e.each(I, function(L, K) {
						K = K.values ? K : g(K);
						E = K.keys || K.values;
						if (e.inArray(H[D], E) !== -1) {
							C.push(H[D])
						} else {
							C.push(E[0])
						}
						D++
					})
				});
				return C
			}
		};
	e(function() {
		d = e('<div class="dw-hidden" role="alert"></div>').appendTo("body")
	});
	e(document).on("mouseover mouseup mousedown click", function(C) {
		if (v) {
			C.stopPropagation();
			C.preventDefault();
			return false
		}
	});
	e.fn.mobiscroll = function(C) {
		w(this, e.mobiscroll.shorts);
		return t(this, C, arguments)
	};
	e.mobiscroll = e.mobiscroll || {
		setDefaults: function(C) {
			w(j, C)
		},
		presetShort: function(C) {
			this.shorts[C] = function(D) {
				return t(this, w(D, {
					preset: C
				}), arguments)
			}
		},
		has3d: l,
		shorts: {},
		presets: {},
		themes: {
			ios: {
				defaults: {
					dateOrder: "yyMMd",
					rows: 5,
					height: 30,
					width: 55,
					headerText: false,
					showLabel: false,
					useShortLabels: true
				}
			}
		},
		i18n: {
			zh: {
				setText: "确定",
				cancelText: "取消",
				dateFormat: "yy年mm月dd日",
				dateOrder: "yymmdd",
				dayNames: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"],
				dayNamesShort: ["日", "一", "二", "三", "四", "五", "六"],
				dayText: "日",
				hourText: "时",
				minuteText: "分",
				monthNames: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
				monthNamesShort: ["一", "二", "三", "四", "五", "六", "七", "八", "九", "十", "十一", "十二"],
				monthText: "月",
				secText: "秒",
				timeFormat: "HH:ii",
				timeWheels: "HHii",
				yearText: "年",
				nowText: "当前",
				dateText: "日",
				timeText: "时间",
				calendarText: "日历",
				wholeText: "Whole",
				fractionText: "Fraction",
				unitText: "Unit",
				labels: ["Years", "Months", "Days", "Hours", "Minutes", "Seconds", ""],
				labelsShort: ["Yrs", "Mths", "Days", "Hrs", "Mins", "Secs", ""],
				startText: "Start",
				stopText: "Stop",
				resetText: "Reset",
				lapText: "Lap",
				hideText: "Hide"
			}
		}
	};
	e.scroller = e.scroller || e.mobiscroll;
	e.fn.scroller = e.fn.scroller || e.fn.mobiscroll
})(window, jQuery);
(function(a) {
	var b = {
		inputClass: "",
		invalid: [],
		rtl: false,
		group: false,
		groupLabel: "Groups"
	};
	a.mobiscroll.presetShort("select");
	a.mobiscroll.presets.select = function(f) {
		var e = a.extend({}, f.settings),
			t = a.extend(f.settings, b, e),
			h = a(this),
			G = h.prop("multiple"),
			x = this.id + "_dummy",
			r = G ? (h.val() ? h.val()[0] : a("option", h).attr("value")) : h.val(),
			j = h.find('option[value="' + r + '"]').parent(),
			u = j.index() + "",
			y = u,
			q, A = a('label[for="' + this.id + '"]').attr("for", x),
			z = a('label[for="' + x + '"]'),
			k = t.label !== undefined ? t.label : (z.length ? z.text() : h.attr("name")),
			D = [],
			d = [],
			g = {},
			l, F, n, p, C = t.readonly,
			m;

		function E() {
			var i, I = 0,
				v = [],
				J = [],
				s = [
					[]
				];
			if (t.group) {
				if (t.rtl) {
					I = 1
				}
				a("optgroup", h).each(function(w) {
					v.push(a(this).attr("label"));
					J.push(w)
				});
				s[I] = [{
					values: v,
					keys: J,
					label: t.groupLabel
				}];
				i = j;
				I += (t.rtl ? -1 : 1)
			} else {
				i = h
			}
			v = [];
			J = [];
			a("option", i).each(function() {
				var w = a(this).attr("value");
				v.push(a(this).text());
				J.push(w);
				if (a(this).prop("disabled")) {
					D.push(w)
				}
			});
			s[I] = [{
				values: v,
				keys: J,
				label: k
			}];
			return s
		}
		function c(s, K) {
			var J = [];
			if (G) {
				var I = [],
					w = 0;
				for (w in f._selectedValues) {
					I.push(g[w]);
					J.push(w)
				}
				p.val(I.join(", "))
			} else {
				p.val(s);
				J = K ? f.values[F] : null
			}
			if (K) {
				q = true;
				h.val(J).trigger("change")
			}
		}
		function H(i) {
			if (G && i.hasClass("dw-v") && i.closest(".dw").find(".dw-ul").index(i.closest(".dw-ul")) == F) {
				var v = i.attr("data-val"),
					s = i.hasClass("dw-msel");
				if (s) {
					i.removeClass("dw-msel").removeAttr("aria-selected");
					delete f._selectedValues[v]
				} else {
					i.addClass("dw-msel").attr("aria-selected", "true");
					f._selectedValues[v] = v
				}
				if (t.display == "inline") {
					c(v, true)
				}
				return false
			}
		}
		if (t.group && !a("optgroup", h).length) {
			t.group = false
		}
		if (!t.invalid.length) {
			t.invalid = D
		}
		if (t.group) {
			if (t.rtl) {
				l = 1;
				F = 0
			} else {
				l = 0;
				F = 1
			}
		} else {
			l = -1;
			F = 0
		}
		a("#" + x).remove();
		p = a('<input type="text" id="' + x + '" class="' + t.inputClass + '" readonly />').insertBefore(h), a("option", h).each(function() {
			g[a(this).attr("value")] = a(this).text()
		});
		if (t.showOnFocus) {
			p.focus(function() {
				f.show()
			})
		}
		if (t.showOnTap) {
			f.tap(p, function() {
				f.show()
			})
		}
		var o = h.val() || [],
			B = 0;
		for (B; B < o.length; B++) {
			f._selectedValues[o[B]] = o[B]
		}
		c(g[r]);
		h.off(".dwsel").on("change.dwsel", function() {
			if (!q) {
				f.setValue(G ? h.val() || [] : [h.val()], true)
			}
			q = false
		}).hide().closest(".ui-field-contain").trigger("create");
		if (!f._setValue) {
			f._setValue = f.setValue
		}
		f.setValue = function(K, O, w, s, N) {
			var L, M = a.isArray(K) ? K[0] : K;
			r = M !== undefined ? M : a("option", h).attr("value");
			if (G) {
				f._selectedValues = {};
				var J = 0;
				for (J; J < K.length; J++) {
					f._selectedValues[K[J]] = K[J]
				}
			}
			if (t.group) {
				j = h.find('option[value="' + r + '"]').parent();
				y = j.index();
				L = t.rtl ? [r, j.index()] : [j.index(), r];
				if (y !== u) {
					t.wheels = E();
					f.changeWheel([F]);
					u = y + ""
				}
			} else {
				L = [r]
			}
			f._setValue(L, O, w, s, N);
			if (O) {
				var I = G ? true : r !== h.val();
				c(g[r], I)
			}
		};
		f.getValue = function(i) {
			var s = i ? f.temp : f.values;
			return s[F]
		};
		return {
			width: 50,
			wheels: m,
			headerText: false,
			multiple: G,
			anchor: p,
			formatResult: function(i) {
				return g[i[F]]
			},
			parseValue: function() {
				var s = h.val() || [],
					w = 0;
				if (G) {
					f._selectedValues = {};
					for (w; w < s.length; w++) {
						f._selectedValues[s[w]] = s[w]
					}
				}
				r = G ? (h.val() ? h.val()[0] : a("option", h).attr("value")) : h.val();
				j = h.find('option[value="' + r + '"]').parent();
				y = j.index();
				u = y + "";
				return t.group && t.rtl ? [r, y] : t.group ? [y, r] : [r]
			},
			validate: function(I, K, L) {
				if (K === undefined && G) {
					var s = f._selectedValues,
						w = 0;
					a(".dwwl" + F + " .dw-li", I).removeClass("dw-msel").removeAttr("aria-selected");
					for (w in s) {
						a(".dwwl" + F + ' .dw-li[data-val="' + s[w] + '"]', I).addClass("dw-msel").attr("aria-selected", "true")
					}
				}
				if (K === l) {
					y = f.temp[l];
					if (y !== u) {
						j = h.find("optgroup").eq(y);
						y = j.index();
						r = j.find("option").eq(0).val();
						r = r || h.val();
						t.wheels = E();
						if (t.group) {
							f.temp = t.rtl ? [r, y] : [y, r];
							t.readonly = [t.rtl, !t.rtl];
							clearTimeout(n);
							n = setTimeout(function() {
								f.changeWheel([F]);
								t.readonly = C;
								u = y + ""
							}, L * 1000);
							return false
						}
					} else {
						t.readonly = C
					}
				} else {
					r = f.temp[F]
				}
				var J = a(".dw-ul", I).eq(F);
				a.each(t.invalid, function(N, M) {
					a('.dw-li[data-val="' + M + '"]', J).removeClass("dw-v")
				})
			},
			onBeforeShow: function(i) {
				t.wheels = E();
				if (t.group) {
					f.temp = t.rtl ? [r, j.index()] : [j.index(), r]
				}
			},
			onMarkupReady: function(s) {
				a(".dwwl" + l, s).on("mousedown touchstart", function() {
					clearTimeout(n)
				});
				if (G) {
					s.addClass("dwms");
					a(".dwwl", s).eq(F).addClass("dwwms").attr("aria-multiselectable", "true");
					a(".dwwl", s).on("keydown", function(i) {
						if (i.keyCode == 32) {
							i.preventDefault();
							i.stopPropagation();
							H(a(".dw-sel", this))
						}
					});
					d = {};
					var v;
					for (v in f._selectedValues) {
						d[v] = f._selectedValues[v]
					}
				}
			},
			onValueTap: H,
			onSelect: function(i) {
				c(i, true);
				if (t.group) {
					f.values = null
				}
			},
			onCancel: function() {
				if (t.group) {
					f.values = null
				}
				if (G) {
					f._selectedValues = {};
					var s;
					for (s in d) {
						f._selectedValues[s] = d[s]
					}
				}
			},
			onChange: function(i) {
				if (t.display == "inline" && !G) {
					p.val(i);
					q = true;
					h.val(f.temp[F]).trigger("change")
				}
			},
			onClose: function() {
				p.blur()
			}
		}
	}
})(jQuery);
(function(d) {
	var b = d.mobiscroll,
		a = new Date(),
		e = {
			dateFormat: "mm/dd/yy",
			dateOrder: "mmddy",
			timeWheels: "hhiiA",
			timeFormat: "hh:ii A",
			startYear: a.getFullYear() - 100,
			endYear: a.getFullYear() + 1,
			monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
			monthNamesShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
			dayNames: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
			dayNamesShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
			shortYearCutoff: "+10",
			monthText: "Month",
			dayText: "Day",
			yearText: "Year",
			hourText: "Hours",
			minuteText: "Minutes",
			secText: "Seconds",
			ampmText: "&nbsp;",
			nowText: "Now",
			showNow: false,
			stepHour: 1,
			stepMinute: 1,
			stepSecond: 1,
			separator: " "
		},
		c = function(H) {
			var L = d(this),
				u = {},
				G;
			if (L.is("input")) {
				switch (L.attr("type")) {
				case "date":
					G = "yy-mm-dd";
					break;
				case "datetime":
					G = "yy-mm-ddTHH:ii:ssZ";
					break;
				case "datetime-local":
					G = "yy-mm-ddTHH:ii:ss";
					break;
				case "month":
					G = "yy-mm";
					u.dateOrder = "mmyy";
					break;
				case "time":
					G = "HH:ii:ss";
					break
				}
				var aa = L.attr("min"),
					A = L.attr("max");
				if (aa) {
					u.minDate = b.parseDate(G, aa)
				}
				if (A) {
					u.maxDate = b.parseDate(G, A)
				}
			}
			var V, T, E, ab, m, B, x, h = d.extend({}, H.settings),
				O = d.extend(H.settings, e, u, h),
				M = 0,
				v = [],
				W = [],
				R = {},
				X = {
					y: "getFullYear",
					m: "getMonth",
					d: "getDate",
					h: Y,
					i: N,
					s: q,
					a: z
				},
				P = O.preset,
				Z = O.dateOrder,
				D = O.timeWheels,
				t = Z.match(/D/),
				C = D.match(/a/i),
				l = D.match(/h/),
				S = P == "datetime" ? O.dateFormat + O.separator + O.timeFormat : P == "time" ? O.timeFormat : O.dateFormat,
				g = new Date(),
				r = O.stepHour,
				n = O.stepMinute,
				j = O.stepSecond,
				K = O.minDate || new Date(O.startYear, 0, 1),
				w = O.maxDate || new Date(O.endYear, 11, 31, 23, 59, 59);
			G = G || S;
			if (P.match(/date/i)) {
				d.each(["y", "m", "d"], function(i, f) {
					V = Z.search(new RegExp(f, "i"));
					if (V > -1) {
						W.push({
							o: V,
							v: f
						})
					}
				});
				W.sort(function(i, f) {
					return i.o > f.o ? 1 : -1
				});
				d.each(W, function(k, f) {
					R[f.v] = k
				});
				m = [];
				for (T = 0; T < 3; T++) {
					if (T == R.y) {
						M++;
						ab = [];
						E = [];
						B = K.getFullYear();
						x = w.getFullYear();
						for (V = B; V <= x; V++) {
							E.push(V);
							ab.push(Z.match(/yy/i) ? V : (V + "").substr(2, 2))
						}
						F(m, E, ab, O.yearText)
					} else {
						if (T == R.m) {
							M++;
							ab = [];
							E = [];
							for (V = 0; V < 12; V++) {
								var I = Z.replace(/[dy]/gi, "").replace(/mm/, V < 9 ? "0" + (V + 1) : V + 1).replace(/m/, (V + 1));
								E.push(V);
								ab.push(I.match(/MM/) ? I.replace(/MM/, '<span class="dw-mon">' + O.monthNames[V] + "</span>") : I.replace(/M/, '<span class="dw-mon">' + O.monthNamesShort[V] + "</span>"))
							}
							F(m, E, ab, O.monthText)
						} else {
							if (T == R.d) {
								M++;
								ab = [];
								E = [];
								for (V = 1; V < 32; V++) {
									E.push(V);
									ab.push(Z.match(/dd/i) && V < 10 ? "0" + V : V)
								}
								F(m, E, ab, O.dayText)
							}
						}
					}
				}
				v.push(m)
			}
			if (P.match(/time/i)) {
				W = [];
				d.each(["h", "i", "s", "a"], function(k, f) {
					k = D.search(new RegExp(f, "i"));
					if (k > -1) {
						W.push({
							o: k,
							v: f
						})
					}
				});
				W.sort(function(i, f) {
					return i.o > f.o ? 1 : -1
				});
				d.each(W, function(k, f) {
					R[f.v] = M + k
				});
				m = [];
				for (T = M; T < M + 4; T++) {
					if (T == R.h) {
						M++;
						ab = [];
						E = [];
						for (V = 0; V < (l ? 12 : 24); V += r) {
							E.push(V);
							ab.push(l && V == 0 ? 12 : D.match(/hh/i) && V < 10 ? "0" + V : V)
						}
						F(m, E, ab, O.hourText)
					} else {
						if (T == R.i) {
							M++;
							ab = [];
							E = [];
							for (V = 0; V < 60; V += n) {
								E.push(V);
								ab.push(D.match(/ii/) && V < 10 ? "0" + V : V)
							}
							F(m, E, ab, O.minuteText)
						} else {
							if (T == R.s) {
								M++;
								ab = [];
								E = [];
								for (V = 0; V < 60; V += j) {
									E.push(V);
									ab.push(D.match(/ss/) && V < 10 ? "0" + V : V)
								}
								F(m, E, ab, O.secText)
							} else {
								if (T == R.a) {
									M++;
									var Q = D.match(/A/);
									F(m, [0, 1], Q ? ["AM", "PM"] : ["am", "pm"], O.ampmText)
								}
							}
						}
					}
				}
				v.push(m)
			}
			function U(o, f, k) {
				if (R[f] !== undefined) {
					return +o[R[f]]
				}
				if (k !== undefined) {
					return k
				}
				return g[X[f]] ? g[X[f]]() : X[f](g)
			}
			function F(o, i, f, p) {
				o.push({
					values: f,
					keys: i,
					label: p
				})
			}
			function J(f, i) {
				return Math.floor(f / i) * i
			}
			function Y(i) {
				var f = i.getHours();
				f = l && f >= 12 ? f - 12 : f;
				return J(f, r)
			}
			function N(f) {
				return J(f.getMinutes(), n)
			}
			function q(f) {
				return J(f.getSeconds(), j)
			}
			function z(f) {
				return C && f.getHours() > 11 ? 1 : 0
			}
			function y(i) {
				var f = U(i, "h", 0);
				return new Date(U(i, "y"), U(i, "m"), U(i, "d", 1), U(i, "a") ? f + 12 : f, U(i, "i", 0), U(i, "s", 0))
			}
			H.setDate = function(s, p, o, f) {
				var k;
				for (k in R) {
					H.temp[R[k]] = s[X[k]] ? s[X[k]]() : X[k](s)
				}
				H.setValue(H.temp, p, o, f)
			};
			H.getDate = function(f) {
				return y(f ? H.temp : H.values)
			};
			return {
				button3Text: O.showNow ? O.nowText : undefined,
				button3: O.showNow ?
				function() {
					H.setDate(new Date(), false, 0.3, true)
				} : undefined,
				wheels: v,
				headerText: function(f) {
					return b.formatDate(S, y(H.temp), O)
				},
				formatResult: function(f) {
					return b.formatDate(G, y(f), O)
				},
				parseValue: function(s) {
					var p = new Date(),
						k, f = [];
					try {
						p = b.parseDate(G, s, O)
					} catch (o) {}
					for (k in R) {
						f[R[k]] = p[X[k]] ? p[X[k]]() : X[k](p)
					}
					return f
				},
				validate: function(o, p) {
					var f = H.temp,
						ac = {
							y: K.getFullYear(),
							m: 0,
							d: 1,
							h: 0,
							i: 0,
							s: 0,
							a: 0
						},
						k = {
							y: w.getFullYear(),
							m: 11,
							d: 31,
							h: J(l ? 11 : 23, r),
							i: J(59, n),
							s: J(59, j),
							a: 1
						},
						s = true,
						ad = true;
					d.each(["y", "m", "d", "a", "h", "i", "s"], function(ap, al) {
						if (R[al] !== undefined) {
							var ak = ac[al],
								ao = k[al],
								aj = 31,
								ae = U(f, al),
								ar = d(".dw-ul", o).eq(R[al]),
								an, af;
							if (al == "d") {
								an = U(f, "y");
								af = U(f, "m");
								aj = 32 - new Date(an, af, 32).getDate();
								ao = aj;
								if (t) {
									d(".dw-li", ar).each(function() {
										var at = d(this),
											av = at.data("val"),
											i = new Date(an, af, av).getDay(),
											au = Z.replace(/[my]/gi, "").replace(/dd/, av < 10 ? "0" + av : av).replace(/d/, av);
										d(".dw-i", at).html(au.match(/DD/) ? au.replace(/DD/, '<span class="dw-day">' + O.dayNames[i] + "</span>") : au.replace(/D/, '<span class="dw-day">' + O.dayNamesShort[i] + "</span>"))
									})
								}
							}
							if (s && K) {
								ak = K[X[al]] ? K[X[al]]() : X[al](K)
							}
							if (ad && w) {
								ao = w[X[al]] ? w[X[al]]() : X[al](w)
							}
							if (al != "y") {
								var ah = d(".dw-li", ar).index(d('.dw-li[data-val="' + ak + '"]', ar)),
									ag = d(".dw-li", ar).index(d('.dw-li[data-val="' + ao + '"]', ar));
								d(".dw-li", ar).removeClass("dw-v").slice(ah, ag + 1).addClass("dw-v");
								if (al == "d") {
									d(".dw-li", ar).removeClass("dw-h").slice(aj).addClass("dw-h")
								}
							}
							if (ae < ak) {
								ae = ak
							}
							if (ae > ao) {
								ae = ao
							}
							if (s) {
								s = ae == ak
							}
							if (ad) {
								ad = ae == ao
							}
							if (O.invalid && al == "d") {
								var aq = [];
								if (O.invalid.dates) {
									d.each(O.invalid.dates, function(au, at) {
										if (at.getFullYear() == an && at.getMonth() == af) {
											aq.push(at.getDate() - 1)
										}
									})
								}
								if (O.invalid.daysOfWeek) {
									var am = new Date(an, af, 1).getDay(),
										ai;
									d.each(O.invalid.daysOfWeek, function(au, at) {
										for (ai = at - am; ai < aj; ai += 7) {
											if (ai >= 0) {
												aq.push(ai)
											}
										}
									})
								}
								if (O.invalid.daysOfMonth) {
									d.each(O.invalid.daysOfMonth, function(au, at) {
										at = (at + "").split("/");
										if (at[1]) {
											if (at[0] - 1 == af) {
												aq.push(at[1] - 1)
											}
										} else {
											aq.push(at[0] - 1)
										}
									})
								}
								d.each(aq, function(au, at) {
									d(".dw-li", ar).eq(at).removeClass("dw-v")
								})
							}
							f[R[al]] = ae
						}
					})
				}
			}
		};
	d.each(["date", "time", "datetime"], function(g, f) {
		b.presets[f] = c;
		b.presetShort(f)
	});
	b.formatDate = function(q, g, j) {
		if (!g) {
			return null
		}
		var r = d.extend({}, e, j),
			o = function(h) {
				var i = 0;
				while (m + 1 < q.length && q.charAt(m + 1) == h) {
					i++;
					m++
				}
				return i
			},
			l = function(i, s, h) {
				var t = "" + s;
				if (o(i)) {
					while (t.length < h) {
						t = "0" + t
					}
				}
				return t
			},
			k = function(h, u, t, i) {
				return (o(h) ? i[u] : t[u])
			},
			m, f = "",
			p = false;
		for (m = 0; m < q.length; m++) {
			if (p) {
				if (q.charAt(m) == "'" && !o("'")) {
					p = false
				} else {
					f += q.charAt(m)
				}
			} else {
				switch (q.charAt(m)) {
				case "d":
					f += l("d", g.getDate(), 2);
					break;
				case "D":
					f += k("D", g.getDay(), r.dayNamesShort, r.dayNames);
					break;
				case "o":
					f += l("o", (g.getTime() - new Date(g.getFullYear(), 0, 0).getTime()) / 86400000, 3);
					break;
				case "m":
					f += l("m", g.getMonth() + 1, 2);
					break;
				case "M":
					f += k("M", g.getMonth(), r.monthNamesShort, r.monthNames);
					break;
				case "y":
					f += (o("y") ? g.getFullYear() : (g.getYear() % 100 < 10 ? "0" : "") + g.getYear() % 100);
					break;
				case "h":
					var n = g.getHours();
					f += l("h", (n > 12 ? (n - 12) : (n == 0 ? 12 : n)), 2);
					break;
				case "H":
					f += l("H", g.getHours(), 2);
					break;
				case "i":
					f += l("i", g.getMinutes(), 2);
					break;
				case "s":
					f += l("s", g.getSeconds(), 2);
					break;
				case "a":
					f += g.getHours() > 11 ? "pm" : "am";
					break;
				case "A":
					f += g.getHours() > 11 ? "PM" : "AM";
					break;
				case "'":
					if (o("'")) {
						f += "'"
					} else {
						p = true
					}
					break;
				default:
					f += q.charAt(m)
				}
			}
		}
		return f
	};
	b.parseDate = function(x, p, z) {
		var l = new Date();
		if (!x || !p) {
			return l
		}
		p = (typeof p == "object" ? p.toString() : p + "");
		var m = d.extend({}, e, z),
			f = m.shortYearCutoff,
			h = l.getFullYear(),
			B = l.getMonth() + 1,
			v = l.getDate(),
			k = -1,
			y = l.getHours(),
			q = l.getMinutes(),
			i = 0,
			n = -1,
			u = false,
			o = function(s) {
				var D = (g + 1 < x.length && x.charAt(g + 1) == s);
				if (D) {
					g++
				}
				return D
			},
			C = function(D) {
				o(D);
				var E = (D == "@" ? 14 : (D == "!" ? 20 : (D == "y" ? 4 : (D == "o" ? 3 : 2)))),
					F = new RegExp("^\\d{1," + E + "}"),
					s = p.substr(w).match(F);
				if (!s) {
					return 0
				}
				w += s[0].length;
				return parseInt(s[0], 10)
			},
			j = function(E, G, D) {
				var H = (o(E) ? D : G),
					F;
				for (F = 0; F < H.length; F++) {
					if (p.substr(w, H[F].length).toLowerCase() == H[F].toLowerCase()) {
						w += H[F].length;
						return F + 1
					}
				}
				return 0
			},
			t = function() {
				w++
			},
			w = 0,
			g;
		for (g = 0; g < x.length; g++) {
			if (u) {
				if (x.charAt(g) == "'" && !o("'")) {
					u = false
				} else {
					t()
				}
			} else {
				switch (x.charAt(g)) {
				case "d":
					v = C("d");
					break;
				case "D":
					j("D", m.dayNamesShort, m.dayNames);
					break;
				case "o":
					k = C("o");
					break;
				case "m":
					B = C("m");
					break;
				case "M":
					B = j("M", m.monthNamesShort, m.monthNames);
					break;
				case "y":
					h = C("y");
					break;
				case "H":
					y = C("H");
					break;
				case "h":
					y = C("h");
					break;
				case "i":
					q = C("i");
					break;
				case "s":
					i = C("s");
					break;
				case "a":
					n = j("a", ["am", "pm"], ["am", "pm"]) - 1;
					break;
				case "A":
					n = j("A", ["am", "pm"], ["am", "pm"]) - 1;
					break;
				case "'":
					if (o("'")) {
						t()
					} else {
						u = true
					}
					break;
				default:
					t()
				}
			}
		}
		if (h < 100) {
			h += new Date().getFullYear() - new Date().getFullYear() % 100 + (h <= (typeof f != "string" ? f : new Date().getFullYear() % 100 + parseInt(f, 10)) ? 0 : -100)
		}
		if (k > -1) {
			B = 1;
			v = k;
			do {
				var r = 32 - new Date(h, B - 1, 32).getDate();
				if (v <= r) {
					break
				}
				B++;
				v -= r
			} while (true)
		}
		y = (n == -1) ? y : ((n && y < 12) ? (y + 12) : (!n && y == 12 ? 0 : y));
		var A = new Date(h, B - 1, v, y, q, i);
		if (A.getFullYear() != h || A.getMonth() + 1 != B || A.getDate() != v) {
			throw "Invalid date"
		}
		return A
	}
})(jQuery);
(function(c) {
	var a = c.mobiscroll,
		d = {
			invalid: [],
			showInput: true,
			inputClass: ""
		},
		b = function(h) {
			var g = c.extend({}, h.settings),
				z = c.extend(h.settings, d, g),
				k = c(this),
				u, x, B = this.id + "_dummy",
				A = 0,
				i = 0,
				r = {},
				p = z.wheelArray || v(k),
				D = t(A),
				l = [],
				o = C(p),
				q = f(o, A);

			function m(G, w, K, I) {
				var H = 0;
				while (H < w) {
					var J = c(".dwwl" + H, G),
						s = E(I, H, K);
					c.each(s, function(M, L) {
						c('.dw-li[data-val="' + L + '"]', J).removeClass("dw-v")
					});
					H++
				}
			}
			function E(J, G, K) {
				var H = 0,
					L, w = K,
					s = [];
				while (H < G) {
					var I = J[H];
					for (L in w) {
						if (w[L].key == I) {
							w = w[L].children;
							break
						}
					}
					H++
				}
				H = 0;
				while (H < w.length) {
					if (w[H].invalid) {
						s.push(w[H].key)
					}
					H++
				}
				return s
			}
			function F(G, w) {
				var s = [];
				while (G) {
					s[--G] = true
				}
				s[w] = false;
				return s
			}
			function t(w) {
				var s = [],
					G;
				for (G = 0; G < w; G++) {
					s[G] = z.labels && z.labels[G] ? z.labels[G] : G
				}
				return s
			}
			function f(K, s, M) {
				var J = 0,
					H, I, L, N = [],
					G = p;
				if (s) {
					for (H = 0; H < s; H++) {
						N[H] = [{}]
					}
				}
				while (J < K.length) {
					N[J] = [j(G, D[J])];
					H = 0;
					L = undefined;
					while (H < G.length && L === undefined) {
						if (G[H].key == K[J] && ((M !== undefined && J <= M) || M === undefined)) {
							L = H
						}
						H++
					}
					if (L !== undefined && G[L].children) {
						J++;
						G = G[L].children
					} else {
						if ((I = e(G)) && I.children) {
							J++;
							G = I.children
						} else {
							return N
						}
					}
				}
				return N
			}
			function e(H, w) {
				if (!H) {
					return false
				}
				var s = 0,
					G;
				while (s < H.length) {
					if (!(G = H[s++]).invalid) {
						return w ? s - 1 : G
					}
				}
				return false
			}
			function j(G, H) {
				var w = {
					keys: [],
					values: [],
					label: H
				},
					s = 0;
				while (s < G.length) {
					w.values.push(G[s].value);
					w.keys.push(G[s].key);
					s++
				}
				return w
			}
			function n(s, w) {
				c(".dwc", s).css("display", "").slice(w).hide()
			}
			function C(I) {
				var G = [],
					J = I,
					H, w = true,
					s = 0;
				while (w) {
					H = e(J);
					G[s++] = H.key;
					if (w = H.children) {
						J = H.children
					}
				}
				return G
			}
			function y(G, J) {
				var M = [],
					s = p,
					K = 0,
					H = false,
					w, L, I;
				if (G[K] !== undefined && K <= J) {
					w = 0;
					L = G[K];
					I = undefined;
					while (w < s.length && I === undefined) {
						if (s[w].key == G[K] && !s[w].invalid) {
							I = w
						}
						w++
					}
				} else {
					I = e(s, true);
					L = s[I].key
				}
				H = I !== undefined ? s[I].children : false;
				M[K] = L;
				while (H) {
					s = s[I].children;
					K++;
					H = false;
					I = undefined;
					if (G[K] !== undefined && K <= J) {
						w = 0;
						L = G[K];
						I = undefined;
						while (w < s.length && I === undefined) {
							if (s[w].key == G[K] && !s[w].invalid) {
								I = w
							}
							w++
						}
					} else {
						I = e(s, true);
						I = I === false ? undefined : I;
						L = s[I].key
					}
					H = I !== undefined && e(s[I].children) ? s[I].children : false;
					M[K] = L
				}
				return {
					lvl: K + 1,
					nVector: M
				}
			}
			function v(s) {
				var w = [];
				A = A > i++ ? A : i;
				s.children("li").each(function(I) {
					var L = c(this),
						M = L.clone();
					M.children("ul,ol").remove();
					var H = M.html().replace(/^\s\s*/, "").replace(/\s\s*$/, ""),
						G = L.data("invalid") ? true : false,
						K = {
							key: L.data("val") || I,
							value: H,
							invalid: G,
							children: null
						},
						J = L.children("ul,ol");
					if (J.length) {
						K.children = v(J)
					}
					w.push(K)
				});
				i--;
				return w
			}
			c("#" + B).remove();
			if (z.showInput) {
				u = c('<input type="text" id="' + B + '" value="" class="' + z.inputClass + '" readonly />').insertBefore(k);
				h.settings.anchor = u;
				if (z.showOnFocus) {
					u.focus(function() {
						h.show()
					})
				}
				if (z.showOnTap) {
					h.tap(u, function() {
						h.show()
					})
				}
			}
			if (!z.wheelArray) {
				k.hide().closest(".ui-field-contain").trigger("create")
			}
			return {
				width: 50,
				wheels: q,
				headerText: false,
				onBeforeShow: function(s) {
					var w = h.temp;
					l = w.slice(0);
					h.settings.wheels = f(w, A, A);
					x = true
				},
				onSelect: function(s, w) {
					if (u) {
						u.val(s)
					}
				},
				onChange: function(s, w) {
					if (u && z.display == "inline") {
						u.val(s)
					}
				},
				onClose: function() {
					if (u) {
						u.blur()
					}
				},
				onShow: function(s) {
					c(".dwwl", s).on("mousedown touchstart", function() {
						clearTimeout(r[c(".dwwl", s).index(this)])
					})
				},
				validate: function(G, w, J) {
					var I = h.temp;
					if ((w !== undefined && l[w] != I[w]) || (w === undefined && !x)) {
						h.settings.wheels = f(I, null, w);
						var s = [],
							H = (w || 0) + 1,
							K = y(I, w);
						if (w !== undefined) {
							h.temp = K.nVector.slice(0)
						}
						while (H < K.lvl) {
							s.push(H++)
						}
						n(G, K.lvl);
						l = h.temp.slice(0);
						if (s.length) {
							x = true;
							h.settings.readonly = F(A, w);
							clearTimeout(r[w]);
							r[w] = setTimeout(function() {
								h.changeWheel(s);
								h.settings.readonly = false
							}, J * 1000);
							return false
						}
						m(G, K.lvl, p, h.temp)
					} else {
						var K = y(I, I.length);
						m(G, K.lvl, p, I);
						n(G, K.lvl)
					}
					x = false
				}
			}
		};
	c.each(["list", "image", "treelist"], function(f, e) {
		a.presets[e] = b;
		a.presetShort(e)
	})
})(jQuery);
(function(a) {
	var b = b || [];
	(function() {
		var d = a.createElement("script");
		d.src = "//hm.baidu.com/hm.js?f1df238552a2693bb59ed55bb258fd49";
		var c = a.getElementsByTagName("script")[0];
		c.parentNode.insertBefore(d, c)
	})()
})(document);