angular.module("component", [])
    .component('viewComp', {
        bindings: {},
        controller: loginController,
        templateUrl: "../../assets/apps/components/login.html"
    })
    .component('bannerComp', {
        bindings: {},
        controller: bannerController,
        templateUrl: "../../assets/apps/components/banner.html"
    })
    .component('bannerrComp', {
        bindings: {},
        controller: bannerrController,
        templateUrl: "../../assets/apps/components/bannerr.html"
    })
    .component('passComp', {
        bindings: {},
        controller: changePassController,
        templateUrl: "../../assets/apps/components/change_pass.html"
    });

function loginController($scope, helperServices, $http, AuthService, pesan) {
    var ctrl = this;
    var login = false;
    ctrl.item = {};
    
    if(login==false){
        setInterval(() => {
            $http.get("check_session").then( function(response) {
                if(response.data==0 && login==false){
                    login = true;
                    $("#exampleModal").modal("show");
                }
             });
        }, 10000);
    }
    ctrl.login = (param)=>{
        AuthService.login($scope.model).then((res)=>{
            if(res.length==1){
                $("#exampleModal").modal("hide");
                pesan.Success("Login berhasil");
                login = false;
            }
        })
    }
    ctrl.batal = ()=>{
        document.location.href= helperServices.url + "login";
    }

    ctrl.changePassword = (param)=>{
        $http.post("change_pass", param).then(res=>{
            pesan.Success(res.data);
            ctrl.item={};
            $("#changePass").modal("hide");
        }, err=>{
            pesan.Error(err.data.messages.error);
        });
    }
}
function bannerController($sce) {
    var ctrl = this;
    var model = {};
    var itemBanner = sessionStorage.getItem("banner");
    ctrl.image = $sce.trustAsHtml("https://dsm01pap002files.storage.live.com/y4m4u07x4HoQ2Razzbx7yUQhnJAntQCf3QDxFYKvvQLspJqwOArIxVrJZ7DQnVNz1WVpkgssORGRvb_ZuMamIZI_DpcCFoDeS-hPZ6u0dPhVadNTs4YKIMvk7ajJ1Xsw0JowUfoWvhyYAkCsPG5zjtTP7mYqgQFlPEtX5GNsTEdNst-RxQtob38iAL854TwdEP6?width=900&height=200&cropmode=none");
    if(itemBanner==null || itemBanner=="0"){
        setTimeout(() => {
            $("#exampleModal1").modal("show");
            sessionStorage.setItem("banner", "1");
        }, 2000);
    }
    // console.log(value);
    // window.localStorage.setItem('banner', JSON.stringify(model));
    
}

function bannerrController($sce) {
    var ctrl = this;
    var model = {};
    var itemBanner = sessionStorage.getItem("banner");
    ctrl.image = $sce.trustAsHtml("https://dsm01pap002files.storage.live.com/y4m4u07x4HoQ2Razzbx7yUQhnJAntQCf3QDxFYKvvQLspJqwOArIxVrJZ7DQnVNz1WVpkgssORGRvb_ZuMamIZI_DpcCFoDeS-hPZ6u0dPhVadNTs4YKIMvk7ajJ1Xsw0JowUfoWvhyYAkCsPG5zjtTP7mYqgQFlPEtX5GNsTEdNst-RxQtob38iAL854TwdEP6?width=900&height=200&cropmode=none");
    if(itemBanner==null || itemBanner=="0"){
        setTimeout(() => {
            $("#exampleModal1").modal("show");
            sessionStorage.setItem("banner", "1");
        }, 1000);
    }
    // console.log(value);
    // window.localStorage.setItem('banner', JSON.stringify(model));
    
}
function changePassController($sce) {
    var ctrl = this;
    var model = {};
    
}