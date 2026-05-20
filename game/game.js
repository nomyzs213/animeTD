document.getElementById("button").addEventListener("click", async () => {

    const response = await fetch("click.php", {
        method: "POST"
    });

    const data = await response.json();

    document.getElementById("clicks").textContent = data.clicks;
});