Gallery = function() {
    var e = $(".section_gallery__grid");
    e.length && e.each(function() {
      var e = $(this).isotope({
        itemSelector: ".section_gallery__grid__item"
      });
      e.imagesLoaded().progress(function() {
        e.isotope("layout")
      })
    })
  }()