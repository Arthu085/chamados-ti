export function showToast(message, type = "info") {
	const backgroundClass =
		{
			success: "bg-success",
			danger: "bg-danger",
			warning: "bg-warning",
		}[type] || "bg-info";

	const toastWrapper = document.createElement("div");
	toastWrapper.className = "position-fixed top-0 end-0 p-3";
	toastWrapper.style.zIndex = "1100";

	toastWrapper.innerHTML = `
        <div class="toast align-items-center text-white ${backgroundClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;

	document.body.appendChild(toastWrapper);

	const toastEl = toastWrapper.querySelector(".toast");
	const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
	toast.show();

	toastEl.addEventListener("hidden.bs.toast", () => {
		toastWrapper.remove();
	});
}
