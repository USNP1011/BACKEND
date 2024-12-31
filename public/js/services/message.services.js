angular.module("message.service", [])
  .factory("message", MessageServices)
  .factory("pesan", PesanServices);
function MessageServices(swangular, $q) {
  return { info: info, success: success, error: error, warning: warning, dialog: dialog, dialogmessage: dialogmessage, dialogconfirm: dialogconfirm, confirm: confirm };

  function info(params) {
    swangular.swal({
      title: "Sukses",
      text: params,
      type: "info"
    });
  }
  function success(params) {
    swangular.swal({
      title: "Success",
      text: params,
      type: "success"
    });
  }

  function error(params) {
    swangular.swal({
      title: params,
      // text: params,
      type: "error"
    });
  }

  function warning(params) {
    swangular.swal({
      title: "Sukses",
      text: params,
      type: "warning"
    });
  }

  function dialog(messageText, yesBtn, cancelBtn) {
    var def = $q.defer();
    var yesText = "Ya";
    var cancelText = "Batal";
    var showCancel = true;

    if (yesBtn) yesText = yesBtn;

    if (cancelBtn) {
      cancelText = cancelBtn;
    } else showCancel = false;

    swangular
      .swal({
        title: "Yakin ?",
        text: messageText,
        type: "warning",
        showCancelButton: showCancel,
        confirmButtonText: yesText,
        cancelButtonText: cancelText,
        reverseButtons: true,
        allowOutsideClick: false
      })
      .then(result => {
        if (result.value) {
          def.resolve(result.value);
        } else {
          def.reject(result.value);
        }
      });

    return def.promise;
  }

  function confirm(messageText, yesBtn, cancelBtn) {
    var def = $q.defer();
    var yesText = "Ya";
    var cancelText = "Batal";
    var showCancel = false;

    if (yesBtn) yesText = yesBtn;

    if (cancelBtn) {
      cancelText = cancelBtn;
    } else showCancel = false;

    swangular
      .swal({
        title: "Yakin ?",
        text: messageText,
        type: "warning",
        showCancelButton: showCancel,
        confirmButtonText: yesText,
        reverseButtons: true,
        allowOutsideClick: false
      })
      .then(result => {
        if (result.value) {
          def.resolve(result.value);
        } else {
          def.reject(result.value);
        }
      });

    return def.promise;
  }

  function dialogmessage(messageText, yesBtn, cancelBtn) {
    var def = $q.defer();
    var yesText = "Ya";
    var cancelText = "Batal";
    var showCancel = true;

    if (yesBtn) yesText = yesBtn;

    if (cancelBtn) {
      cancelText = cancelBtn;
    } else showCancel = false;

    swangular
      .swal({
        title: "Information ?",
        text: messageText,
        type: "info",
        showCancelButton: showCancel,
        confirmButtonText: yesText,
        cancelButtonText: cancelText,
        reverseButtons: true,
        allowOutsideClick: false
      })
      .then(result => {
        if (result.value) {
          def.resolve(result.value);
        } else {
          def.reject(result.value);
        }
      });

    return def.promise;
  }

  function dialogconfirm(messageText, yesBtn, cancelBtn) {
    var def = $q.defer();
    var yesText = "Ya";
    var cancelText = "Batal";
    var showCancel = false;

    if (yesBtn) yesText = yesBtn;

    if (cancelBtn) {
      cancelText = cancelBtn;
    } else showCancel = false;

    swangular
      .swal({
        title: "Information ?",
        text: messageText,
        type: "info",
        showCancelButton: showCancel,
        confirmButtonText: yesText,
        // cancelButtonText: cancelText,
        reverseButtons: true
      })
      .then(result => {
        if (result.value) {
          def.resolve(result.value);
        } else {
          def.reject(result.value);
        }
      });

    return def.promise;
  }

}

function PesanServices($q) {
  return { success: success, error: error, info: info, dialog: dialog };

  function success(param) {
    Swal.fire({
      icon: 'success',
      title: param,
    })
  }

  function error(param) {
    Swal.fire({
      icon: 'error',
      title: param,
    })
  }

  function info(param) {
    Swal.fire({
      icon: 'info',
      title: param,
    })
  }

  function dialog(messageText, yesBtn, cancelBtn) {
    var def = $q.defer();

    var yesText = "Ya";
    var cancelText = "Batal";
    var showCancel = true;

    if (yesBtn) yesText = yesBtn;

    if (cancelBtn) {
      cancelText = cancelBtn;
    } else showCancel = false;

    Swal.fire({
      title: messageText,
      icon: 'info',
      showCancelButton: true,
      // confirmButtonColor: '#3085d6',
      // cancelButtonColor: '#d33',
      cancelButtonText: cancelText,
      confirmButtonText: yesText
    }).then((result) => {
      if (result.value) {
        def.resolve(result.value);
      } else {
        def.reject(result.value);
      }
    })
    return def.promise;
  }
}
