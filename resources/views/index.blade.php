<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Notes Management System</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="container mx-auto p-6">

    <h1 class="text-3xl font-bold mb-6 text-center">
        AI Notes Management System
    </h1>

    <!-- Create Note -->
    <div class="bg-white p-6 rounded shadow mb-6">

        <h2 class="text-xl font-semibold mb-4">
            Create Note
        </h2>

        <input
            type="text"
            id="title"
            placeholder="Title"
            class="w-full border p-2 rounded mb-3">

        <textarea
            id="content"
            placeholder="Content"
            rows="5"
            class="w-full border p-2 rounded mb-3"></textarea>

        <button
            onclick="createNote()"
            class="bg-blue-600 text-white px-4 py-2 rounded">
            Create Note
        </button>

    </div>

    <!-- Search -->
    <div class="bg-white p-6 rounded shadow mb-6">

        <h2 class="text-xl font-semibold mb-4">
            Semantic Search
        </h2>

        <div class="flex gap-2">

            <input
                type="text"
                id="search"
                placeholder="Search notes..."
                class="flex-1 border p-2 rounded">

            <button
                onclick="searchNotes()"
                class="bg-green-600 text-white px-4 py-2 rounded">
                Search
            </button>

        </div>

    </div>

    <!-- Notes -->
    <div class="bg-white p-6 rounded shadow">

        <div class="flex justify-between mb-4">
            <h2 class="text-xl font-semibold">
                Notes
            </h2>

            <button
                onclick="loadNotes()"
                class="bg-gray-700 text-white px-3 py-2 rounded">
                Refresh
            </button>
        </div>

        <div id="notesContainer"></div>

    </div>

</div>

<script>

let currentPage = 1;

async function loadNotes(page = 1)
{
    currentPage = page;

    const response =
        await fetch(`/api/notes?page=${page}`);

    const result =
        await response.json();

    let html = '';

    result.data.forEach(note => {

        html += `
        <div class="border rounded p-4 mb-4">

            <div class="flex justify-between">

                <h3 class="font-bold text-lg">
                    ${note.title}
                </h3>

                <button
                    onclick="deleteNote(${note.id})"
                    class="bg-red-600 text-white px-2 py-1 rounded">
                    Delete
                </button>

            </div>

            <p class="mt-2">
                ${note.content}
            </p>

            <div class="mt-3 flex gap-2">

                <button
                    onclick="editNote(${note.id},
                    '${note.title.replace(/'/g,"&#39;")}',
                    '${note.content.replace(/'/g,"&#39;")}')"
                    class="bg-yellow-500 text-white px-3 py-1 rounded">
                    Edit
                </button>

                <button
                    onclick="generateSummary(${note.id})"
                    class="bg-indigo-600 text-white px-3 py-1 rounded">
                    Generate Summary
                </button>

            </div>

            <div
                id="summary-${note.id}"
                class="mt-3 text-sm text-gray-700">
            </div>

        </div>
        `;
    });

    html += `
    <div class="flex gap-2 mt-4">

        <button
            onclick="prevPage()"
            class="bg-gray-500 text-white px-3 py-1 rounded">
            Previous
        </button>

        <button
            onclick="nextPage()"
            class="bg-gray-500 text-white px-3 py-1 rounded">
            Next
        </button>

    </div>
    `;

    document.getElementById('notesContainer')
        .innerHTML = html;
}

async function createNote()
{
    const title =
        document.getElementById('title').value;

    const content =
        document.getElementById('content').value;

    const response =
        await fetch('/api/notes', {

            method:'POST',

            headers:{
                'Content-Type':'application/json'
            },

            body:JSON.stringify({
                title,
                content
            })
        });

    if(response.ok)
    {
        document.getElementById('title').value = '';
        document.getElementById('content').value = '';

        loadNotes();
    }
}

async function deleteNote(id)
{
    if(!confirm('Delete note?'))
    {
        return;
    }

    await fetch(`/api/notes/${id}`,{
        method:'DELETE'
    });

    loadNotes();
}

async function editNote(id, title, content)
{
    const newTitle =
        prompt('Edit Title', title);

    if(newTitle === null) return;

    const newContent =
        prompt('Edit Content', content);

    if(newContent === null) return;

    await fetch(`/api/notes/${id}`,{

        method:'PUT',

        headers:{
            'Content-Type':'application/json'
        },

        body:JSON.stringify({
            title:newTitle,
            content:newContent
        })
    });

    loadNotes();
}

async function generateSummary(id)
{
    const response =
        await fetch(`/api/notes/${id}/summary`,{
            method:'POST'
        });

    const data =
        await response.json();

    document.getElementById(
        `summary-${id}`
    ).innerHTML =
        '<strong>Summary:</strong><br>' +
        data.summary;
}

async function searchNotes()
{
    const q =
        document.getElementById('search').value;

    const response =
        await fetch(
            `/api/notes-search?q=${encodeURIComponent(q)}`
        );

    const data =
        await response.json();

    let html = '';

    data.forEach(note => {

        html += `
        <div class="border rounded p-4 mb-3">

            <h3 class="font-bold">
                ${note.title}
            </h3>

            <p>
                ${note.content}
            </p>

            <div class="text-green-600">
                Similarity Score:
                ${note.score}
            </div>

        </div>
        `;
    });

    document.getElementById('notesContainer')
        .innerHTML = html;
}

function nextPage()
{
    loadNotes(currentPage + 1);
}

function prevPage()
{
    if(currentPage > 1)
    {
        loadNotes(currentPage - 1);
    }
}

loadNotes();

</script>

</body>
</html>