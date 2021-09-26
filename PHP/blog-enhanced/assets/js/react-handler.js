"use strict";

const likeButton = document.getElementById("like-button");
const unlikeButton = document.getElementById("unlike-button");
const likeCountComponent = document.getElementById("like-count");

const likePost = async (postID) => {
  likeButton.setAttribute("disabled", "disabled");

  try {
    const response = await fetch(
      `/blog-enhanced/api/react/?action=like&id=${postID}`
    );
    console.log(response);

    const likesCount = Number(likeCountComponent.innerHTML);

    likeCountComponent.innerHTML = likesCount + 1;

    likeButton.style.display = "none";
    unlikeButton.style.display = "block";
  } catch (e) {
    console.log(e);
  } finally {
    likeButton.removeAttribute("disabled");
  }
};

const unlikePost = async (postID) => {
  unlikeButton.setAttribute("disabled", "disabled");

  try {
    await fetch(`/blog-enhanced/api/react/?action=unlike&id=${postID}`);

    let likesCount = Number(likeCountComponent.innerHTML);

    likeCountComponent.innerHTML = likesCount <= 0 ? 0 : likesCount - 1;

    likeButton.style.display = "block";
    unlikeButton.style.display = "none";
  } catch (e) {
    console.log(e);
  } finally {
    unlikeButton.removeAttribute("disabled");
  }
};
