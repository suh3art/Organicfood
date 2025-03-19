document.addEventListener("DOMContentLoaded", () => {
    const accordionHeaders = document.querySelectorAll(".accordion-header");

    accordionHeaders.forEach(header => {
        header.addEventListener("click", () => {
            const targetBody = document.getElementById(header.dataset.toggle);
            
            if (targetBody.classList.contains("active")) {
                targetBody.classList.remove("active");
            } else {
                document.querySelectorAll(".accordion-body").forEach(body => body.classList.remove("active"));
                targetBody.classList.add("active");
            }
        });
    });
});
