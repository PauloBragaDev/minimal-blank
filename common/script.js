document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll("form:not(.ajax_off)").forEach(form => {

        form.addEventListener("submit", async function (e) {
            e.preventDefault();

            const submitButton = form.querySelector("button[type='submit'], input[type='submit']");
            const originalText = submitButton.innerHTML;
            const loader = document.querySelector(".ajax_load");

            try {
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <span class="spinner-border spinner-border-sm mx-2"></span> Aguarde...
                `;

                if (loader) loader.style.display = "flex";

                const formData = new FormData(form);

                const response = await fetch(form.action, {
                    method: "POST",
                    body: formData
                });

                const data = await response.json();

                handleResponse(form, data);

            } catch (error) {
                showError(form, "Erro ao processar requisição.");
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
                if (loader) loader.style.display = "none";

                if (form.dataset.reset === "true") {
                    form.reset();
                }
            }

        });

    });

});


function handleResponse(form, response) {

    if (response.message) {
        showMessage(form, response.message);
    }

    if (response.redirect) {
        window.location.href = response.redirect;
    }

    if (response.reload) {
        setTimeout(() => location.reload(), 2000);
    }
}


function showMessage(form, message) {

    let flash = form.querySelector(".ajax_response");

    if (!flash) {
        flash = document.createElement("div");
        flash.classList.add("ajax_response");
        form.prepend(flash);
    }

    flash.innerHTML = message;
    flash.style.display = "block";
}


function showError(form, message) {
    showMessage(form, `<div class="alert alert-danger">${message}</div>`);
}