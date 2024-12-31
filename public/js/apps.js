angular.module('apps', [
    'adminctrl',
    'helper.service',
    'admin.service',
    'auth.service',
    'message.service'
])
    .controller('indexController', indexController)

function indexController($scope, helperServices, dashboardServices) {
    $scope.titleHeader = "Booking Foto";
    $scope.header = "";
    $scope.breadcrumb = "";
    $scope.title;
    $scope.warning = 0;
    dashboardServices.get().then(res=>{
        console.log(res);
    })
}
