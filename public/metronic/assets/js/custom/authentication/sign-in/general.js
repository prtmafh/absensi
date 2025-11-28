"use strict";
var KTSigninGeneral = (function () {
    var form, submitButton, validator;

    return {
        init: function () {
            form = document.querySelector("#kt_sign_in_form");
            submitButton = document.querySelector("#kt_sign_in_submit");

            validator = FormValidation.formValidation(form, {
                fields: {
                    username: {
                        // ubah dari email ke username
                        validators: {
                            notEmpty: { message: "Username wajib diisi" },
                        },
                    },
                    password: {
                        validators: {
                            notEmpty: { message: "Password wajib diisi" },
                        },
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                    }),
                },
            });

            submitButton.addEventListener("click", function (e) {
                e.preventDefault();

                validator.validate().then(function (status) {
                    if (status === "Valid") {
                        // tampilkan loading spinner
                        submitButton.setAttribute("data-kt-indicator", "on");
                        submitButton.disabled = true;

                        // kirim ke Laravel setelah 1 detik
                        setTimeout(function () {
                            submitButton.removeAttribute("data-kt-indicator");
                            submitButton.disabled = false;
                            form.submit(); // kirim form ke server Laravel
                        }, 1000);
                    } else {
                        Swal.fire({
                            text: "Ada kesalahan, periksa kembali username dan password.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, mengerti!",
                            customClass: { confirmButton: "btn btn-primary" },
                        });
                    }
                });
            });
        },
    };
})();

KTUtil.onDOMContentLoaded(function () {
    KTSigninGeneral.init();
});
