app.directive('isWords', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, element, attributes, control) {
            control.$validators.isWords = function (modelValue, viewValue) {
                if (control.$isEmpty(modelValue)) { return false; }
                else {
                    if (/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/.test(viewValue)) { return true; }
                    else { return false; }
                }
            }
        }
    };
});

app.directive('isWord', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, element, attributes, control) {
            control.$validators.isWord = function (modelValue, viewValue) {
                if (control.$isEmpty(modelValue)) { return false; }
                else {
                    if (/^[a-zA-ZáéíóúÁÉÍÓÚñÑ]+$/.test(viewValue)) { return true; }
                    else { return false; }
                }
            }
        }
    };
});

app.directive('showErrorMessages', function(){
    return {
        restrict: 'E',
        replace: true,
        templateUrl: 'html/error_messages.html'
    };
});

app.directive('back', ['$window', function($window) {
    return {
        restrict: 'A',
        link: function (scope, elem, attrs) {
            elem.bind('click', function () {
                $window.history.back();
            });
        }
    };
}]);