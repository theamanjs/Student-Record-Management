$(document).ready(function() {
      $().ready(function() {
        $sidebar = $('.sidebar');

        $sidebar_img_container = $sidebar.find('.sidebar-background');

        $full_page = $('.full-page');

        $sidebar_responsive = $('body > .navbar-collapse');

        window_width = $(window).width();

        fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();

        if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
          if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
            $('.fixed-plugin .dropdown').addClass('open');
          }

        }


        $('.switch-sidebar-mini input').change(function() {
          $body = $('body');

          $input = $(this);

          if (md.misc.sidebar_mini_active == true) {
            $('body').removeClass('sidebar-mini');
            md.misc.sidebar_mini_active = false;

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();

          } else {

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');

            setTimeout(function() {
              $('body').addClass('sidebar-mini');

              md.misc.sidebar_mini_active = true;
            }, 300);
          }

          // we simulate the window Resize so the charts will get updated in realtime.
          var simulateWindowResize = setInterval(function() {
            window.dispatchEvent(new Event('resize'));
          }, 180);

          // we stop the simulation of Window Resize after the animations are completed
          setTimeout(function() {
            clearInterval(simulateWindowResize);
          }, 1000);

        });
      });
    });
// Code manipulating theme session
// let colorDark = localStorage.getItem("themeColorDark");
// let colorLight = localStorage.getItem("themeColorLight");
// if (typeof colorDark != "undefined" && colorDark != null && colorLight != "undefined" && colorLight != null){
//   document.body.style.setProperty('--themeColorDark', colorDark);
//   document.body.style.setProperty('--themeColorLight', colorLight);
//   document.body.style.setProperty('--themeColorShadow', colorDark+'66');
// }
//function for changing theme defined by divyanshu
function changeTheme(index){
  var color = [
      // ['DarkColor','LightColor']
      // ['#8e24aa','#ab47bc'],
      // ['#1B1464','#ab47bc'],
      // ['#00acc1','#26c6da'],
      // ['#43a047','#66bb6a'],
      // ['#fb8c00','#ffa726'],
      // ['#e53935','#ef5350'],
      // ['#665e8a','#786fa6']
      ['#8e24aa','#ab47bc'],
      ['#00acc1','#26c6da'],
      ['#43a047','#66bb6a'],
      ['#fb8c00','#ffa726'],
      ['#e53935','#ef5350'],
      ['#d81b60','#ec407a'],
      ['#574b90','#7066a0']
    ];
    document.body.style.setProperty('--themeColorDark',color[index][0]);
    document.body.style.setProperty('--themeColorLight',color[index][1]);
    document.body.style.setProperty('--themeColorShadow',color[index][0]+'66');
    localStorage.setItem("themeColorDark", color[index][0]);
    localStorage.setItem("themeColorLight", color[index][1]);
    //localStorage.clear();
}