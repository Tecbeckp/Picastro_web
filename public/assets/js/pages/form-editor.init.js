// Initialize ClassicEditor for elements with the class "ckeditor-classic"
let editorInstances = {};

var ckClassicEditor = document.querySelectorAll(".ckeditor-classic");
if (ckClassicEditor) {
    Array.from(ckClassicEditor).forEach(function () {
        ClassicEditor.create(document.querySelector(".ckeditor-classic"))
            .then(function (editor) {
                editor.ui.view.editable.element.style.height = "200px"; // Set editor height
            })
            .catch(function (error) {
                console.error(error); // Log any errors
            });
    });
}
var ckClassicEditorEdit = document.querySelectorAll(".ckeditor-classic-edit");

if (ckClassicEditorEdit) {
    Array.from(ckClassicEditorEdit).forEach(function (element) {
        ClassicEditor.create(element) // Pass the current element to the create method
            .then(function (editor) {
                editorInstances[element.id] = editor;
                editor.ui.view.editable.element.style.height = "200px"; // Set editor height
            })
            .catch(function (error) {
                console.error(error); // Log any errors
            });
    });
}

function setEditorData(editorId, data) {
    if (editorInstances[editorId]) {
        editorInstances[editorId].setData(data);
    } else {
        console.error('Editor instance not found.');
    }
}

// Initialize Quill Snow Editor for elements with the class "snow-editor"
var snowEditor = document.querySelectorAll(".snow-editor");
if (snowEditor) {
    Array.from(snowEditor).forEach(function (element) {
        var options = {}; // Define options object for Quill

        // Check if the element has the class "snow-editor"
        if (element.classList.contains("snow-editor")) {
            options.theme = "snow";
            options.modules = {
                toolbar: [
                    [{ font: [] }, { size: [] }],
                    ["bold", "italic", "underline", "strike"],
                    [{ color: [] }, { background: [] }],
                    [{ script: "super" }, { script: "sub" }],
                    [{ header: [false, 1, 2, 3, 4, 5, 6] }, "blockquote", "code-block"],
                    [{ list: "ordered" }, { list: "bullet" }, { indent: "-1" }, { indent: "+1" }],
                    ["direction", { align: [] }],
                    ["link", "image", "video"],
                    ["clean"]
                ]
            };
        }
        new Quill(element, options); // Initialize Quill editor with the options
    });
}

// Initialize Quill Bubble Editor for elements with the class "bubble-editor"
var bubbleEditor = document.querySelectorAll(".bubble-editor");
if (bubbleEditor) {
    Array.from(bubbleEditor).forEach(function (element) {
        var options = {}; // Define options object for Quill

        // Check if the element has the class "bubble-editor"
        if (element.classList.contains("bubble-editor")) {
            options.theme = "bubble"; // Set theme to "bubble"
        }
        new Quill(element, options); // Initialize Quill editor with the options
    });
}
