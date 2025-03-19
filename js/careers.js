document.addEventListener("DOMContentLoaded", () => {
    const jobList = document.getElementById("job-list");
    const filterButtons = document.querySelectorAll(".filter-btn");

    // Static list of open roles
    const jobs = [
        { title: "Marketing Specialist", description: "Create and manage marketing campaigns.", category: "marketing", applyLink: "mailto:hr@organicfoodstore.com?subject=Marketing Specialist" },
        { title: "Supply Chain Manager", description: "Manage supply chain logistics.", category: "logistics", applyLink: "mailto:hr@organicfoodstore.com?subject=Supply Chain Manager" },
        { title: "Customer Support Representative", description: "Assist customers and resolve issues.", category: "customer-service", applyLink: "mailto:hr@organicfoodstore.com?subject=Customer Support Representative" },
        { title: "Web Developer", description: "Develop and maintain our website.", category: "technology", applyLink: "mailto:hr@organicfoodstore.com?subject=Web Developer" },
        { title: "Food Quality Specialist", description: "Ensure food safety and quality.", category: "quality", applyLink: "mailto:hr@organicfoodstore.com?subject=Food Quality Specialist" },
    ];

    // Function to display jobs
    const displayJobs = (category) => {
        jobList.innerHTML = ""; // Clear existing jobs
        jobs
            .filter(job => category === "all" || job.category === category)
            .forEach(job => {
                const jobCard = document.createElement("div");
                jobCard.className = "job-card";
                jobCard.innerHTML = `
                    <h3 class="job-title">${job.title}</h3>
                    <p class="job-description">${job.description}</p>
                    <a href="${job.applyLink}" class="apply-btn">Apply Now</a>
                `;
                jobList.appendChild(jobCard);
            });
    };

    // Event listener for filter buttons
    filterButtons.forEach(button => {
        button.addEventListener("click", () => {
            const category = button.dataset.role;
            displayJobs(category);
        });
    });

    // Display all jobs by default
    displayJobs("all");
});
