$(document).ready(function() {
    slickInit();

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        $(".slider").slick("unslick");
        slickInit();
    });
});

function slickInit() {
    $('.slider').slick({
        draggable: false,
        infinite: false,
        slidesPerRow: 3,
        slidesToShow: 3
    });

}
