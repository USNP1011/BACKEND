angular.module("auth.service", [])
    .factory("AuthService", AuthService);

function AuthService($http, $q, helperServices, pesan) {
    var service = {};
    return {
        login: login,
        setRole: setRole,
        // logOff: logoff,
        // userIsLogin: userIsLogin,
        // getUserName: getUserName,
        // userIsLogin: userIsLogin,
        // userInRole: userInRole,
        getHeader: getHeader,
        // getToken: getToken,
        // getUserId: getUserId
    }

    function login(user) {
        var def = $q.defer();
        $http({
            method: 'POST',
            url: helperServices.url + "/login/check",
            data: user,
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJJc3N1ZXIgb2YgdGhlIEpXVCIsImF1ZCI6IkF1ZGllbmNlIHRoYXQgdGhlIEpXVCIsInN1YiI6IlN1YmplY3Qgb2YgdGhlIEpXVCIsIm5iZiI6MTcxODk0NjYzNCwiaWF0IjoxNzE4OTQ2NjI0LCJleHAiOjE3MTg5ODI2MjQsImRhdGEiOnsidWlkIjo2LCJ1c2VybmFtZSI6IkFkbWluaXN0cmF0b3IiLCJlbWFpbCI6ImFkbWluaXN0cmF0b3JAbWFpbC5jb20iLCJzdGF0dXMiOm51bGx9fQ.fSs5VYC1WZuLyYy3fuopamQFskzSVcCoo6Qp9_wGqelkY9hE8KWfiPy1x4DzHtE_bdquebuo8ApZ-TJBAKhhQnKLTW2X2dqjKZ-AsHko2KUIp1unjRJ-i1W7BjS79hvgZonl8NBAbOOYRr_XVHRPaDo4Xqh2-lY2bZb_6GlQIqY'
            }
        }).then(res => {
            var user = res.data;
            def.resolve(user);
        }, err => {
            def.reject(err.data.messages.error);
            pesan.error(err.data.messages.error);
        });
        return def.promise;
    }

    function setRole(user) {
        var def = $q.defer();
        $http({
            method: 'POST',
            url: helperServices.url + "/login/set",
            data: user,
            headers: {
                'Content-Type': 'application/json'
            }
        }).then(res => {
            var user = res.data;
            def.resolve(user);
        }, err => {
            def.reject(err.data.messages.error);
            message.error(err.data.messages.error);
        });
        return def.promise;
    }

    function getHeader() {

        try {
            if (userIsLogin()) {
                return {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJJc3N1ZXIgb2YgdGhlIEpXVCIsImF1ZCI6IkF1ZGllbmNlIHRoYXQgdGhlIEpXVCIsInN1YiI6IlN1YmplY3Qgb2YgdGhlIEpXVCIsIm5iZiI6MTcxODk2MTk4NiwiaWF0IjoxNzE4OTYxOTc2LCJleHAiOjE3MTg5OTc5NzYsImRhdGEiOnsidWlkIjo2LCJ1c2VybmFtZSI6IkFkbWluaXN0cmF0b3IiLCJlbWFpbCI6ImFkbWluaXN0cmF0b3JAbWFpbC5jb20iLCJzdGF0dXMiOm51bGx9fQ.e0IthKaRd5DdRXE8th9EikV2bhq8B8xiJK-KUbcW3TxK0z2DuvfyUOJ3MhJVMsxi0cUahfAGxN3PREjOuQDWF9OXvKBPYK9OUndV1Z2Gqs0XrpYW8sv_zTEbA_d8INQnrayQ-hO8lwh3ZdBDq6PeDeIm9sH2Fqwk2gFJ93k5V80'
                }
            }
            throw new Error("Not Found Token");
        } catch {
            return {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJJc3N1ZXIgb2YgdGhlIEpXVCIsImF1ZCI6IkF1ZGllbmNlIHRoYXQgdGhlIEpXVCIsInN1YiI6IlN1YmplY3Qgb2YgdGhlIEpXVCIsIm5iZiI6MTcxODk2MTk4NiwiaWF0IjoxNzE4OTYxOTc2LCJleHAiOjE3MTg5OTc5NzYsImRhdGEiOnsidWlkIjo2LCJ1c2VybmFtZSI6IkFkbWluaXN0cmF0b3IiLCJlbWFpbCI6ImFkbWluaXN0cmF0b3JAbWFpbC5jb20iLCJzdGF0dXMiOm51bGx9fQ.e0IthKaRd5DdRXE8th9EikV2bhq8B8xiJK-KUbcW3TxK0z2DuvfyUOJ3MhJVMsxi0cUahfAGxN3PREjOuQDWF9OXvKBPYK9OUndV1Z2Gqs0XrpYW8sv_zTEbA_d8INQnrayQ-hO8lwh3ZdBDq6PeDeIm9sH2Fqwk2gFJ93k5V80'
            }
        }
    }

    // function logoff() {
    //     StorageService.clear();
    //     $state.go("login");

    // }

    // function getUserName() {
    //     if (userIsLogin) {
    //         var result = StorageService.getObject("user");
    //         return result.Username;
    //     }
    // }

    // function getToken() {
    //     if (userIsLogin) {
    //         var result = StorageService.getObject("user");
    //         return result.token;
    //     }
    // }
    // function getUserId() {
    //     if (userIsLogin) {
    //         var result = StorageService.getObject("user");
    //         return result.id;
    //     }
    // }

    // function userIsLogin() {
    //     var result = StorageService.getObject("user");
    //     if (result) {
    //         return true;
    //     }
    // }

    // function userInRole(role) {
    //     var result = StorageService.getItem("user");
    //     if (result && result.roles.find(x => x.name = role)) {

    //         return true;
    //     }
    // }
}