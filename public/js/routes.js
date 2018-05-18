var timestamp = Date.now();

routes = [
  {
    path: '/',
    url: './home',
  },
  {
    path: '/leftTicket/',
    url: '/train/leftTicket',
  },
  {
    path: '/detail/:train_no/:from_station_no/:to_station_no/:seat_types/:train_date/:from_station_code/:to_station_code/:avail_seats/:start_time',
    url: '/train/detail?train_no={{train_no}}&from_station_no={{from_station_no}}&to_station_no={{to_station_no}}&seat_types={{seat_types}}&train_date={{train_date}}&from_station_code={{from_station_code}}&to_station_code={{to_station_code}}&avail_seats={{avail_seats}}&start_time={{start_time}}',
    async: function (routeTo, routeFrom, resolve, reject) {
      // Router instance
      var router = this;
      // App instance
      var app = router.app;
      // Show Preloader
      app.preloader.show();
    },
  },
  {
    path: '/pay/:id',
    url: '/order/pay/{{id}}',
  },
  {
    path: '/pay/confirm/:id',
    url: '/order/pay/confirm/{{id}}',
  },
  {
    path: '/orders',
    url: '/order',
  },
  {
    path: '/login-screen/',
    url: './pages/login-screen.html'
  },
  {
    path: '/form/',
    url: './pages/form.html',
  },
  {
      path: '/account/',
      url: '/user/account',
  },
  {
    path: '/logout',
    async: function (routeTo, routeFrom, resolve, reject) {
      window.location.href = "/auth/logout";
    }
  },
  {
    path: '/jaccount/',
    async: function (routeTo, routeFrom, resolve, reject) {
      window.location.href = "/auth/jaccount";
    }
  },
  // Page Loaders & Router
  {
    path: '/page-loader-template7/:user/:userId/:posts/:postId/',
    templateUrl: './pages/page-loader-template7.html',
  },
  {
    path: '/page-loader-component/:user/:userId/:posts/:postId/',
    componentUrl: './pages/page-loader-component.html',
  },
  {
    path: '/request-and-load/user/:userId/',
    async: function (routeTo, routeFrom, resolve, reject) {
      // Router instance
      var router = this;

      // App instance
      var app = router.app;

      // Show Preloader
      app.preloader.show();

      // User ID from request
      var userId = routeTo.params.userId;

      // Simulate Ajax Request
      setTimeout(function () {
        // We got user data from request
        var user = {
          firstName: 'Vladimir',
          lastName: 'Kharlampidi',
          about: 'Hello, i am creator of Framework7! Hope you like it!',
          links: [
            {
              title: 'Framework7 Website',
              url: 'http://framework7.io',
            },
            {
              title: 'Framework7 Forum',
              url: 'http://forum.framework7.io',
            },
          ]
        };
        // Hide Preloader
        app.preloader.hide();

        // Resolve route to load page
        resolve(
          {
            componentUrl: './pages/request-and-load.html',
          },
          {
            context: {
              user: user,
            }
          }
        );
      }, 1000);
    },
  },
  // Default route (404 page). MUST BE THE LAST
  {
    path: '(.*)',
    url: './pages/404.html',
  },
];
