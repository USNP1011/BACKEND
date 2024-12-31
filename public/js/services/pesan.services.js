angular.module("message.service", [])
  .factory("pesan", PesanServices);

function PesanServices($q) {
  return { success: success, error: error, info: info, dialog: dialog, Success:Success, Error:Error };

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
      allowOutsideClick:false
    })
  }

  function info(param) {
    Swal.fire({
      icon: 'info',
      title: param,
    })
  }

  // function dialog(messageText, yesBtn, cancelBtn) {
  //   var def = $q.defer();

  //   var yesText = "Ya";
  //   var cancelText = "Batal";
  //   var showCancel = true;

  //   if (yesBtn) yesText = yesBtn;

  //   if (cancelBtn) {
  //     cancelText = cancelBtn;
  //   } else showCancel = false;

  //   Swal.fire({
  //     title: messageText,
  //     icon: 'warning',
  //     showCancelButton: true,
  //     allowOutsideClick: false,
  //     cancelButtonText: cancelText,
  //     confirmButtonText: yesText
  //   }).then((result) => {
  //     if (result.value) {
  //       def.resolve(result.value);
  //     } else {
  //       def.reject(result.value);
  //     }
  //   })
  //   return def.promise;
  // }


  function dialog(messageText, yesBtn, cancelBtn, iconDialog) {
    var def = $q.defer();

    var yesText = "Ya";
    var cancelText = "Batal";
    var showCancel = true;
    var icon = 'warning';

    if (yesBtn) yesText = yesBtn;

    if(iconDialog) icon=iconDialog;

    if (cancelBtn) cancelText = cancelBtn;
    else showCancel = false;

    Swal.fire({
      title: messageText,
      icon: icon,
      showCancelButton: showCancel,
      allowOutsideClick: false,
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

  function Success(params) {
    const Toast = Swal.mixin({
      toast: true,
      background:'#3ea92a',
      position: 'top-end',
      showConfirmButton: false,
      timer: 4000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    })
    
    Toast.fire({
      icon: 'success',
      title: params
    })
  }
  
  function Error(params) {
    const Toast = Swal.mixin({
      toast: true,
      background:'#fc7c96',
      position: 'top-end',
      showConfirmButton: false,
      timer: 4000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    })
    
    Toast.fire({
      icon: 'error',
      title: params
    })
  }
}
