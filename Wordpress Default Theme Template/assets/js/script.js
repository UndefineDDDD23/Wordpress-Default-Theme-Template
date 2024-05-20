

jQuery(document).ready(function ($) {
    $(".nav-list__element-link--dropdown").each(function (index, dropdown) {
        $(dropdown).click(function (e) { 
            e.preventDefault();   
            $(".nav-list__element-link--dropdown").each(function (index, element) {
                if(dropdown !== element) {
                    $(element).removeClass("active");
                }
            });     
            $(dropdown).toggleClass("active");
        });    
    });
    $(".nav-list__element-link--dropdown-submenu").each(function (index, dropdown) {
        $(dropdown).click(function (e) { 
            e.preventDefault();            
            $(".nav-list__element-link--dropdown-submenu").each(function (index, element) {
                if(dropdown !== element) {
                    $(element).removeClass("active");
                }
            });
            $(dropdown).toggleClass("active");
        });    
    });

    $(".nav-burger").click(function (e) { 
        e.preventDefault();
        $(this).toggleClass("active");
    });  

    $(".profile-info__profile").click(function (e) { 
        e.preventDefault();
        $(this).toggleClass("active");
    }); 

    $(".form-add-product-to-cart__increase-btn").click(function (e) { 
        e.preventDefault();
        let currentValue = parseInt($(e.target.form[1]).val());
        if(currentValue >= 0 && currentValue <= 20) {
            $(e.target.form[1]).val(currentValue + 1);
        }
    }); 

    $(".form-add-product-to-cart__decrease-btn").click(function (e) { 
        e.preventDefault();
        let currentValue = parseInt($(e.target.form[1]).val());
        if(currentValue >= 1) {
            $(e.target.form[1]).val(currentValue - 1);
        }
    }); 

    $(".filter-sort-by__link").click(function (e) { 
        e.preventDefault();
        $(this).toggleClass("active");
    }); 

    $('.recommended-candles-slider').slick({
		dots: true,
		arrows: false,
		infinite: true,
	});
});