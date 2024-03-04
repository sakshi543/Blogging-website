// Function to toggle between create post page and main screen
function toggleCreatePostPage() {
  const createPostPage = document.getElementById("postForm");
  const mainContent = document.getElementById("mainContent");

  if (createPostPage.style.display === "none") {
    createPostPage.style.display = "block";
    mainContent.style.display = "none";
  } else {
    createPostPage.style.display = "none";
    mainContent.style.display = "block";
  }
}

// Function to toggle between edit post page and main screen
function toggleEditPostPage() {
  const editPostPage = document.getElementById("editPostPage");
  const mainContent = document.getElementById("mainContent");

  if (editPostPage.style.display === "none") {
    editPostPage.style.display = "block";
    mainContent.style.display = "none";
  } else {
    editPostPage.style.display = "none";
    mainContent.style.display = "block";
  }
}

// Function to populate the edit post form with the selected post's data
function populateEditPostForm() {
  const post = document.getElementById(selectedPostId);
  const heading = post.querySelector("h2");
  const paragraph = post.querySelector("p");

  document.getElementById("editPostTitle").value = heading.textContent;
  document.getElementById("editPostContent").value = paragraph.textContent;
}

// Event listener for the "Add Post" button
const addPostButton = document.getElementById("addPostButton");
addPostButton.addEventListener("click", toggleCreatePostPage);

// Event listener for the "Edit Post" button
document.addEventListener("click", function (event) {
  if (event.target.classList.contains("edit-button")) {
    selectedPostId = event.target.closest("article").id;
    populateEditPostForm();
    toggleEditPostPage();
  }
});
