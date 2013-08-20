// Don't break on browsers without console.log();
if (typeof(console) === 'undefined') { console = { log: function() {}, assert: function() {} }; }
jQuery(function($) {

  $('.filter-buttons button').click(function() {
    $('.filter-buttons button').attr('disabled', 'disabled');
    $elem = $(this);
    var filter = $(this).attr('data-preset');
    Caman("#image-canvas", function (elem) {
      $title = $elem.html();
      $elem.html('Render..').toggleClass('btn-warning');
      this.revert();
      this[filter]();
      this.render(function() {
        $elem.html($title);
        $elem.toggleClass('btn-warning');
        $('.filter-buttons button').removeAttr('disabled');
      });
    });
  });


  $('#save-button').click(function() {
    Caman("#image-canvas", function () {
      this.save();
    });
  });
});
