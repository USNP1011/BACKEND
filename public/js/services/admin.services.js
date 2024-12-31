angular.module('admin.service', [])
    // admin
    .factory('dashboardServices', dashboardServices)
    ;

function dashboardServices($http, $q, helperServices, AuthService) {
    var controller = helperServices.url + 'sync/';
    var service = {};
    service.data = [];
    service.instance = false;
    return {
        get: get,
        sync:sync
    };

    function get() {
        var def = $q.defer();
        $http({
            method: 'get',
            url: controller + 'read',
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err);
            }
        );
        return def.promise;
    }

    function sync(param, url) {
        var def = $q.defer();
        $http({
            method: 'post',
            url: controller + url,
            data: param,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err);
            }
        );
        return def.promise;
    }
}



