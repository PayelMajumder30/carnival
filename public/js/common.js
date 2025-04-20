// sweetalert toast
function toastFire(type = 'success', title) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'bottom-end',
        timer: 2500,
        showConfirmButton: false,
        // timer: 2000,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
    Toast.fire({
        icon: type,
        title: title
    })
}

function toastDelete() {
    Swal.fire({
        title: "Do you want to save the changes?",
        // showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: "Delete",
        // denyButtonText: `Don't save`
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          Swal.fire("Saved!", "", "success");
        }
    });
}