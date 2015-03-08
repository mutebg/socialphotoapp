var app = angular.module('social', ['ngRoute', 'ngAnimate']);


//ROUTER///////////////////////////////////////////////////////////////////////
app.config(function($routeProvider, $locationProvider) {
    $routeProvider
        .when('/', {
            templateUrl: 'front/js/views/app.html',
            controller: 'AppController',
            controllerAs: 'app'
        })
        .when('/photo/:id', {
            templateUrl: 'front/js/views/photo.html',
            controller: 'PhotoController',
            controllerAs: 'photo'
        })
        .otherwise({
            redirectTo: '/'
        });
});


//SERVICES/////////////////////////////////////////////////////////////////////
app.service('TransferService', function(){
    var data = JSON.parse(localStorage.getItem('social') || '{}');

    function set(key, item, noSave) {
        data[key] = item;

        if (noSave !== true) {
            localStorage.setItem('social', JSON.stringify(data));
        }
    }

    function get(key) {
        return data[key] || {};
    }

    return {
        set: set,
        get: get
    };
});


//CONTROLLERS//////////////////////////////////////////////////////////////////
app.controller('AppController', function($scope, $http, $location, TransferService){
    this.form = {
        from: new Date( new Date() - 1000 * 60 * 60 * 24 * 130 ), //ms * s * m * h * d 
        to: new Date(), 
        social: currentSocial
    };
    this.isLoading = false;
    
    this.getPhotos = getPhotos;

    function getPhotos(form) {
        this.isLoading = true;
        $http.get('photos/', { params: form })
            .success(function(response) {
                this.isLoading = false;

                if (response.status == 'success') {
                    TransferService.set('photos', response.data);
                    $location.path('/photo/0');
                } else {
                    $scope.$emit('notify', {
                        message: response.message,
                        time: 3000
                    });
                }
    
            }.bind(this))

            .error(function() {
                this.isLoading = false;
                $scope.$emit('notify', {
                    message: 'Error. Please refresh page and try again.',
                    time: 3000
                });
            }.bind(this));
    }
});

app.controller('PhotoController', function($location, $routeParams, TransferService){
    var photoIndex = parseInt($routeParams.id);
    var photos = TransferService.get('photos');
    this.image = photos[ photoIndex ];
    this.photoIndex = photoIndex;
    this.isFirst = photoIndex ===  0;
    this.isLast = ( photoIndex + 1 ) ===  photos.length;
    
    this.direction = direction;
    this.close = close;

    function direction(index) {
        $location.path('/photo/' + index);
    }

    function close() {
        $location.path('/');
    }
});


//DIRECTIVE////////////////////////////////////////////////////////////////////
app.directive('notification', function notification($rootScope, $timeout) {
    return {
        template: '<div class="alert alert--error alert--fixed" ng-show="visible">{{ message }}</di>',
        link: function(scope) {
            scope.message = false;
            scope.visible = false;
            
            $rootScope.$on('notify', function(event, data){
                var time = 2000;
                if ( typeof data === "object" ) {
                    scope.message = data.message;
                    time = data.time;
                } else {
                    scope.message = data;
                }
                
                scope.visible = true;
                $timeout( function(){
                    scope.visible = false;
                }, time);
            });

            $rootScope.$on('notify:cancel', function(event, data){
                scope.visible = false;
            });
        }
    };
});