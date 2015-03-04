// =============================================================================
// JS/SRC/SITE/INC/SHORTCODES/X-SKILL-BAR.JS
// -----------------------------------------------------------------------------
// X data attribute API shortcode information.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Skill Bar
// =============================================================================

// Skill Bar
// =============================================================================

xData.api.map('skill_bar', function(element, params) {

  $(element).waypoint(function() {

    $(this).find('.bar').animate({ 'width' : params.percent }, 750, 'easeInOutExpo');

  }, { offset : '95%', triggerOnce : true });

});