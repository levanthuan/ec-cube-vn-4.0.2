/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

$(function() {
    var doc = document.documentElement;
    var w = window;
    var prevScroll = w.scrollY || doc.scrollTop;
    var curScroll;
    var direction = 0;
    var prevDirection = 0;
    var header = document.getElementById('header');
    var checkScroll = function() {
    curScroll = w.scrollY || doc.scrollTop;
    if (curScroll > prevScroll) { 
      direction = 2;
    }
    else if (curScroll < prevScroll) { 
      direction = 1;
    }
    if (direction !== prevDirection) {
      toggleHeader(direction, curScroll);
    }
    prevScroll = curScroll;
    };
    var toggleHeader = function(direction, curScroll) {
        if (direction === 2 && curScroll > 52) { 
          header.classList.add('hide');
          prevDirection = direction;
        }
        else if (direction === 1) {
          header.classList.remove('hide');
          prevDirection = direction;
        }
    };
    window.addEventListener('scroll', checkScroll);
    $(window).scroll(function() { 
        if ($(window).scrollTop() > 0) {
            header.classList.add('fixed');
        } else {
            header.classList.remove('fixed');
        }
    });

    $('#btn-category_toggle').on("click", function(){
      $(this).next().addClass("open");
    });
    $(document).mouseup(function(e) {
        var container = $('.ec-headerCategories_wrap');
        if (!container.is(e.target) && container.has(e.target).length === 0) 
        {
            container.removeClass("open");
        }
    });
    $( document ).ready(function() {
        $('.ec-headerCategories_wrap .ec-categoryLists > li.parent').prepend('<span class="toggle"><i class="bx bx-chevron-down"></i></span>');
        $('.ec-headerCategories_wrap .ec-categoryLists > li.parent .toggle').on("click", function(){
            $(this).next().next().toggle();
            $(this).toggleClass("up");
        });
    }); 
    
    $(window).scroll(function() {
        var windowHeight = $(window).height(),
            windowTop = $(window).scrollTop();
        $('.ec-animation-image').each(function() {
            if( windowTop >= $( this ).offset().top - windowHeight + 100) {
                $(this).addClass('is-show');
            }
        });
    });
    // Get the modal
    var modal = document.getElementById("searchModal");
    // Get the button that opens the modal
    var btn = document.getElementById("searchBtn");
    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("modalclose")[0];
    // When the user clicks the button, open the modal 
    btn.onclick = function() {
      modal.style.display = "block";
    }
    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
      modal.style.display = "none";
    }
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }


    $('.fcampaign').on('click', 'span', function(){
        $(this).parent().remove();
    });

    //var header_sp = $('.ec-headerRole__navSP').outerHeight();

    $('.pagetop').hide();

    $(window).on('scroll', function() {
        // ページトップフェードイン
        // if ($(this).scrollTop() > 0) {
        //     $('.ec-headerRole__navSP').css( "position", "fixed" );
        // } else {
        //     $('.ec-headerRole__navSP').css( "position", "relative" );
        // }
        if ($(this).scrollTop() > 300) {
            $('.pagetop').fadeIn();
        } else {
            $('.pagetop').fadeOut();
        }

        // PC表示の時のみに適用
        if (window.innerWidth > 767) {

            if ($('#shopping-form').length) {

                var side = $(".ec-orderRole__summary"),
                    wrap = $("#shopping-form"),
                    min_move = wrap.offset().top,
                    max_move = wrap.height(),
                    margin_bottom = max_move - min_move;

                var scrollTop = $(window).scrollTop();
                if (scrollTop > min_move && scrollTop < max_move) {
                    var margin_top = scrollTop - min_move;
                    side.css({"margin-top": margin_top});
                } else if (scrollTop < min_move) {
                    side.css({"margin-top": 0});
                } else if (scrollTop > max_move) {
                    side.css({"margin-top": margin_bottom});
                }

            }
        }
        return false;
    });
	

    $('.ec-headerNavSP').on('click', function() {
        $('.ec-layoutRole').toggleClass('is_active');
        $('.ec-drawerRole').toggleClass('is_active');
        $('.ec-drawerRoleClose').toggleClass('is_active');
        $('body').toggleClass('have_curtain');
    });

    $('.ec-overlayRole').on('click', function() {
        $('body').removeClass('have_curtain');
        $('.ec-layoutRole').removeClass('is_active');
        $('.ec-drawerRole').removeClass('is_active');
        $('.ec-drawerRoleClose').removeClass('is_active');
    });

    $('.ec-drawerRoleClose').on('click', function() {
        $('body').removeClass('have_curtain');
        $('.ec-layoutRole').removeClass('is_active');
        $('.ec-drawerRole').removeClass('is_active');
        $('.ec-drawerRoleClose').removeClass('is_active');
    });

    // TODO: カート展開時のアイコン変更処理
    $('.ec-headerRole__cart').on('click', '.ec-cartNavi', function() {
        // $('.ec-cartNavi').toggleClass('is-active');
        $('.ec-cartNaviIsset').toggleClass('is-active');
        $('.ec-cartNaviNull').toggleClass('is-active')
    });

    $('.ec-headerRole__cart').on('click', '.ec-cartNavi--cancel', function() {
        // $('.ec-cartNavi').toggleClass('is-active');
        $('.ec-cartNaviIsset').toggleClass('is-active');
        $('.ec-cartNaviNull').toggleClass('is-active')
    });

    $('.ec-orderMail__link').on('click', function() {
        $(this).siblings('.ec-orderMail__body').slideToggle();
    });

    $('.ec-orderMail__close').on('click', function() {
        $(this).parent().slideToggle();
    });

    $('.is_inDrawer').each(function() {
        var html = $(this).html();
        $(html).appendTo('.ec-drawerRole');
    });

    $('.ec-blockTopBtn').on('click', function() {
        $('html,body').animate({'scrollTop': 0}, 500);
    });

    $('.ec-blockCartAreaBtn').on('click', function() {
        $('html,body').animate({scrollTop:$('#cart_area').offset().top}, 500);
    });


    // スマホのドロワーメニュー内の下層カテゴリ表示
    // TODO FIXME スマホのカテゴリ表示方法
    $('.child').parents().addClass('parent');
    $('.ec-itemNav ul a').click(function() {
        var child = $(this).siblings();
        if (child.length > 0) {
            if (child.is(':visible')) {
                return true;
            } else {
                $('.ec-categoryNaviRole .ec-itemNav__nav ul').hide();
                child.slideToggle();
                return false;
            }
        }
    });

    // イベント実行時のオーバーレイ処理
    // classに「load-overlay」が記述されていると画面がオーバーレイされる
    $('.load-overlay').on({
        click: function() {
            loadingOverlay();
        },
        change: function() {
            loadingOverlay();
        }
    });

    // submit処理についてはオーバーレイ処理を行う
    $(document).on('click', 'input[type="submit"], button[type="submit"]', function() {

        // html5 validate対応
        var valid = true;
        var form = getAncestorOfTagType(this, 'FORM');

        if (typeof form !== 'undefined' && !form.hasAttribute('novalidate')) {
            // form validation
            if (typeof form.checkValidity === 'function') {
                valid = form.checkValidity();
            }
        }

        if (valid) {
            loadingOverlay();
        }
    });
});

$(window).on('pageshow', function() {
    loadingOverlay('hide');
});

/**
 * オーバーレイ処理を行う関数
 */
function loadingOverlay(action) {

    if (action == 'hide') {
        $('.bg-load-overlay').remove();
    } else {
        $overlay = $('<div class="bg-load-overlay">');
        $('body').append($overlay);
    }
}

/**
 *  要素FORMチェック
 */
function getAncestorOfTagType(elem, type) {

    while (elem.parentNode && elem.tagName !== type) {
        elem = elem.parentNode;
    }

    return (type === elem.tagName) ? elem : undefined;
}

// anchorをクリックした時にformを裏で作って指定のメソッドでリクエストを飛ばす
// Twigには以下のように埋め込む
// <a href="PATH" {{ csrf_token_for_anchor() }} data-method="(put/delete/postのうちいずれか)" data-confirm="xxxx" data-message="xxxx">
//
// オプション要素
// data-confirm : falseを定義すると確認ダイアログを出さない。デフォルトはダイアログを出す
// data-message : 確認ダイアログを出す際のメッセージをデフォルトから変更する
//
$(function() {
    var createForm = function(action, data) {
        var $form = $('<form action="' + action + '" method="post"></form>');
        for (input in data) {
            if (data.hasOwnProperty(input)) {
                $form.append('<input name="' + input + '" value="' + data[input] + '">');
            }
        }
        return $form;
    };

    $('a[token-for-anchor]').click(function(e) {
        e.preventDefault();
        var $this = $(this);
        var data = $this.data();
        if (data.confirm != false) {
            if (!confirm(data.message ? data.message : eccube_lang['common.delete_confirm'] )) {
                return false;
            }
        }

        // 削除時はオーバーレイ処理を入れる
        loadingOverlay();

        var $form = createForm($this.attr('href'), {
            _token: $this.attr('token-for-anchor'),
            _method: data.method
        }).hide();

        $('body').append($form); // Firefox requires form to be on the page to allow submission
        $form.submit();
    });
});
