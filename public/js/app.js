// Dom7
var $$ = Dom7;

// Framework7 App main instance
var app  = new Framework7({
  root: '#app', // App root element
  id: 'io.framework7.testapp', // App bundle ID
  name: 'Framework7', // App name
  theme: 'auto', // Automatic theme detection
  // App root data
  data: function () {
    return {
      user: {
        firstName: 'John',
        lastName: 'Doe',
      },
    };
  },
  // App root methods
  methods: {
    helloWorld: function () {
      app.dialog.alert('Hello World!');
    },
  },
  // App routes
  routes: routes,
  // Enable panel left visibility breakpoint
  panel: {
    leftBreakpoint: 960,
  },
  cache: false,
});

// Init/Create left panel view
var mainView = app.views.create('.view-left', {
  url: '/'
});

// Init/Create main view
var mainView = app.views.create('.view-main', {
  url: '/'
});

// Login Screen Demo
$$('#my-login-screen .login-button').on('click', function () {
  var username = $$('#my-login-screen [name="username"]').val();
  var password = $$('#my-login-screen [name="password"]').val();

  // Close login screen
  app.loginScreen.close('#my-login-screen');

  // Alert username and password
  app.dialog.alert('Username: ' + username + '<br>Password: ' + password);
});

app.request({
    url: '/api/user/isLogin',
    method: 'GET',
    dataType: 'json',
    success: function (data) {
        if (data.is_login === false) {
            var login_screen = app.loginScreen.create({
                el: $$("#my-login-screen"),
            });
            login_screen.open();
        }
    }
});

$$('#jaccount_login').on('click', function () {
    console.log('click')
    window.location.href = "/auth/jaccount";
});

app.on('pageInit', function (page) {
    if (page.name === 'home') {

    } else if (page.name === 'all_orders') {
        app.preloader.hide();
        $$('#refresh_orders').on('click', function () {
            app.preloader.show();
            mainView.router.refreshPage()
        });
    } else if (page.name === 'order_confirmed') {
        if ($$('#pay_success').html() === 'false') {
            var toastIcon = app.toast.create({
                icon: '<i class="material-icons">close</i>',
                text: $$('#error_msg').html(),
                position: 'center',
                closeTimeout: 2000,
            });
        } else {
            var toastIcon = app.toast.create({
                icon: '<i class="material-icons">check</i>',
                text: '支付成功',
                position: 'center',
                closeTimeout: 2000,
            });
        }
        toastIcon.open();
    } else if (page.name === 'order_pay') {
        $$('#confirm_pay').on('click', function () {
            app.dialog.confirm($$("#pay_dialog").html(), "支付订单", function () {
                mainView.router.navigate('/pay/confirm/' + $$("#order_id").html(), {
                    reloadCurrent: true,
                });
            });
        });

        $$('#cancel_order').on('click', function () {
            app.dialog.confirm($$("#cancel_dialog").html(), "取消订单", function () {
                app.request({
                    url: '/order/cancel/' + $$("#order_id").html(),
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        if (data.success === true) {
                            app.toast.create({
                                icon: '<i class="material-icons">check</i>',
                                text: '取消成功',
                                position: 'center',
                                closeTimeout: 2000,
                            }).open();
                        } else {
                            app.toast.create({
                                icon: '<i class="material-icons">info</i>',
                                text: data.msg,
                                position: 'center',
                                closeTimeout: 2000,
                            }).open();
                        }
                        mainView.router.refreshPage()
                    }
                });
            });
        });
    } else if (page.name === 'train_detail') {
        app.preloader.hide();
        $$('#orderAddForm').on('formajax:success', function (data, status, xhr) {
            var data = xhr.response;
            var jsonParse = JSON.parse(data);
            mainView.router.navigate('/pay/' + jsonParse.order_id, {
                reloadCurrent: true,
            });
        });
    } else if (page.name === 'left_ticket') {
        var departure_calendar = app.calendar.create({
            inputEl: '#departure_date',
        });

        var departure_autocomplete = app.autocomplete.create({
            inputEl: '#departure',
            openIn: 'dropdown',
            preloader: true, //enable preloader
            /* If we set valueProperty to "id" then input value on select will be set according to this property */
            valueProperty: 'name', //object's "value" property name
            textProperty: 'name', //object's "text" property name
            limit: 20, //limit to 20 results
            source: function (query, render) {
                var autocomplete = this;
                var results = [];
                if (query.length === 0) {
                    render(results);
                    return;
                }
                // Show Preloader
                autocomplete.preloaderShow();

                // Do Ajax request to Autocomplete data
                app.request({
                    url: '/api/stations/search',
                    method: 'GET',
                    dataType: 'json',
                    //send "query" to server. Useful in case you generate response dynamically
                    data: {
                        query: query,
                    },
                    success: function (data) {
                        // Find matched items
                        for (var i = 0; i < data.length; i++) {
                            results.push(data[i]);
                        }
                        // Hide Preoloader
                        autocomplete.preloaderHide();
                        // Render items by passing array with result items
                        render(results);
                    }
                });
            }
        });

        var arrival_autocomplete = app.autocomplete.create({
            inputEl: '#arrival',
            openIn: 'dropdown',
            preloader: true, //enable preloader
            /* If we set valueProperty to "id" then input value on select will be set according to this property */
            valueProperty: 'name', //object's "value" property name
            textProperty: 'name', //object's "text" property name
            limit: 20, //limit to 20 results
            source: function (query, render) {
                var autocomplete = this;
                var results = [];
                if (query.length === 0) {
                    render(results);
                    return;
                }
                // Show Preloader
                autocomplete.preloaderShow();

                // Do Ajax request to Autocomplete data
                app.request({
                    url: '/api/stations/search',
                    method: 'GET',
                    dataType: 'json',
                    //send "query" to server. Useful in case you generate response dynamically
                    data: {
                        query: query,
                    },
                    success: function (data) {
                        // Find matched items
                        for (var i = 0; i < data.length; i++) {
                            results.push(data[i]);
                        }
                        // Hide Preoloader
                        autocomplete.preloaderHide();
                        // Render items by passing array with result items
                        render(results);
                    }
                });
            }
        });

        // search button on click
        $$('#search-button').on('click', function () {
            app.preloader.show();
            app.request({
                url: '/api/train/leftTicket',
                method: 'POST',
                dataType: 'json',
                //send "query" to server. Useful in case you generate response dynamically
                data: {
                    from: $$("#departure").val(),
                    to: $$("#arrival").val(),
                    date: $$("#departure_date").val(),
                    _token: $$("#_csrf").val(),
                },
                success: function (data) {
                    app.preloader.hide();
                    if (data.success === false) {
                        app.toast.create({
                            icon: '<i class="material-icons">error</i>',
                            text: data.msg,
                            position: 'center',
                            closeTimeout: 2000,
                        }).open();
                    } else {
                        $$('#trains_data').empty();
                        var table_data = ""
                        for (var i = 0; i < data.data.length; i++) {
                            var row = "<tr>";
                            var train = [
                                data.data[i].train_code,
                                data.data[i].can_reserve ? "<a href=\"/detail/" + data.data[i].train_no + "/" + data.data[i].start_station_id + "/" + data.data[i].end_station_id + "/" + data.data[i].seat_type + "/" + data.data[i].date + "/" + data.data[i].user_start_station.station_code + "/" + data.data[i].user_end_station.station_code + "/" + data.data[i].avail_seats + "/" + data.data[i].start_time + "\" class=\"button button-fill detail-button\" target=\"_blank\">预定座位</a>\n" : "列车已满",
                                data.data[i].user_start_station.station_name,
                                data.data[i].user_end_station.station_name,
                                data.data[i].start_time,
                                data.data[i].end_time,
                                data.data[i].duration,
                                data.data[i].remain_seats[0],
                                data.data[i].remain_seats[1],
                                data.data[i].remain_seats[2],
                                data.data[i].remain_seats[3],
                                data.data[i].remain_seats[4],
                                data.data[i].remain_seats[5],
                                data.data[i].remain_seats[6],
                                data.data[i].remain_seats[7],
                                data.data[i].remain_seats[8],
                                data.data[i].remain_seats[9],
                                data.data[i].remain_seats[10],
                            ];
                            row += "<td class=\"label-cell\" style='font-size: 1.5em; font-weight: 600'>" + train[0] + "</td>";
                            for (var j = 1; j < train.length; j++) {
                                row += "<td class=\"label-cell\">" + train[j] + "</td>";
                            }

                            table_data += row + "</tr>"

                        }

                        $$('#trains_data').html(table_data);
                        app.dataTable.create({
                            el: '.data-table',
                        });

                    }
                }
            });
        });
    }
});