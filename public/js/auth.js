document.addEventListener('DOMContentLoaded', function() {
    const recover_button = document.getElementById('recover_button');

    if (recover_button == null)
        return;

    const dialog = document.querySelector('#recover_dialog');
    
    const cancel_button =  document.getElementById('cancel_button');

    const email_error = document.getElementById('recovery_error');

    if (email_error != null)
        dialog.showModal();

    recover_button.addEventListener('click', function() {
        dialog.showModal();
    });

    cancel_button.addEventListener('click', function() {
        dialog.close();
    });
});

function disableSubmit(button) {
    button.disabled = true;
    button.innerHTML = '<i class="fa fa-spinner fa-spin" style="margin-right: 0.25rem" aria-hidden="true"></i>Submitting...';
    button.form.submit();
}