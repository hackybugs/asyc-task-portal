<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Task</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    <h1>Create a New Task</h1>

    <form id="taskForm">
        <label for="param">Enter Parameter:</label>
        <input type="text" id="param" name="param" required>
        <button type="submit">Create Task</button>
    </form>

    <div id="response" style="margin-top:20px;"></div>

    <script>
        document.getElementById('taskForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const param = document.getElementById('param').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch("/api/tasks/submit", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify({ param: param })
                });

                const data = await response.json();
                console.log(data);
                if (response.ok) {
                    document.getElementById('response').innerHTML = `
                        <p><strong>Task Submitted Successfully!</strong></p>
                        <p>Task ID: ${data.task_id}</p>
                        <p>Status: ${data.status}</p>
                    `;
                } else {
                    document.getElementById('response').innerHTML = `
                        <p><strong>Error:</strong> ${data.message || 'Something went wrong.'}</p>
                    `;
                }
            } catch (error) {
                document.getElementById('response').innerHTML = `
                    <p><strong>Error:</strong> ${error.message}</p>
                `;
            }
        });
    </script>

</body>
</html>
