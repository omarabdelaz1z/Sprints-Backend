const followButton = document.getElementById("follow-button");
const unfollowButton = document.getElementById("unfollow-button");
const followCountComponent = document.getElementById("follow-count");

const followUser = async (follower_id) => {
  followButton.setAttribute("disabled", "disabled");

  try {
    const response = await fetch(
      `/blog-enhanced/api/follow/?action=follow&follower_id=${follower_id}`
    );

    console.log(response);

    const followCount = Number(followCountComponent.innerHTML);

    followCountComponent.innerHTML = followCount + 1;

    followButton.style.display = "none";
    unfollowButton.style.display = "block";
  } catch (e) {
    console.log(e);
  } finally {
    followButton.removeAttribute("disabled");
  }
};

const unfollowUser = async (follower_id) => {
  unfollowButton.setAttribute("disabled", "disabled");

  try {
    await fetch(
      `/blog-enhanced/api/follow/?action=unfollow&follower_id=${follower_id}`
    );

    const followCount = Number(followCountComponent.innerHTML);

    followCountComponent.innerHTML = followCount <= 0 ? 0 : followCount - 1;

    followButton.style.display = "block";
    unfollowButton.style.display = "none";
  } catch (e) {
    console.log(e);
  } finally {
    unfollowButton.removeAttribute("disabled");
  }
};
