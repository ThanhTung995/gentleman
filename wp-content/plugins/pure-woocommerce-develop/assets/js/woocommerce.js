'use strict';

(function ($) {

    function debounce(func, wait, immediate) {
        var timeout;
        return function () {
            var context = this, args = arguments;
            var later = function () {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    };

    function clickOutsideDivHandler(selector, callback) {
        var args = Array.prototype.slice.call(arguments); // Save/convert arguments to array since we won't be able to access these within .on()
        $(document).on("mouseup.clickOFF touchend.clickOFF", function (e) {
            var container = $(selector);

            if (!container.is(e.target) // if the target of the click isn't the container...
                && container.has(e.target).length === 0) // ... nor a descendant of the container
            {
                $(document).off("mouseup.clickOFF touchend.clickOFF");
                if (callback) callback.apply(this, args);
            }
        });
    }

    var s, d;
    var PureWoocommerce = {
        settings: {
            document: $(document),
            body: $(document.body),
            window: $(window),
            browserWidth: 0,
            browserHeight: 0,
            quantityInc: $('.quantity .inc'),
            quantityDec: $('.quantity .dec'),
            tabWrapper: $('.woocommerce-tabs'),
            accordionTitle: $('.woocommerce-Tabs-panel__title'),
            addToCartForm: $('.summary form.cart'),
            addToCartFormHeight: 0,
            productOffsetTop: 0,
            footerOffsetTop: 0,
            galleryHeight: 0,
            productSummary: $('.summary.entry-summary'),
            singleAddToCartButton: $('.single_add_to_cart_button'),
            singleAddToCartButtonOffsetTop: 0,
            variableAddToCartButton: $('.woocommerce-variation-add-to-cart .single_add_to_cart_button'),
            singleAddToCartButtonIcon: null,
            miniCart: $('.pure-woocommerce-mini-cart'),
            miniCartIcon: $('.pure-woocommerce-mini-cart-icon'),
            miniCartIconClose: $('.js-close-mini-cart'),
            shopSideBarIcon: $('.js-open-shop-sidebar'),
            shopListIcon: $('.js-shop-list'),
            shopGridIcon: $('.js-shop-grid'),
            productsList: $('ul.products'),
            addToWishList: $('.add_to_wishlist'),
            quickViewButton: $('.js-quick-view'),
            quickViewWrapper: $('.pure-quick-view'),
            sizeGuideImage: $('.size-guide-image'),
            deliveryReturn: $('.js-delivery-return'),
            catalogMenu: $('.danh-muc-san-pham'),
        },
        init: function () {
            d = this;
            s = this.settings;

            this.initAnimation();

            // Init function when DOM is full loaded.
            document.addEventListener('DOMContentLoaded', this.ready);

            window.addEventListener('resize', debounce(function () {
                d.updateBrowserDimension();
                // d.updateOffset();
                d.adjustAddToWishListHeight();
            }, 250));

            this.updateBrowserDimension();
            this.addIconToAddToCartButton();
            this.addButtonClassToLoopWishListIcon();
            this.prepareButtonTitleAttributes();

            // Event listener
            s.quantityInc.on('click', this.increaseQuantity);
            s.quantityDec.on('click', this.decreaseQuantity);

            s.accordionTitle.on('click', this.tabAccordion);

            s.singleAddToCartButton.on('click', this.singleAjaxAddToCart);
            s.variableAddToCartButton.on('click', this.scrollToVariationOption);

            s.miniCartIcon.on('click', this.showMiniCart);
            s.miniCartIconClose.on('click', this.hideMiniCart);

            s.shopListIcon.on('click', this.activeShopList);
            s.shopGridIcon.on('click', this.activeShopGrid);

            s.document.on('added_to_cart', this.updateMiniCart);

            $('.product-video').magnificPopup({
                disableOn: 700,
                type: 'iframe',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                preloader: false,
                fixedContentPos: false
            });

            s.addToWishList.on('click', this.addToWishListHandler);

            s.quickViewButton.on('click', this.quickViewHandler);

            s.document.on('append.infiniteScroll', function () {
                d.addButtonClassToLoopWishListIcon();

                s.quickViewButton = $('.js-quick-view');
                s.quickViewButton.on('click', d.quickViewHandler);
            });

            this.stickySummary();

            s.sizeGuideImage.on('click', this.popUpSizeGuide);

            s.deliveryReturn.magnificPopup({
                type: 'inline',
                midClick: true
            });

            this.productTimer();

            this.adjustAddToWishListPosition();

            this.salesNotification();

            // s.document.on('woof_ajax_done', this.initAnimation);
            // s.document.on('woof_ajax_done', this.addButtonClassToLoopWishListIcon);

            if (s.browserWidth < 993) {
                this.initShopSidebar();
            }

            s.document.on('price_slider_change', function () {
                $('.widget_price_filter').find('form').submit();
            });

            this.addTextToAddToCartButton();

            this.cloneProductTitle();
            this.singleProductScrollHeader();
            this.productImageSlider();
            this.relatedProductsSlider();
        },
        ready: function () {
            d.adjustAddToWishListHeight();
            d.updateMiniCartProductsListHeight();
            // d.showMobileAddToCart();
            // s.window.on('scroll', debounce(d.showMobileAddToCart, 100));
            $('.stars a[class^="star-"]').append('<i class="fa fa-star"></i>');
            if (s.catalogMenu.length > 0) {
                d.updateSubMenuHeight();
                d.activeProductMenu();
            }
            // d.initScrollbar();
            // d.updateOffset();
            s.window.on('scroll', d.activeSingleProductScrollHeader);
            if (s.singleAddToCartButton.length > 0) {
                var atcButtonOffset = s.singleAddToCartButton.offset();
                s.singleAddToCartButtonOffsetTop = atcButtonOffset.top;
            }
        },
        updateBrowserDimension: function () {
            s.browserWidth = s.window.width();
            s.browserHeight = s.window.height();
        },
        increaseQuantity: function () {
            var target = $(this).parent().find('input'),
                currentValue = target.val();
            target.val(Number(currentValue) + 1);
            target.change();
        },
        decreaseQuantity: function () {
            var target = $(this).parent().find('input'),
                currentValue = target.val();
            if (Number(currentValue) < 2) {
                target.val(1);
            } else {
                target.val(Number(currentValue) - 1);
            }
            target.change();
        },
        tabAccordion: function () {
            if (s.browserWidth > 767 && !s.tabWrapper.hasClass('accordion')) return;
            var target = $(this).parent();
            if (target.hasClass('active')) {
                target.find('.woocommerce-Tabs-panel__content').slideUp();
                target.removeClass('active');
            } else {
                // s.tabWrapper.find('.active .woocommerce-Tabs-panel__content').slideUp();
                // s.tabWrapper.find('.active').removeClass('active');

                target.find('.woocommerce-Tabs-panel__content').slideDown();
                target.addClass('active');
            }
        },
        updateOffset: function () {
            var footerOffset = $(document.getElementById('colophon')).offset(),
                productOffset = $('div.product.pure-woocommerce-single').offset();
            if (footerOffset) {
                s.footerOffsetTop = footerOffset.top;
            }
            if (productOffset) {
                s.productOffsetTop = productOffset.top;
            }
            s.galleryHeight = $('.pure-woocommerce-single__gallery-wrapper').height();
            s.addToCartFormHeight = s.addToCartForm.height();
        },
        showMobileAddToCart: function () {
            if (s.browserWidth > 479) return;
            var scrollTop = s.window.scrollTop();
            if ((s.productOffsetTop - s.browserHeight + s.galleryHeight + s.addToCartFormHeight + 20) < scrollTop && scrollTop < (s.footerOffsetTop - s.browserHeight * 4 / 5)) {
                s.addToCartForm.fadeIn();
            } else {
                s.addToCartForm.fadeOut()
            }
        },
        singleAjaxAddToCart: function (e) {
            var $this = $(this);
            if (
                s.body.hasClass('single-product-no-ajax')
                || $this.hasClass('no-ajax')
                || $this.hasClass('disabled')
                || $this.attr('disabled')
            ) return;

            var singleAddToCartForm = $this.closest('form.cart');

            $.ajax({
                type: 'POST',
                url: PUREWOOCOMMERCE.siteURL,
                dataType: 'html',
                data: singleAddToCartForm.serialize(),
                cache: false,
                headers: {'cache-control': 'no-cache'},
                beforeSend: function () {
                    s.singleAddToCartButtonIcon.html('<i class="far fa-circle-notch fa-spin"></i>');
                },
                success: function () {
                    d.updateMiniCart();
                    s.singleAddToCartButtonIcon.html('<i class="far fa-check"></i>');
                }
            });
            return false;
        },
        updateMiniCart: function () {
            d.showMiniCart();
            $('.pure-woocommerce-cart-wrapper').html('<i class="ion-spin ion-load-a"></i>');
            $.ajax({
                type: 'POST',
                url: PUREWOOCOMMERCE.ajaxURL,
                dataType: 'json',
                data: {
                    action: 'pure_woocommerce_update_mini_cart'
                },
                success: function (res) {
                    if (res.data.message != null && $(res.data.message.error).length > 0) {
                        $('body').append('<div class="woocommerce-error">' + res.data.message.error + '</div>');
                        $('.woocommerce-message').remove();
                    } else {
                        $('.cart-counter').text(res.data.cart_total);
                        $('.pure-woocommerce-cart-wrapper').html(res.data.cart_html);
                    }
                }
            });
        },
        updateMiniCartProductsListHeight: function () {
            if (s.miniCart.hasClass('bottom')) return;
            var productsListHeight = s.browserHeight - s.miniCart.find('.mini-cart-header').outerHeight() - s.miniCart.find('p.total').outerHeight() - s.miniCart.find('p.buttons').outerHeight();
            s.miniCart.find('.product_list_widget').css('max-height', productsListHeight + 'px');
            if (s.browserWidth > 779 && s.body.hasClass('admin-bar')) {
                s.miniCart.find('.product_list_widget').css('max-height', (productsListHeight - 32) + 'px');
            }
        },
        showMiniCart: function () {
            s.body.addClass('mini-cart-active');
            clickOutsideDivHandler('.pure-woocommerce-mini-cart', debounce(d.hideMiniCart, 100));
        },
        hideMiniCart: function () {
            $(document).off("mouseup.clickOFF touchend.clickOFF");
            s.body.removeClass('mini-cart-active');
        },
        scrollToVariationOption: function () {
            if (s.browserWidth > 479) return;
            if (
                !s.singleAddToCartButton.hasClass('disabled')
                && !s.singleAddToCartButton.attr('disabled')
            ) return;
            var summaryOffset = s.productSummary.offset();
            s.body.scrollTop(summaryOffset.top);
        },
        addIconToAddToCartButton: function () {
            s.singleAddToCartButton.removeClass('alt');
            s.singleAddToCartButton.prepend('<span class="pure-add-to-cart-icon"><i class="fa fa-shopping-basket"></i></span>');
            s.singleAddToCartButtonIcon = s.singleAddToCartButton.find('.pure-add-to-cart-icon');
        },
        initShopSidebar: function () {
            if ($('#shop-sidebar').length == 0) {
                return;
            }
            $('#shop-sidebar').appendTo('body');
            var shopSidebar = $('#shop-sidebar');
            shopSidebar.append('<span class="js-close-shop-sidebar"><i class="fal fa-times"></i></span>');
            var shopSidebarSlideout = new Slideout({
                'panel': document.getElementById('page'),
                'menu': document.getElementById('shop-sidebar'),
                'padding': 256,
                'tolerance': 70,
                'touch': false
            });
            s.shopSideBarIcon.on('click', function () {
                shopSidebarSlideout.toggle();
                shopSidebar.css('z-index', '0');
            });

            shopSidebarSlideout.on('close', function () {
                shopSidebar.css('z-index', '-1');
            });

            $('.js-close-shop-sidebar').on('click', function () {
                shopSidebarSlideout.toggle();
            });
        },
        activeShopList: function () {
            s.productsList.removeClass('fitrows');
            s.productsList.addClass('list');
            // d.reInitIsotope();
        },
        activeShopGrid: function () {
            s.productsList.removeClass('list');
            s.productsList.addClass('fitrows');
            // d.reInitIsotope();
        },
        reInitIsotope: function () {
            s.productsList.isotope();
        },
        initAnimation: function () {
            $('*[data-animation-enable="1"]').each(function () {
                var $this = $(this);
                var delay = $this.data('animation-delay');
                var animation = $this.data('animation');
                $this.one('inview', function (event, isInView, visiblePartX, visiblePartY) {
                    setTimeout(function () {
                        $this.addClass(animation + ' animated ');
                    }, delay);
                }); // inview
            });
        },
        addPhotoSwipeMarkup: function () {
            if ($('.pswp').length) {
                return;
            }
            s.body.append('<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true"> <div class="pswp__bg"></div> <div class="pswp__scroll-wrap"> <div class="pswp__container"> <div class="pswp__item"></div> <div class="pswp__item"></div> <div class="pswp__item"></div> </div> <div class="pswp__ui pswp__ui--hidden"> <div class="pswp__top-bar"> <!-- Controls are d-explanatory. Order can be changed. --> <div class="pswp__counter"></div> <button class="pswp__button pswp__button--close" title="Close (Esc)"></button> <button class="pswp__button pswp__button--share" title="Share"></button> <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button> <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button> <div class="pswp__preloader"> <div class="pswp__preloader__icn"> <div class="pswp__preloader__cut"> <div class="pswp__preloader__donut"></div> </div> </div> </div> </div> <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap"> <div class="pswp__share-tooltip"></div> </div> <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"> </button> <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"> </button> <div class="pswp__caption"> <div class="pswp__caption__center"></div> </div> </div> </div> </div>');
        },
        adjustAddToWishListHeight: function () {
            if (s.body.hasClass('single-large-add-to-cart')) {
                return;
            }
            if (s.addToCartForm.hasClass('variations_form')) return;
            if (s.browserWidth < 768 && s.browserWidth > 567 || s.browserWidth > 991) {
                s.addToCartForm.parent().find('.yith-wcwl-add-to-wishlist').height(s.addToCartFormHeight);
            } else {
                s.addToCartForm.parent().find('.yith-wcwl-add-to-wishlist').css('height', 'auto');
            }
        },
        addToWishListHandler: function () {
            var $this = $(this);
            $this.addClass('adding-to-wishlist');
            if ($this.parents('li.product').length > 0) {
                $this.parents('li.product').addClass('added-to-wishlist');
            }
        },
        addButtonClassToLoopWishListIcon: function () {
            $('ul.products .yith-wcwl-add-to-wishlist a').addClass('button');
        },
        prepareButtonTitleAttributes: function () {
            $('li.product .actions a').each(function () {
                var $this = $(this);
                $(this).attr('data-title', $this.text().trim());
            })
        },
        quickViewHandler: function () {
            var $this = $(this),
                $id = $this.data('id');

            if (!$id) return;

            s.body.addClass('quick-view-active');

            $.ajax({
                type: 'POST',
                url: PUREWOOCOMMERCE.ajaxURL,
                data: {action: 'pure_woocommerce_quick_view', product_id: $id},
                success: function (res) {
                    s.quickViewWrapper.html(res);
                    s.quickViewWrapper.addClass('active');

                    var $gallery = $('.quick-view .woocommerce-product-gallery__wrapper');
                    var flickityData = $gallery.attr('data-flickity');
                    $gallery.flickity(JSON.parse(flickityData));
                    $gallery.flickity('reloadCells');

                    var $gallery = $('.quick-view .woocommerce-product-thumbnails__wrapper');
                    var flickityData = $gallery.attr('data-flickity');
                    $gallery.flickity(JSON.parse(flickityData));

                    $('.product-video').magnificPopup({
                        disableOn: 700,
                        type: 'iframe',
                        mainClass: 'mfp-fade',
                        removalDelay: 160,
                        preloader: false,
                        fixedContentPos: false
                    });

                    // s.quickViewWrapper.find('.quick-view').css('height', s.quickViewWrapper.find('.insider').height() + 'px');
                    s.quickViewWrapper.find('.single_add_to_cart_button').removeClass('alt');

                    $('.single_add_to_cart_button').on('click', d.singleAjaxAddToCart);
                    $('.quantity .inc').on('click', d.increaseQuantity);
                    $('.quantity .dec').on('click', d.decreaseQuantity);
                    $('.js-close-quick-view').on('click', d.closeQuickView);
                    $('.quick-view-overlay').on('click', d.closeQuickView);
                    clickOutsideDivHandler('.quick-view', d.closeQuickView);
                }
            });
        },
        closeQuickView: function (e) {
            if (typeof e === 'object') {
                e.preventDefault();
            }
            s.body.removeClass('quick-view-active');
            s.quickViewWrapper.removeClass('active');
            s.quickViewWrapper.html('');
        },
        stickySummary: function () {
            $('.sticky-summary .summary > .insider').stick_in_parent({
                parent: $('.pure-woocommerce-single__upper'),
                spacer: $('.pure-woocommerce-single__upper .summary')
            });
        },
        popUpSizeGuide: function (e) {
            e.preventDefault();
            d.addPhotoSwipeMarkup();
            var $pswpElement = document.querySelectorAll('.pswp')[0],
                $src = $(this).data('image'),
                $height = $(this).data('height'),
                $width = $(this).data('width'),
                $items = [{
                    src: $src,
                    h: $height,
                    w: $width
                }],
                $sizeGuidePopUp = new PhotoSwipe($pswpElement, PhotoSwipeUI_Default, $items);
            $sizeGuidePopUp.init();
        },
        productTimer: function () {
            var $timer = $('#countdown-timer');
            if ($timer.length === 0) return;
            var $time = $timer.data('time');
            if (!$time) return;
            var $now = new Date().getTime(),
                $countDownDate = $time + $now,
                x = setInterval(function () {

                    // Get todays date and time
                    var now = new Date().getTime();

                    // Find the distance between now an the count down date
                    var distance = $countDownDate - now;

                    // Time calculations for days, hours, minutes and seconds
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    // Display the result in the element with id="demo"
                    document.getElementById("countdown-timer").innerHTML = "<div>" + days + "<span>d<span>ays</span></span></div><div>" + hours + "<span>h<span>ours</span></span></div><div>" + minutes + "<span>m<span>inutes</span></span></div><div>" + seconds + "<span>s<span>econds</span></span></div>";

                    // If the count down is finished, write some text
                    if (distance < 0) {
                        clearInterval(x);
                        document.getElementById("countdown-timer").innerHTML = "<div>0<span>d<span>ays</span></span></div><div>0<span>h<span>ours</span></span></div><div>0<span>m<span>inutes</span></span></div><div>0<span>s<span>econds</span></span></div>";
                    }
                }, 1000);
        },
        adjustAddToWishListPosition: function () {
            if (!s.body.hasClass('single-large-add-to-cart')) {
                return;
            }
            s.productSummary.find('.yith-wcwl-add-to-wishlist').prependTo('.pure-summary-actions');
        },
        salesNotification: function () {
            var $products = $('.sales-notification-products');
            if ($products.length === 0) return;
            var $ids_array = [],
                $interval = $products.data('interval'),
                counter = 1,
                $between = 0;

            $products.find('.item').each(function () {
                var id = $(this).attr('id');
                $ids_array.push(id);
            });

            setTimeout(function () {
                d.popupSalesNotification($ids_array[0]);
            }, (Math.floor(Math.random() * 15) + 1) * 1000);

            if ($interval !== 'random') {
                $between = parseInt($interval) * 30000;
            } else {
                $between = (Math.floor(Math.random() * 4) + 1) * 30000;
            }
            setInterval(function () {
                d.popupSalesNotification($ids_array[counter]);
                counter = (counter + 1 < $ids_array.length) ? counter + 1 : 0;
            }, $between);
        },
        popupSalesNotification: function (id) {
            var target = $('#' + id);
            target.removeClass('hidden fadeOutDown').addClass('fadeInUp');
            setTimeout(function () {
                target.removeClass('fadeInUp').addClass('fadeOutDown');
            }, 8000);
        },
        initScrollbar: function () {
            if (s.miniCart.hasClass('bottom')) {

                $('.pure-woocommerce-mini-cart .cart_list').mCustomScrollbar({
                    theme: "minimal",
                    axis: "x",
                    advanced: {autoExpandHorizontalScroll: true}
                });
            } else {
                $('.pure-woocommerce-mini-cart .cart_list').mCustomScrollbar({
                    theme: "minimal",
                    advanced: {autoExpandHorizontalScroll: true}
                });
            }
        },
        updateSubMenuHeight: function () {
            s.catalogMenu.each(function () {

                var subMenu = s.catalogMenu.find('> ul > li > ul');
                var topSubMenu = s.catalogMenu.find('> ul');
                var previousCss = topSubMenu.attr("style");
                var height = topSubMenu.outerHeight();
                topSubMenu.attr("style", previousCss ? previousCss : "");
                subMenu.outerHeight(height);
            });
        },
        activeProductMenu: function () {
            if (s.browserWidth > 1119 && s.body.hasClass('home')) {
                s.catalogMenu.addClass('active');
            } else {
                s.catalogMenu.removeClass('active');
            }
        },
        addTextToAddToCartButton: function () {
            if (PUREWOOCOMMERCE.addToCartAdditionalText) {
                s.singleAddToCartButton.append('<span class="additional-text">' + PUREWOOCOMMERCE.addToCartAdditionalText + '</span>');
            }
        },
        cloneProductTitle: function () {
            if (!s.body.hasClass('single-product')) return;
            $('.pure-woocommerce-single').prepend('<div class="product_title--clone color-heading-color">' + $('.product_title').text() + '</div>');
        },
        singleProductScrollHeader: function () {
            if (s.browserWidth < 992) return;
            if (!s.body.hasClass('single-product')) return;
            if (s.singleAddToCartButton.length == 0) return;
            $('.pure-woocommerce-single').prepend('<div class="product-scroll-header bg-primary-bg"><div class="wrap"></div></div>');
            var $target = $('.product-scroll-header .wrap');
            var imageURL = $('meta[property="og:image"]').attr('content');
            $target.append('<div class="image"><img src="' + imageURL + '" /></div>');
            $target.append('<h1 class="title">' + $('.product_title').text() + '</h1>');
            s.productSummary.find('.price').clone().appendTo($target);
            s.singleAddToCartButton.clone().appendTo($target).addClass('clone');
        },
        activeSingleProductScrollHeader: function () {
            if (s.browserWidth < 992) return;
            if (!s.body.hasClass('single-product')) return;
            if (s.singleAddToCartButton.length == 0) return;

            var scrollTop = s.window.scrollTop();

            if (s.singleAddToCartButtonOffsetTop + 100 < scrollTop) {
                s.body.addClass('product-scroll-header-active');
            } else {
                s.body.removeClass('product-scroll-header-active');
            }

            $('.single_add_to_cart_button.clone').unbind().click(function () {

                console.log('hello');
                s.addToCartForm.find('.single_add_to_cart_button').click();
            });
        },
        relatedProductsSlider: function () {
            var $related = $('.related .products');
            $related.on('ready.flickity', function () {
                $related.addClass('flick-initialized');
            });
            $related.flickity({
                cellAlign: 'left',
                contain: true,
                draggable: true,
                freeScroll: true,
                imagesLoaded: true,
                pageDots: false,
            });
            setTimeout(function () {
                $related.flickity('reloadCells');
            }, 5000);
        },
        productImageSlider: function () {
            var $carousel = $('.woocommerce-product-gallery__wrapper').flickity({
                draggable: true,
                pageDots: false,
                wrapAround: true,
                lazyLoad: true,
                imagesLoaded: true,
            });
            if ($('.pure-woocommerce-single__gallery-wrapper').hasClass('bottom') || s.browserWidth < 992) {
                $('.woocommerce-product-thumbnails__wrapper').flickity({
                    draggable: true,
                    pageDots: false,
                    wrapAround: true,
                    lazyLoad: true,
                    prevNextButtons: false,
                    imagesLoaded: true,
                    asNavFor: '.woocommerce-product-gallery__wrapper',
                });
            } else {
                var $carouselNav = $('.woocommerce-product-thumbnails__wrapper');
                var $carouselNavCells = $carouselNav.find('.woocommerce-product-thumbnails__image');

                $carouselNav.on('click', '.woocommerce-product-thumbnails__image', function (event) {
                    var index = $(event.currentTarget).index();
                    $carousel.flickity('select', index);
                });

                var flkty = $carousel.data('flickity');
                var navTop = $carouselNav.position().top;
                var navCellHeight = $carouselNavCells.height();
                var navHeight = $carouselNav.height();

                $carousel.on('select.flickity', function () {
                    // set selected nav cell
                    $carouselNav.find('.is-nav-selected').removeClass('is-nav-selected');
                    var $selected = $carouselNavCells.eq(flkty.selectedIndex)
                        .addClass('is-nav-selected');
                    // scroll nav
                    var scrollY = $selected.position().top +
                        $carouselNav.scrollTop() - (navHeight + navCellHeight) / 2;
                    $carouselNav.animate({
                        scrollTop: scrollY
                    });
                });
            }
        },
    };

    PureWoocommerce.init();

}(jQuery));
