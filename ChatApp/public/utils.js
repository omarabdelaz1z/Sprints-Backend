/**
 * Check if entered button is enter only.
 * @param Event: destructured into altKey, ctrlKey, shiftKey and key
 * @returns Boolean True if both altKey and 'enter' key are pressed, false otherwise.
 */
const isNewLine = ({ altKey, ctrlKey, shiftKey, key }) => {
  return !(ctrlKey || shiftKey) && altKey && key === "Enter";
};

/**
 * Check if entered button is enter only.
 * @param Event: destructured into altKey, ctrlKey, shiftKey and key
 * @returns Boolean: True if 'enter' is pressed, false otherwise.
 */
const isSend = ({ altKey, ctrlKey, shiftKey, key }) => {
  return !(altKey || ctrlKey || shiftKey) && key === "Enter";
};

/**
 * Check if a string is empty or undefined.
 * @param {String} content
 * @returns True if empty, False otherwise.
 */
const isContentEmpty = (content) =>
  typeof content === "undefined" || content.trim() === "";

/**
 * Reset the timer.
 * @param {Number} minutes
 * @returns Change the minutes into seconds
 */
const reset = (minutes) => minutes * 60;

/**
 * Convert Seconds to Minutes and Seconds (MM:SS)
 * @param {Number} sec
 * @returns A template literal in form of MM:SS
 */
const formatTime = (sec) => {
  let minutes = Math.floor(sec / 60);
  let seconds = sec % 60;

  minutes = minutes < 10 ? "0" + minutes : minutes;
  seconds = seconds < 10 ? "0" + seconds : seconds;

  return `${minutes}:${seconds}`;
};

/**
 * Render the sent message based on the sender (You) or (Other User)
 * @param request.body destructured into: username, userId and content
 */
const renderMessage = ({ username, userId, content, receivedDate }) => {
  const chatLog = document.getElementById("chat-log");

  const newMessageDiv = document.createElement("div");
  const newMessageLi = document.createElement("li");

  const othersHTML = `(${username}): ${content}<br>${receivedDate}`;

  const selfHTML = `(You): ${content}<br>${receivedDate}`;

  const { username: localUsername, userId: localUserId } = JSON.parse(
    localStorage.getItem("data")
  );

  if (username === localUsername && userId === localUserId) {
    newMessageLi.innerHTML = selfHTML;
    newMessageDiv.classList.add("message-self");
  } else {
    newMessageLi.innerHTML = othersHTML;
    newMessageDiv.classList.add("message-others");
  }

  newMessageDiv.appendChild(newMessageLi);
  chatLog.appendChild(newMessageDiv);
};

/**
 * Get User Count per Group
 * @param String group
 * @returns {'group': , 'count': }
 */
const getUserCount = async (group) => {
  try {
    const res = await fetch(`/usercount/${group}`, {
      method: "GET",
      headers: {
        Accept: "application/json",
      },
    });

    const json = await res.json();
    return json;
  } catch (err) {
    console.log(err);
  }
};

export {
  isSend,
  isNewLine,
  isContentEmpty,
  formatTime,
  reset,
  renderMessage,
  getUserCount,
};
