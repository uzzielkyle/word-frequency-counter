<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Word Frequency Counter</title>
    <link rel="stylesheet" type="text/css" href="styles.css">

</head>

<body>
    <main>
        <h1>Word Frequency Counter</h1>

        <form action="process.php" method="post">
            <label for="text">Paste your text here:</label><br>
            <textarea id="text" name="text" rows="10" cols="50"
                placeholder="She may be small in stature, but her personality is anything but tiny. She has the brightest smile that lights up the room, and her laugh is contagious. Her eyes sparkle when she talks about things she loves, whether it’s her favorite movie, her latest book, or a new adventure she’s planning. Her kindness is endless, and she always knows how to make the people around her feel special. There’s something so refreshing about how she carries herself—confident yet humble, sweet but strong. She’s truly one of a kind, and being around her is the highlight of my day."
                required></textarea><br><br>

            <label for="sort">Sort by frequency:</label>
            <select id="sort" name="sort">
                <option value="asc">Ascending</option>
                <option value="desc">Descending</option>
            </select><br><br>

            <label for="limit">Number of words to display:</label>
            <input type="number" id="limit" name="limit" value="10" min="1"><br><br>

            <input type="submit" value="Calculate Word Frequency">
        </form>

        <div id="container">
            <strong>Output:</strong>
            <ol id="output-box"></ol>
        </div>
    </main>
    <script>
        const form = document.querySelector("form")

        form.addEventListener("submit", (event) => {
            event.preventDefault()

            const formData = new FormData(form)

            fetch(form.action, {
                method: form.method,
                body: formData
            })
                .then(response => response.json())
                .then(words => {
                    const outputBox = document.getElementById("output-box")
                    outputBox.innerHTML = ''

                    for (word in words) {
                        const li = document.createElement("li")
                        li.innerHTML = `<strong>${word}</strong> => ${words[word]}`
                        outputBox.appendChild(li)
                    }
                })
                .catch(error => {
                    console.log(data)
                });
        });
    </script>
</body>

</html>