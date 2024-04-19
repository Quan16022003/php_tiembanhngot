function themeToggle() {
    const element = document.getElementById("theme-toggle");
    if (element.classList.contains("light")) {
        element.classList.remove("light");
        element.classList.add("dark");
    } else {
        element.classList.remove("dark");
        element.classList.add("light");
    }
}