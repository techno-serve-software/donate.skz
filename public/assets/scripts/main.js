var App = window.App || {};

App = (function($) {

    'use strict';

    var assetsPath = 'assets/';

    var globalImgPath = 'images/';

    var globalPluginsPath = 'scripts/';

    var globalCssPath = 'styles/';

    // MEDIA QUERIES
    var handleDesktopWidthChange = (function() {
        var _$navLinks = $('.main-nav .main-nav-item-parent .main-nav-link');
        var _handleWidthChange = function(mqlVal) {
            if (mqlVal.matches) {
                _$navLinks.on('click', function(e) {
                    e.preventDefault();
                    $(this).parent().toggleClass('selected').siblings().removeClass('selected');
                });
            } else {
                _$navLinks.off('click');
            }
        }
        var init = function() {
            if (window.matchMedia) {
                var mql = window.matchMedia('(max-width: 1199px)');
                mql.addListener(_handleWidthChange);
                _handleWidthChange(mql);
            }
        }
        return {
            init: init
        }
    })();

    // SLIDESHOW
    var slideshow = (function() {
        var _$el = $('.owl-carousel');
        var _config = {
            items: 1,
            loop: true,
            nav: true,
            navText: ['<i class="fa fa-chevron-left" aria-hidden="true"></i>', '<i class="fa fa-chevron-right" aria-hidden="true"></i>'],
            autoplay: true,
            autoplayTimeout: 10000,
            autoplayHoverPause: true,
            onInitialized: function(event) {
                var element = event.target;
                if (!$(element).find('.owl-dots').hasClass('disabled')) {
                    $(element).addClass('owl-dots-enabled');
                }
            }
        };
        var init = function() {
            _$el.each(function() {
                var self = $(this);
                var settings = self.data('slideshow-settings');
                self.owlCarousel($.extend(Object.create(_config), settings));
            });
        }
        return {
            init: init
        }
    })();

    // COUNTDOWN
    var countdown = (function() {
        var _$el = $('.countdown');
        var init = function() {
            _$el.each(function() {
                var $this = $(this);
                var finalDate = $(this).data('countdown');
                $this.countdown(finalDate, function(event) {
                    $this.html(event.strftime('<span class="countdown-inner"><span class="countdown-value">%D</span> <small>Days</small></span>' +
                        '<span class="countdown-inner"><span class="countdown-value">%H</span> <small>Hr</small></span>' +
                        '<span class="countdown-inner"><span class="countdown-value">%M</span> <small>Min</small></span>' +
                        '<span class="countdown-inner"><span class="countdown-value">%S</span> <small>Sec</small></span>'));
                });
            });
        }
        return {
            init: init
        }
    })();

    // BOOTSTRAP TOOLTIPS
    var tooltip = (function() {
        var _$el = $('[data-toggle="tooltip"]');
        var init = function() {
            _$el.tooltip();
        }
        return {
            init: init
        }
    })();

    // POPUPS
    var popup = (function() {
        var _$popupBtn = $('.btn-popup');
        var _popupConfig = {
            removalDelay: 300
        };
        var init = function() {
            _$popupBtn.magnificPopup(_popupConfig);
        }
        return {
            init: init
        }
    })();

    // PRICE SLIDER
    var priceSlider = (function() {
        var _el = document.getElementById('price-slider');
        var _priceMinInput = document.getElementById('price-min');
        var _priceMaxInput = document.getElementById('price-max');
        var _prices = [_priceMinInput, _priceMaxInput];
        var _config = {
            start: [100, 500],
            connect: true,
            step: 10,
            range: {
                'min': 0,
                'max': 1000
            }
        }

        function setSliderHandle(i, value) {
            var r = [null, null];
            r[i] = value;
            _el.noUiSlider.set(r);
        }
        var init = function() {
            if (_el) {
                noUiSlider.create(_el, _config);
                _el.noUiSlider.on('update', function(values, handle) {
                    _prices[handle].value = values[handle];
                });
                _prices.forEach(function(input, handle) {
                    input.addEventListener('change', function() {
                        setSliderHandle(handle, this.value);
                    });
                    input.addEventListener('keydown', function(e) {
                        var values = _el.noUiSlider.get();
                        var value = Number(values[handle]);
                        var steps = _el.noUiSlider.steps();
                        var step = steps[handle];
                        var position;
                        switch (e.which) {
                            case 13:
                                setSliderHandle(handle, this.value);
                                break;
                            case 38:
                                position = step[1];
                                if (position === false) {
                                    position = 1;
                                }
                                if (position !== null) {
                                    setSliderHandle(handle, value + position);
                                }
                                break;
                            case 40:
                                position = step[0];
                                if (position === false) {
                                    position = 1;
                                }
                                if (position !== null) {
                                    setSliderHandle(handle, value - position);
                                }
                                break;
                        }
                    });
                });
            }
        }
        return {
            init: init
        }
    })();

    // VALIDATION
    var validation = (function() {
        var _$el = $('form');
        var _config = {
            errorPlacement: function() {}
        }
        var init = function() {
            _$el.each(function() {
                $(this).validate(_config);
            });
        }
        return {
            init: init
        }
    })();

    // SEARCH
    var toggleSearchForm = (function() {
        var _$el = $('.site-search');
        var _$elTrigger = $('.user-nav-search-link');
        var _$elClose = $('.site-search-close-btn');
        var init = function() {
            _$elTrigger.on('click', function() {
                _$el.addClass('is-visible');
            });
            _$elClose.on('click', function() {
                _$el.removeClass('is-visible');
            });
        }
        return {
            init: init
        }
    })();

    // DONATION FORM
    var donationForm = (function() {
        var _$el = $('.donation-form');
        var _$elPaymentOnline = _$el.find('#payment-method-online');
        var _$elPaymentOffline = _$el.find('#payment-method-offline');
        var _$elRecurranceGroup = _$el.find('#recurrance-group');
        var init = function() {
            _$elPaymentOnline.on('click', function() {
                _$elRecurranceGroup.show();
            });
            _$elPaymentOffline.on('click', function() {
                _$elRecurranceGroup.hide();
            });
        }
        return {
            init: init
        }
    })();

    // CHECKOUT FORM
    var checkoutForm = (function() {
        var _$el = $('.checkout-form');
        var _$elAccountInput = _$el.find('#create-account');
        var _$elPasswordGroup = _$el.find('#password-group');
        var init = function() {
            _$elAccountInput.on('click', function() {
                _$elPasswordGroup.toggle();
            });
        }
        return {
            init: init
        }
    })();

    // CONTACT FORM
    var contactForm = (function() {
        var _$el = $('.contact-form');
        var _$elSubmit = _$el.find('[type="submit"]');
        var init = function() {
            _$el.submit(function(e) {
                e.preventDefault();
                _$el.find('.loader').remove();
                if (_$el.valid()) {
                    var dataString = _$el.serialize();
                    _$elSubmit.after('<div class="loader"></div>');
                    $.ajax({
                        type: _$el.attr('method'),
                        url: _$el.attr('action'),
                        data: dataString
                    }).done(function() {
                        _$elSubmit.parent().after('<div class="alert alert-success">Your message was sent successfully!</div>');
                    }).fail(function() {
                        _$elSubmit.parent().after('<div class="alert alert-danger">Your message wasn\'t sent, please try again.</div>');
                    }).always(function() {
                        _$el.find('.loader').remove();
                        _$el.find('.alert').fadeIn();
                        setTimeout(function() {
                            _$el.find('.alert').fadeOut(function() {
                                $(this).remove();
                            });
                        }, 5000);
                    });
                }
            });
        }
        return {
            init: init
        }
    })();

    // MAP
    var contactsMap = (function() {
        var _$el = document.getElementById('map');

        var init = function() {
            if (_$el) {
                L.TileLayer.Grayscale = L.TileLayer.extend({
                    options: {
                        quotaRed: 21,
                        quotaGreen: 71,
                        quotaBlue: 8,
                        quotaDividerTune: 0,
                        quotaDivider: function() {
                            return this.quotaRed + this.quotaGreen + this.quotaBlue + this.quotaDividerTune;
                        }
                    },

                    initialize: function(url, options) {
                        options = options || {};
                        options.crossOrigin = true;
                        L.TileLayer.prototype.initialize.call(this, url, options);

                        this.on('tileload', function(e) {
                            this._makeGrayscale(e.tile);
                        });
                    },

                    _createTile: function() {
                        var tile = L.TileLayer.prototype._createTile.call(this);
                        tile.crossOrigin = 'Anonymous';
                        return tile;
                    },

                    _makeGrayscale: function(img) {
                        if (img.getAttribute('data-grayscaled')) {
                            return;
                        }

                        img.crossOrigin = '';
                        var canvas = document.createElement('canvas');
                        canvas.width = img.width;
                        canvas.height = img.height;
                        var ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0);

                        var imgd = ctx.getImageData(0, 0, canvas.width, canvas.height);
                        var pix = imgd.data;
                        for (var i = 0, n = pix.length; i < n; i += 4) {
                            pix[i] = pix[i + 1] = pix[i + 2] = (this.options.quotaRed * pix[i] + this.options.quotaGreen * pix[i + 1] + this.options.quotaBlue * pix[i + 2]) / this.options.quotaDivider();
                        }
                        ctx.putImageData(imgd, 0, 0);
                        img.setAttribute('data-grayscaled', true);
                        img.src = canvas.toDataURL();
                    }
                });

                L.tileLayer.grayscale = function(url, options) {
                    return new L.TileLayer.Grayscale(url, options);
                };

                var map = L.map(_$el, {
                    center: [mapObjectsData[0].lat, mapObjectsData[0].lng],
                    zoom: 18,
                    'zoomControl': false
                });
                var icon = L.icon({
                    iconUrl: 'images/map-marker.png',
                    iconSize: [42, 42],
                    iconAnchor: [42, 42]
                });

                var zoomControl = L.control.zoom({
                    position: 'topright'
                });
                map.addControl(zoomControl);

                L.tileLayer.grayscale('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

                var markers_data = [];
                if (mapObjectsData.length > 0) {

                    for (var i = 0, l = mapObjectsData.length; i < l; i++) {
                        var item = mapObjectsData[i];
                        if (item.lat != undefined && item.lng != undefined) {
                            var lat = item.lat;
                            var lng = item.lng;
                            var marker = L.marker([lat, lng], { icon: icon });
                            marker.bindPopup('<h3>' + item.title + '</h3><p>' + item.description + '</p><address>' + item.address + '</address>').openPopup();
                            markers_data.push(marker);
                        }
                    }
                }
                var group = new L.featureGroup(markers_data);
                group.addTo(map);
                map.fitBounds(group.getBounds());
            }
        }
        return {
            init: init
        }
    })();

    // TOGGLE MAIN NAV
    var toggleMainNav = (function() {
        var _$el = $('.main-nav');
        var _$elToggle = $('.main-nav-toggle-btn');
        var init = function() {
            _$elToggle.on('click', function() {
                _$el.toggleClass('is-visible');
            });
        }
        return {
            init: init
        }
    })();

    // TOGGLE USER NAV
    var toggleUserNav = (function() {
        var _$el = $('.user-nav');
        var _$elToggle = $('.user-nav-toggle-btn');
        var init = function() {
            _$elToggle.on('click', function() {
                _$el.toggleClass('is-visible');
                _$elToggle.toggleClass('is-active');
            });
        }
        return {
            init: init
        }
    })();

    // PROGRESS BARS
    var progress = (function() {
        var _$el = $('.progress');
        var _$tooltip = _$el.find('[data-toggle="progress-tooltip"]');
        var init = function() {
            _$el.each(function() {
                var self = $(this);
                var _$elProgress = self.find('.progress-bar');
                var val = _$elProgress.attr('aria-valuenow');
                _$elProgress.width(val + '%');
                setTimeout(function() {
                    _$tooltip.tooltip({
                        trigger: 'manual'
                    }).tooltip('show');
                }, 600);
            });
        }
        return {
            init: init
        }
    })();




    return {
        init: function() {
            slideshow.init();
            countdown.init();
            tooltip.init();
            popup.init();
            priceSlider.init();
            toggleSearchForm.init();
            validation.init();
            donationForm.init();
            checkoutForm.init();
            contactForm.init();
            contactsMap.init();
            toggleMainNav.init();
            toggleUserNav.init();
            handleDesktopWidthChange.init();
            progress.init();
        },

        blockUI: function(options) {
            options = $.extend(true, {}, options);
            var html = '';
            if (options.animate) {
                html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '">' + '<div class="block-spinner-bar"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>' + '</div>';
            } else if (options.iconOnly) {
                html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><img src="' + this.getGlobalImgPath() + 'loading-spinner-blue.gif" align=""></div>';
            } else if (options.textOnly) {
                html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><span>&nbsp;&nbsp;' + (options.message ? options.message : 'LOADING...') + '</span></div>';
            } else {
                html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><img src="' + this.getGlobalImgPath() + 'loading-spinner-blue.gif" align=""><span>&nbsp;&nbsp;' + (options.message ? options.message : 'LOADING...') + '</span></div>';
            }

            if (options.target) { // element blocking
                var el = $(options.target);
                if (el.height() <= ($(window).height())) {
                    options.cenrerY = true;
                }
                el.block({
                    message: html,
                    baseZ: options.zIndex ? options.zIndex : 1000,
                    centerY: options.cenrerY !== undefined ? options.cenrerY : false,
                    css: {
                        top: '10%',
                        border: '0',
                        padding: '0',
                        backgroundColor: 'none'
                    },
                    overlayCSS: {
                        backgroundColor: options.overlayColor ? options.overlayColor : '#555',
                        opacity: options.boxed ? 0.05 : 0.1,
                        cursor: 'wait'
                    }
                });
            } else { // page blocking
                $.blockUI({
                    message: html,
                    baseZ: options.zIndex ? options.zIndex : 1000,
                    css: {
                        border: '0',
                        padding: '0',
                        backgroundColor: 'none'
                    },
                    overlayCSS: {
                        backgroundColor: options.overlayColor ? options.overlayColor : '#555',
                        opacity: options.boxed ? 0.05 : 0.1,
                        cursor: 'wait'
                    }
                });
            }
        },

        // wrMetronicer function to  un-block element(finish loading)
        unblockUI: function(target) {
            if (target) {
                $(target).unblock({
                    onUnblock: function() {
                        $(target).css('position', '');
                        $(target).css('zoom', '');
                    }
                });
            } else {
                $.unblockUI();
            }
        },

        allowNumber: function() {
            $(document).on('keydown', '.allow-only-number', function(e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                    // Allow: Ctrl+A, Command+A
                    (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                    // Allow: home, end, left, right, down, up
                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
        },

        textUpper: function() {
            $(document).on('keydown', '.txtuppercase', function(e) {
                $(this).val($(this).val().toUpperCase());
            });
        },

        textFirstCapEachWord: function() {
            $(document).on('keyup', '.txtfirstcapitaleachword', function(event) {
                var str = $(this).val();

                var spart = str.split(" ");
                for (var i = 0; i < spart.length; i++) {
                    var j = spart[i].charAt(0).toUpperCase();
                    spart[i] = j + spart[i].substr(1);
                }
                $(this).val(spart.join(" "));
            });
        },

        textFirstCap: function() {
            $(document).on('keyup', '.txtcapital', function(event) {
                var caps = $(this).val();
                caps = caps.charAt(0).toUpperCase() + caps.slice(1);
                $(this).val(caps);
            });
        },

        getGlobalImgPath: function() {
            return assetsPath + globalImgPath;
        },

        cartItemCount: function() {

            $.post(JsVariables.cart_count, function(data) {

                if (data > 0) {
                    $('.cart-count').text(data).show();
                } else
                    $('.cart-count').hide();
            });
        },
    }

}(jQuery));

jQuery(function() {
    'use strict';
    App.init();
});